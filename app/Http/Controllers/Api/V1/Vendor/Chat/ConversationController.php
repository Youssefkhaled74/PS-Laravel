<?php

namespace App\Http\Controllers\Api\V1\Vendor\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Chat\CreateConversationRequest;
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
        $vendor = auth('vendor')->user();
        $filters = $request->only(['q', 'per_page']);
        $list = $this->service->listConversationsForVendor($vendor, $filters);
        return $this->paginated(ConversationResource::collection($list), null, 'chat.conversation_list');
    }

    public function store(CreateConversationRequest $request)
    {
        $vendor = auth('vendor')->user();
        $conv = $this->service->createOrGetConversationForVendor($vendor, (int)$request->user_id);
        return $this->success(new ConversationResource($conv), 'chat.conversation_created');
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        $vendor = auth('vendor')->user();
        $this->service->markAsRead($vendor, $conversation);
        return $this->success(new ConversationResource($conversation->fresh()), 'chat.marked_read');
    }
}
