<?php

namespace App\Http\Controllers\Api\V1\User\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Chat\CreateConversationRequest;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\Chat\ConversationResource;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;
use App\Models\Conversation;

class ConversationController extends Controller
{
    use ApiResponseTrait;

    protected ChatService $service;

    public function __construct(ChatService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['q', 'per_page']);
        $list = $this->service->listConversationsForUser($user, $filters);
        return $this->paginated(ConversationResource::collection($list), null, 'chat.conversation_list');
    }

    public function store(CreateConversationRequest $request)
    {
        $user = $request->user();
        $conv = $this->service->createOrGetConversationForUser($user, (int)$request->vendor_id);
        return $this->success(new ConversationResource($conv), 'chat.conversation_created');
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        $this->service->markAsRead($user, $conversation);
        return $this->success(new ConversationResource($conversation->fresh()), 'chat.marked_read');
    }
}
