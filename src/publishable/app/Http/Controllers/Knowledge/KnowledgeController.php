<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Post\Post;
use App\Models\Post\Category;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

/**
 * Controller for knowledge.
 */
class KnowledgeController extends BaseController
{
    /**
     * Display page knowledge categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::childrenCategoriesById(Category::CATEGORY_KNOWLEDGE_ID)
            ->get();

        return view('knowledge.index', compact('categories'));
    }

    /**
     * Display page list categories with posts.
     *
     * @param int $id Post id
     * @return \Illuminate\View\View
     */
    public function list($id)
    {
        $mainCategory = Category::where('id', $id)
            ->firstOrFail();

        $categories = Category::childrenCategoriesById($mainCategory->id)
            ->get();

        return view('knowledge.category', compact('categories', 'mainCategory'));
    }

    /**
     * Find categories and posts by search string.
     *
     * @param \Illuminate\Http\Request $request Request object
     * @return \Illuminate\View\View
     */
    public function searchTextInPosts(Request $request)
    {
        $categoryId = $request->get('categoryId');
        $searchQuery = $request->get('searchQuery', '');

        $mainCategory = Category::where('id', $categoryId)
            ->firstOrFail();

        $categories = Category::where('parent_id', '=', $categoryId)
            ->with(['posts' => function($query) use ($searchQuery) {
                $query->where('body', 'like', "%{$searchQuery}%")
                    ->orWhere('title', 'like', "%{$searchQuery}%");
            }])
            ->whereHas('posts', function($query) use ($searchQuery) {
                $query->where('body', 'like', "%{$searchQuery}%")
                    ->orWhere('title', 'like', "%{$searchQuery}%");
            })
            ->get()
            ->getDictionary();

        $onlyCategories = Category::where([
            ['parent_id', '=', $categoryId],
            ['name', 'like', "%{$searchQuery}%"]
        ])
        ->with('posts')
        ->get()
        ->getDictionary();

        $categories = array_except($categories, array_keys($onlyCategories));
        $categories = array_merge($categories, $onlyCategories);

        return view('knowledge._block_content_posts', compact('categories', 'mainCategory'));
    }
}
