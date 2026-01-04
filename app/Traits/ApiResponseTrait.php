<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponseTrait
{
    protected function success($data = null, string $messageKey = 'success', $meta = null, int $code = 200)
    {
        $payload = [
            'success' => true,
            'message' => __("api.$messageKey"),
            'data' => $this->transformData($data),
            'errors' => null,
            'meta' => $meta ?? $this->transformMeta($data),
        ];

        return response()->json($payload, $code);
    }

    protected function error(string $messageKey = 'error', $errors = null, int $code = 400)
    {
        $payload = [
            'success' => false,
            'message' => __("api.$messageKey"),
            'data' => null,
            'errors' => $errors,
            'meta' => null,
        ];

        return response()->json($payload, $code);
    }

    protected function validationError($errors, string $messageKey = 'validation_failed')
    {
        return $this->error($messageKey, $errors, 422);
    }

    protected function paginated($resourceCollection, $paginator = null, string $messageKey = 'success')
    {
        $data = $resourceCollection->response()->getData(true);

        $payload = [
            'success' => true,
            'message' => __("api.$messageKey"),
            'data' => $data['data'] ?? null,
            'errors' => null,
            'meta' => $data['meta'] ?? $this->extractPaginationMeta($data),
        ];

        return response()->json($payload, 200);
    }

    private function transformData($data)
    {
        if ($data instanceof JsonResource) {
            return $data->toArray(request());
        }

        if ($data instanceof ResourceCollection) {
            return $data->response()->getData(true)['data'] ?? [];
        }

        return $data;
    }

    private function transformMeta($data)
    {
        if ($data instanceof ResourceCollection) {
            $resp = $data->response()->getData(true);
            return $resp['meta'] ?? $this->extractPaginationMeta($resp);
        }

        return null;
    }

    private function extractPaginationMeta(array $resp)
    {
        if (! isset($resp['meta']) && isset($resp['links']) && isset($resp['data'])) {
            return [
                'current_page' => $resp['current_page'] ?? null,
                'last_page' => $resp['last_page'] ?? null,
                'per_page' => $resp['per_page'] ?? null,
                'total' => $resp['total'] ?? null,
            ];
        }

        return $resp['meta'] ?? null;
    }
}
