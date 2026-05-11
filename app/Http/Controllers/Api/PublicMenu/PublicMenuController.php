<?php

namespace App\Http\Controllers\Api\PublicMenu;

use App\Http\Controllers\Controller;
use App\Services\PublicMenuService;
use Illuminate\Http\JsonResponse;

class PublicMenuController extends Controller
{
    public function __construct(
        private readonly PublicMenuService $publicMenuService,
    ) {}

    public function show(string $token): JsonResponse
    {
        $menu = $this->publicMenuService->getByToken($token);

        return response()->json([
            'data' => $menu->toArray(),
        ]);
    }
}
