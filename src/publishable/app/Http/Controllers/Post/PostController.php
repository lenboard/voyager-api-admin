<?php

namespace App\Http\Controllers\Post;

use App\Models\Post\Post;
use App\Models\Post\Category;
use App\Http\Controllers\Controller as BaseController;

/**
 * Controller for posts.
 */
class PostController extends BaseController
{
    /**
     * Display page posts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::news()
            ->published()
            ->paginate();

        return view('post.index', compact('posts'));
    }

    /**
     * Display page some posts.
     *
     * @param int $id Post id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $post = Post::where('id', $id)
            ->published()
            ->firstOrFail();

        $lastPosts = Post::published()
            ->where('id', '<>', $id)
            ->orderBy('created_at', 'desc')
            ->limit(Post::LIMIT_LAST_NEWS)
            ->get();

        $dayNews = Post::where('featured',  1)
            ->published()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('post.show', compact('post', 'lastPosts', 'dayNews'));
    }

    /**
     * Display posts by category.
     *
     * @param \App\Models\Post\Category $category Category model
     * @return \Illuminate\View\View
     */
    public function byCategory(Category $category)
    {
        if ($category->parent_id != Category::CATEGORY_NEWS_ID) {
            abort(404);
        }

        $posts = Post::published()
            ->byCategory($category->id)
            ->paginate();

        return view('post.index', compact('posts'));
    }

    /**
     * Display posts by tag.
     *
     * @param string $tagName Tag name
     * @return \Illuminate\View\View
     */
    public function byTag($tagName)
    {
        $posts = Post::news()
            ->byTag($tagName)
            ->published()
            ->paginate();

        return view('post.index', compact('posts'));
    }
}