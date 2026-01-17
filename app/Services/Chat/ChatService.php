<?php

namespace App\Services\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ChatService
{
    public function listConversationsForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 12;
        $q = $filters['q'] ?? null;

        $query = Conversation::with(['vendor.latestMessage'])
            ->where('user_id', $user->id)
            ->orderByDesc('last_message_at');

        if ($q) {
            $query->whereHas('vendor', function ($qv) use ($q) {
                $qv->where('name_en', 'like', "%$q%")
                   ->orWhere('name_ar', 'like', "%$q%");
            });
        }

        return $query->paginate($perPage);
    }

    public function listConversationsForVendor(Vendor $vendor, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 12;
        $q = $filters['q'] ?? null;

        $query = Conversation::with(['user.latestMessage'])
            ->where('vendor_id', $vendor->id)
            ->orderByDesc('last_message_at');

        if ($q) {
            $query->whereHas('user', function ($qu) use ($q) {
                $qu->where('full_name', 'like', "%$q%")
                   ->orWhere('name_en', 'like', "%$q%");
            });
        }

        return $query->paginate($perPage);
    }

    public function getMessages($actor, Conversation $conversation, array $filters = [])
    {
        $perPage = $filters['per_page'] ?? 20;

        $this->assertActorBelongsToConversation($actor, $conversation);

        return Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function sendMessage($actor, Conversation $conversation, array $data): Message
    {
        $this->assertActorBelongsToConversation($actor, $conversation);

        $senderType = $this->actorType($actor);
        $senderId = $actor->id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'body' => $data['body'] ?? null,
            'message_type' => $data['message_type'] ?? 'text',
            'attachment_path' => $data['attachment_path'] ?? null,
        ]);

        $conversation->last_message_at = Carbon::now();

        // increment unread for other side
        if ($senderType === 'user') {
            $conversation->vendor_unread_count = $conversation->vendor_unread_count + 1;
        } else {
            $conversation->user_unread_count = $conversation->user_unread_count + 1;
        }

        $conversation->save();

        return $message->fresh();
    }

    public function markAsRead($actor, Conversation $conversation): void
    {
        $this->assertActorBelongsToConversation($actor, $conversation);

        $actorType = $this->actorType($actor);

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('sender_type', '!=', $actorType)
            ->whereNull('read_at')
            ->get();

        foreach ($messages as $msg) {
            $msg->read_at = Carbon::now();
            $msg->save();
        }

        if ($actorType === 'user') {
            $conversation->user_unread_count = 0;
        } else {
            $conversation->vendor_unread_count = 0;
        }

        $conversation->save();
    }

    public function createOrGetConversationForUser(User $user, int $vendorId): Conversation
    {
        $conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
            'vendor_id' => $vendorId,
        ]);

        return $conversation;
    }

    public function createOrGetConversationForVendor(Vendor $vendor, int $userId): Conversation
    {
        return Conversation::firstOrCreate([
            'user_id' => $userId,
            'vendor_id' => $vendor->id,
        ]);
    }

    protected function assertActorBelongsToConversation($actor, Conversation $conversation)
    {
        if ($actor instanceof User && $conversation->user_id !== $actor->id) {
            abort(403, __('api.chat.errors.unauthorized_conversation'));
        }

        if ($actor instanceof Vendor && $conversation->vendor_id !== $actor->id) {
            abort(403, __('api.chat.errors.unauthorized_conversation'));
        }
    }

    protected function actorType($actor): string
    {
        return $actor instanceof Vendor ? 'vendor' : 'user';
    }
}
