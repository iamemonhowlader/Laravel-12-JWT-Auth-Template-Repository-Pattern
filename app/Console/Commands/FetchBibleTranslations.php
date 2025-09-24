<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchBibleTranslations extends Command
{
    protected $signature = 'fetch:bible-translations';
    protected $description = 'Fetch and display Bible translation names from the API';

    public function handle()
    {
        try {
            $response = Http::get('https://bible.helloao.org/api/available_translations.json');

            if ($response->ok()) {
                // Extract the short names correctly
                $translations = collect($response->json()['translations'])
                    ->pluck('shortName')
                    ->toArray();

                $this->info("Available Translations:");
                foreach ($translations as $shortName) {
                    $this->line($shortName);
                }
            } else {
                $this->error("Failed to fetch translations. Status Code: " . $response->status());
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
