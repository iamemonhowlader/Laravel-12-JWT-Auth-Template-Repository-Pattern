<?php

use App\Http\Controllers\Api\v1\Bible\BibleNewTestamentController;
use App\Http\Controllers\Api\v1\Bible\BibleOldTestamentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/bible')->name('api.auth.')->group(function () {
    // Bible New Testament routes
    Route::get('/new-testament', [BibleNewTestamentController::class, 'index']);

    // Bible Old Testament routes
    Route::get('/old-testament', [BibleOldTestamentController::class, 'index']);
    Route::get('/old-testament/{book}', [BibleOldTestamentController::class, 'getBookChapters']);
    Route::get('/old-testament/{book}/{chapter}', [BibleOldTestamentController::class, 'getChapterVerses']);
});
