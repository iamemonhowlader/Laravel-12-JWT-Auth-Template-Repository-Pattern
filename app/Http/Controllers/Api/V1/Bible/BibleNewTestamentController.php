<?php

namespace App\Http\Controllers\Api\v1\Bible;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BibleNewTestamentController
{
    public function index()
{
    $response = Http::get("https://bible.helloao.org/api/BSB/books.json");

    if ($response->successful()) {
        $books = $response->json()['books'];

        // $oldTestament = array_filter($books, fn($b) => $b['order'] <= 39);
        $newTestament = array_filter($books, fn($b) => $b['order'] >= 40);

        $newTestament = array_map(fn($b) => [
            'id' => $b['id'],
            'name' => $b['name'],
            'numberOfChapters' => $b['numberOfChapters']
        ], $newTestament);

        return response()->json([
            // 'old_testament' => array_values($oldTestament),
            'new_testament' => array_values($newTestament),
        ]);
    }

    return response()->json(['error' => 'Failed to fetch data'], 500);
}

    //
}
