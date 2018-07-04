<?php

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/posts/create', 'Admin\PostController@create')->name('voyager.posts.create');
    Route::post('/posts', 'Admin\PostController@store')->name('voyager.posts.store');
    Route::get('/posts/{post}/edit', 'Admin\PostController@edit')->name('voyager.posts.edit');
    Route::put('/posts/{post}', 'Admin\PostController@update')->name('voyager.posts.update');
    Route::post('/categories', 'Admin\CategoriesController@store')->name('voyager.categories.store');
    Route::put('/categories/{category}', 'Admin\CategoriesController@update')->name('voyager.categories.update');
    Route::get('/categories/create', 'Admin\CategoriesController@create')->name('voyager.categories.create');
    Route::get('/categories/{category}/edit', 'Admin\CategoriesController@edit')->name('voyager.categories.edit');
    Route::get('/categories-list/{id?}', 'Admin\CategoriesController@index')->name('voyager.categories.index');
    Route::get('/translates', 'Admin\TranslateController@index')->name('lang.file.index');
    Route::get('/translates/edit-lang-file/{cryptFileName}', 'Admin\TranslateController@editLangFile')->name('lang.file.form');
    Route::put('/translates/update-lang-file/{cryptFileName}', 'Admin\TranslateController@updateLangFile')->name('lang.file.update');
    Route::post('/translates/add-keys-to-lang-files/{cryptFileName}', 'Admin\TranslateController@addKeysToLangFiles')->name('lang.file.add.keys');
    Route::get('/translates/upgrade-lang-file/', 'Admin\TranslateController@upgradeLangFiles')->name('lang.file.upgrade');
    Route::get('/translates/export-to-db/', 'Admin\TranslateController@exportToDb')->name('lang.file.export-to-db');
    Route::get('/translates/update-db/', 'Admin\TranslateController@updateTranslationInDb')->name('lang.file.update-db');
    Route::get('/translates/import-from-db/', 'Admin\TranslateController@importFromDb')->name('lang.file.import-from-db');
});