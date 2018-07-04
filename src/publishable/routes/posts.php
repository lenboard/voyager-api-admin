<?php

// Posts
Route::prefix('news')->group(function(){
    Route::get('/', 'Post\PostController@index')->name('news');
    Route::get('/{id}', 'Post\PostController@show')->where('id', '[0-9]+')->name('news.show');
    Route::get('/category/{category}', 'Post\PostController@byCategory')->name('news.category');
    Route::get('/tag/{tagName}', 'Post\PostController@byTag')->name('news.tag');
});