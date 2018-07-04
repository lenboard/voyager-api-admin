<?php

// Knowledge
Route::prefix('knowledge')->group(function(){
    Route::get('/', 'Knowledge\KnowledgeController@index')->name('knowledge');
    Route::get('/{id}', 'Knowledge\KnowledgeController@list')->where('id', '[0-9]+')->name('knowledge.list');
    Route::post('/search', 'Knowledge\KnowledgeController@searchTextInPosts')->name('knowledge.search');
});