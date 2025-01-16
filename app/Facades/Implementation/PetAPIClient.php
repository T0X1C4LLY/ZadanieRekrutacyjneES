<?php

declare(strict_types=1);

namespace App\Facades\Implementation;

use App\Enums\PetStatus;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class PetAPIClient
{
    public function add(
        ?int $id,
        ?Category $category,
        string $name,
        array $photoUrls,
        ?PetStatus $status,
        ?array $tags,
    ): bool|array {
        $payload = [
            'id' => $id,
            'name' => $name,
            'photoUrls' => $photoUrls,
        ];

        if ($category) {
            $payload['category'] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        if ($status) {
            $payload['status'] = $status->value;
        }

        if ($tags) {
            $payload['tags'] = $tags;
        }

        $response = Http::post('https://petstore.swagger.io/v2/pet', $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function get(int $id): bool|array
    {
        $response = Http::get(sprintf('https://petstore.swagger.io/v2/pet/%d', $id));

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function edit(
        ?int $id,
        ?Category $category,
        string $name,
        array $photoUrls,
        ?PetStatus $status,
        ?array $tags,
    ): bool|array {
        $payload = [
            'id' => $id,
            'name' => $name,
            'photoUrls' => $photoUrls,
        ];

        if ($category) {
            $payload['category'] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        if ($status) {
            $payload['status'] = $status->value;
        }

        if ($tags) {
            $payload['tags'] = $tags;
        }

        $response = Http::put('https://petstore.swagger.io/v2/pet', $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function delete(int $id): bool|array
    {
        $response = Http::withHeaders([
            'api_key' => env('PET_API_KEY'),
        ])->delete(sprintf('https://petstore.swagger.io/v2/pet/%d', $id), [

        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }
}
