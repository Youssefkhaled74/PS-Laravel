<?php

namespace App\Http\Controllers\Api\V1\User\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Chat\SendMessageRequest;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\Chat\MessageResource;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;
use App\Models\Conversation;

class MessageController extends Controller
{
    use ApiResponseTrait;

    protected ChatService $service;

    public function __construct(ChatService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        $filters = $request->only(['per_page']);
        $messages = $this->service->getMessages($user, $conversation, $filters);
        return $this->paginated(MessageResource::collection($messages), null, 'chat.messages_list');
    }

    public function store(SendMessageRequest $request, Conversation $conversation)
    {
        $user = $request->user();
        $data = $request->validated();
        $msg = $this->service->sendMessage($user, $conversation, $data);
        return $this->success(new MessageResource($msg), 'chat.message_sent');
    }
}
