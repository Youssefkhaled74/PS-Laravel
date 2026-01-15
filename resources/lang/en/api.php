<?php

return [            
    // common
    'success' => 'Operation successful.',
    'error' => 'An error occurred.',
    'validation_failed' => 'Validation failed.',

    // auth
    'login_success' => 'Logged in successfully.',
    'register_success' => 'Registered successfully. OTP sent to phone.',
    'register_requires_otp' => 'Registration requires OTP verification.',
    'otp_sent' => 'OTP sent successfully.',
    'otp_verified' => 'OTP verified successfully.',
    'otp_invalid' => 'Invalid OTP.',
    'otp_expired' => 'OTP has expired.',
    'otp_too_many_attempts' => 'Too many attempts.',
    'otp_resend_wait' => 'Please wait before resending the code.',
    'otp_resent' => 'OTP resent successfully.',
    'otp_resend_not_allowed' => 'OTP resend not allowed yet. Please wait.',
    'password_reset_otp_sent' => 'Password reset code sent.',
    'password_reset_success' => 'Password updated successfully.',
    'unauthorized' => 'Unauthorized.',
    'phone_not_verified' => 'Phone not verified.',
    'user_not_found' => 'User not found.',
    'not_implemented' => 'Not implemented.',

    // generic
    'profile_updated' => 'Profile updated successfully.',
    'upload_success' => 'File(s) uploaded successfully.',
    'file_invalid' => 'Invalid file provided.',
    'file_too_large' => 'File size exceeds limit.',
    'not_found' => 'Resource not found.',
    'created' => 'Created successfully.',
    'updated' => 'Updated successfully.',
    'deleted' => 'Deleted successfully.',

    // addresses
    'address_created' => 'Address created successfully.',
    'address_updated' => 'Address updated successfully.',
    'address_deleted' => 'Address deleted successfully.',
    'address_set_default' => 'Address set as default.',
    'address_not_found' => 'Address not found.',
    'forbidden' => 'Forbidden.',
    'logout_success' => 'Logged out successfully.',

    // vendor auth
    'vendor.auth.register_success' => 'Vendor registered successfully.',
    'vendor.auth.register_failed' => 'Registration failed. Please try again.',
    'vendor.auth.login_success' => 'Logged in successfully.',
    'vendor.auth.invalid_credentials' => 'Invalid phone or password.',
    'vendor.auth.logged_out' => 'Logged out successfully.',
    'vendor.auth.logout_failed' => 'Logout failed.',

    // validation
    'validation.full_name_required' => 'Full name is required.',
    'validation.full_name_min' => 'Full name must be at least 3 characters.',
    'validation.phone_required' => 'Phone is required.',
    'validation.phone_unique' => 'Phone already exists.',
    'validation.phone_not_found' => 'Phone not found.',
    'validation.email_invalid' => 'Invalid email format.',
    'validation.email_unique' => 'Email already exists.',
    'validation.password_required' => 'Password is required.',
    'validation.password_min' => 'Password must be at least 8 characters.',
    'validation.password_confirmed' => 'Password confirmation does not match.',
    'validation.avatar_image' => 'Avatar must be an image.',
    'validation.avatar_max' => 'Avatar size must not exceed 2MB.',
];
