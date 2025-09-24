<?php

namespace App\Http\Controllers\Api\v1\Bible;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BibleOldTestamentController
{
    public function index()
{
    $response = Http::get("https://bible.helloao.org/api/BSB/books.json");

    if ($response->successful()) {
        $books = $response->json()['books'];

        $oldTestament = array_filter($books, fn($b) => $b['order'] <= 39);

        $oldTestament = array_map(fn($b) => [
            'id' => $b['id'],
            'name' => $b['name'],
            'numberOfChapters' => $b['numberOfChapters']
        ], $oldTestament);

        return response()->json([
            'old_testament' => array_values($oldTestament),
            // 'new_testament' => array_values($newTestament),
        ]);
    }

    return response()->json(['error' => 'Failed to fetch data'], 500);
}

public function getBookChapters($bookId)
{
    $response = Http::get("https://bible.helloao.org/api/BSB/books.json");

    if ($response->successful()) {
        $books = $response->json()['books'];

    // get book by id
        $book = collect($books)->firstWhere('id', $bookId);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // generate chapters from 1 to numberOfChapters
        $chapters = range(1, $book['numberOfChapters']);

        return response()->json([
            'book' => $book['name'],
            'chapters' => $chapters
        ]);
    }

    return response()->json(['error' => 'Failed to fetch data'], 500);
}

public function getChapterVerses($book, $chapter, $translation = 'BSB', $verse = 'all')
{
    $url = "https://bible.helloao.org/api/{$translation}/{$book}/{$chapter}.json";
    $response = Http::get($url);

    if (!$response->successful()) {
        return response()->json([
            'status' => false,
            'error' => 'Chapter not found or API error'
        ], 404);
    }

    $data = $response->json();

    if (!isset($data['chapter']['content'])) {
        return response()->json([
            'status' => false,
            'error' => $data['message'] ?? 'Verses not found'
        ], 404);
    }

    // Filter only verse items
    $verses = collect($data['chapter']['content'])
    ->where('type', 'verse')
    ->map(function($v) {
        // Flatten content array
        $text = collect($v['content'])->map(function($c) {
            if (is_array($c)) {
                return implode(' ', array_values($c)); // flatten nested array
            }
            return $c; // keep string as is
        })->implode(' '); // join all
        return [
            'verse' => $v['number'],
            'text'  => $text
        ];
    })->values();


    return response()->json([
        'status' => true,
        'book' => $data['book']['name'] ?? $book,
        'chapter' => $chapter,
        'verses' => $verses
    ]);
}






}
