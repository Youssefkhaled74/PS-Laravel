# Stories Feature - Implementation Documentation

## Overview
Instagram-like stories feature for PS Laravel project. Allows vendors to create stories (image/video) that users can view. Admin dashboard provides full CRUD management.

## Database Schema

### vendor_stories
- `id` - Primary key
- `vendor_id` - FK to vendors
- `title` - Optional story title
- `media_type` - ENUM('image', 'video')
- `media_path` - Storage path for media
- `thumb_path` - Optional thumbnail path
- `duration_seconds` - Display duration (default 5s for images)
- `sort_order` - Display order (0-based)
- `status` - ENUM('active', 'inactive')
- `start_at` - Optional start datetime
- `end_at` - Optional end datetime
- `created_at`, `updated_at`

### vendor_story_views
- `id` - Primary key
- `vendor_story_id` - FK to vendor_stories
- `user_id` - FK to users
- `viewed_at` - Timestamp
- UNIQUE constraint on (user_id, vendor_story_id)

## Models & Relationships

### VendorStory
- `belongsTo` Vendor
- `hasMany` VendorStoryView
- Scopes: `active()`, `ordered()`
- Methods: `isActive()`, `getScheduleStatus()`, `getMediaUrlAttribute()`, `getThumbUrlAttribute()`

### VendorStoryView
- `belongsTo` VendorStory
- `belongsTo` User

### Vendor
- `hasMany` VendorStory (stories)
- `hasMany` VendorStory (activeStories) - filtered

### User
- `hasMany` VendorStoryView (storyViews)

## User API Endpoints

All endpoints require `auth:sanctum` middleware and respect `Lang` header (en/ar).

### 1. GET /api/user/stories/vendors
Get list of vendors with active stories.

**Response:**
```json
{
  "success": true,
  "message": "Vendors retrieved successfully.",
  "data": [
    {
      "vendor_id": 1,
      "vendor_name": "Vendor 1",
      "vendor_logo_url": "http://example.com/uploads/vendor1.jpg",
      "country_code": "SA",
      "has_unread": true,
      "stories_count": 3,
      "latest_story_created_at": "2026-01-15T10:30:00Z"
    }
  ]
}
```

### 2. GET /api/user/stories/vendor/{vendor}
Get stories for a specific vendor.

**Response:**
```json
{
  "success": true,
  "message": "Stories retrieved successfully.",
  "data": {
    "vendor": {
      "id": 1,
      "name": "Vendor 1",
      "logo_url": "...",
      "country_code": "SA"
    },
    "stories": [
      {
        "id": 1,
        "title": "New Product",
        "media_type": "image",
        "media_url": "http://example.com/...",
        "thumb_url": null,
        "duration_seconds": 5,
        "is_viewed": false,
        "created_at": "2026-01-15T10:30:00Z"
      }
    ],
    "next_vendor_id": 2
  }
}
```

### 3. POST /api/user/stories/{story}/view
Mark story as viewed.

**Response:**
```json
{
  "success": true,
  "message": "Story marked as viewed.",
  "data": {
    "vendor_has_unread": false
  }
}
```

### 4. POST /api/user/stories/vendor/{vendor}/mark-all-viewed
Mark all vendor stories as viewed.

**Response:**
```json
{
  "success": true,
  "message": "All stories marked as viewed.",
  "data": {
    "marked_count": 3
  }
}
```

## Admin Dashboard

### Routes
- `/admin/stories` - Global stories management
- `/admin/vendors/{vendor}/stories` - Vendor-specific stories
- CRUD: index, create, store, edit, update, destroy
- Toggle status: PATCH `/admin/stories/{story}/toggle`

### Features
- Create/Edit stories with media upload
- Filter by vendor, status, schedule
- Preview media thumbnails
- Status badges (Active, Inactive, Upcoming, Expired)
- Bulk delete via confirmation modal
- Scheduling (start_at, end_at)
- Sort order management

### Views
- `resources/views/admin/vendors/stories/index.blade.php`
- `resources/views/admin/vendors/stories/create.blade.php`
- `resources/views/admin/vendors/stories/edit.blade.php`

## Service Layer

