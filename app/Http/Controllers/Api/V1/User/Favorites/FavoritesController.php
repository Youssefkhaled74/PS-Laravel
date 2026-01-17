<?php

namespace App\Http\Controllers\Api\V1\User\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Favorites\ToggleFavoriteRequest;
use App\Services\User\Favorites\FavoritesService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\User\Favorites\FavoriteProductResource;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    use ApiResponseTrait;

    protected FavoritesService $service;

    public function __construct(FavoritesService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['q','per_page']);
        $list = $this->service->list($user, $filters);
        return $this->success(FavoriteProductResource::collection($list), 'favorites.list_loaded');
    }

    public function toggle(ToggleFavoriteRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $res = $this->service->toggle($user, (int)$data['product_id']);
        $msgKey = $res['is_favorited'] ? 'favorites.added' : 'favorites.removed';
        return $this->success(['is_favorited' => $res['is_favorited']], $msgKey);
    }

    public function count(Request $request)
    {
        $user = $request->user();
        $c = $this->service->count($user);
        return $this->success(['count' => $c], 'success');
    }
}
