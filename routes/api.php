<?php

use App\Models\Tenant\Clothing\Category;
use Illuminate\Support\Facades\Route;

Route::get('/categories', function () {
    $categories = Category::all();

    return response()->json($categories);
});