### VendorStoryService
- `getVendorsWithStories($userId)` - Get vendors with unread status
- `getVendorStories($vendor, $userId)` - Get stories with viewed status
- `markStoryAsViewed($story, $userId)` - Mark single story
- `markVendorStoriesAsViewed($vendor, $userId)` - Mark all
- `vendorHasUnread($vendor, $userId)` - Check unread
- `uploadMedia($file, $vendorId, $type)` - Upload handler
- `deleteMedia($mediaPath)` - Delete handler
- `getNextVendor($currentVendor, $userId)` - Navigation
- `getStoryAnalytics($story)` - Views count
- `getVendorAnalytics($vendor)` - Vendor analytics

## File Upload

### Storage
- Path: `public/uploads/stories/{vendor_id}/`
- Naming: `{uniqid}_{timestamp}.{ext}`
- Validation: 
  - Images: jpg, jpeg, png, webp
  - Videos: mp4, mov
  - Max size: 50MB

### Preview
Uses existing upload preview component from admin.js:
- `data-upload-preview`
- `data-preview-target="#element"`
- `data-filename-target="#element"`

## Story Visibility Rules

A story is visible to users if ALL conditions met:
1. `status = 'active'`
2. `start_at IS NULL OR start_at <= NOW()`
3. `end_at IS NULL OR end_at >= NOW()`
4. `vendor.status = 'active'`

Stories are ordered by:
1. `vendor.sort_order` (if applicable)
2. `story.sort_order`
3. `story.created_at DESC`

## Testing

### Seeder
Run: `php artisan db:seed --class=VendorStoriesSeeder`

Creates:
- 5 vendors with business profiles
- 2-4 stories per vendor
- Random country codes (SA, AE, KW, BH, QA)
- Placeholder media paths

### Postman Collection
File: `postman/PS_User_Stories_API.postman_collection.json`

Variables:
- `base_url`: http://127.0.0.1:8000
- `user_token`: Your Sanctum token

## Migration Commands

```bash
# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed --class=VendorStoriesSeeder

# Rollback
php artisan migrate:rollback
```

## Localization

All messages use Laravel's `__()` helper and respect the `Lang` header via `SetLocaleFromHeader` middleware.

Translation keys used:
- `admin.stories.*`
- Generic success/error messages
- Status labels (active, inactive, upcoming, expired)

## UI Components

Uses existing PS admin design system:
- `.card`, `.toolbar`, `.filters`
- `.btn-gold`, `.btn-ghost`, `.btn-success`, `.btn-danger`
- `.badge-neutral`, `.badge-warning`, `.badge-muted`
- `.table`, `.table-wrap`, `.table-actions`
- `.alert-success`, `.alert-danger`
- `.form-group`, `.input`, `.file-input`
- `.upload-preview` component

## Next Steps

1. Run migrations: `php artisan migrate`
2. Seed test data: `php artisan db:seed --class=VendorStoriesSeeder`
3. Create placeholder media files in `public/uploads/stories/`
4. Test API endpoints using Postman collection
5. Access admin at: `/admin/stories`
6. Add "Stories" link to admin navigation sidebar

## Files Created

### Migrations
- `database/migrations/2026_01_15_120000_create_vendor_stories_table.php`
- `database/migrations/2026_01_15_120100_create_vendor_story_views_table.php`

### Models
- `app/Models/VendorStory.php`
- `app/Models/VendorStoryView.php`
- Updated: `app/Models/Vendor.php`
- Updated: `app/Models/User.php`

### Controllers
- `app/Http/Controllers/Api/UserStoriesController.php`
- `app/Http/Controllers/Admin/VendorStoriesController.php`

### Services
- `app/Services/VendorStoryService.php`

### Resources
- `app/Http/Resources/VendorStoryVendorListResource.php`
- `app/Http/Resources/VendorStoryItemResource.php`

### Routes
- `routes/api/stories.php`
- Updated: `routes/api.php`
- Updated: `routes/web/admin/vendors.php`

### Views
- `resources/views/admin/vendors/stories/index.blade.php`
- `resources/views/admin/vendors/stories/create.blade.php`
- `resources/views/admin/vendors/stories/edit.blade.php`

### Seeders
- `database/seeders/VendorStoriesSeeder.php`

### Postman
- `postman/PS_User_Stories_API.postman_collection.json`

### Documentation
- `docs/STORIES_FEATURE.md` (this file)

---

**Status:** ✅ Feature fully implemented and ready for testing
**Date:** January 15, 2026
**Version:** 1.0
