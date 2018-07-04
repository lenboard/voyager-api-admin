<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Post\Category;
use TCG\Voyager\Models\Post as VoyagerPost;
use TCG\Voyager\Traits\HasRelationships;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

/**
 * Post model.
 */
class Post extends VoyagerPost
{
    use Translatable,
        Resizable,
        HasRelationships;

    protected $translatable = ['title', 'seo_title', 'excerpt', 'body', 'meta_description', 'meta_keywords'];

    /**
     * Post status published.
     *
     * @var string
     */
    const STATUS_PUBLISHED = 'PUBLISHED';

    /**
     * Post status draft.
     *
     * @var string
     */
    const STATUS_DRAFT = 'DRAFT';

    /**
     * Post status pending.
     *
     * @var string
     */
    const STATUS_PENDING = 'PENDING';

    /**
     * Count last news in block <Last news>.
     *
     * @var int
     */
    const LIMIT_LAST_NEWS = 2;

    /**
     * Scope published posts.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope posts in category news.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNews(Builder $query)
    {
        $categoriesNewsIds = Category::childrenCategoriesById(Category::CATEGORY_NEWS_ID)->pluck('id');

        return $query->whereIn('category_id', $categoriesNewsIds)
            ->orWhere('category_id', Category::CATEGORY_NEWS_ID);
    }

    /**
     * Scope posts in category knowledge.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKnowledge(Builder $query)
    {
        $categoriesKnowledgeIds = Category::childrenCategoriesById(Category::CATEGORY_KNOWLEDGE_ID)->pluck('id');

        return $query->whereIn('category_id', $categoriesKnowledgeIds)
            ->orWhere('category_id', Category::CATEGORY_KNOWLEDGE_ID);
    }

    /**
     * Scope posts by category.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope posts by tag.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTag(Builder $query, $tagName)
    {
        return $query->where('tags', 'like', $tagName)
            ->orWhere('tags', 'like', $tagName . ',%')
            ->orWhere('tags', 'like', '%,'. $tagName)
            ->orWhere('tags', 'like', '%,'. $tagName . ',%');
    }

    /**
     * Relation with category.
     *
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getListTagsAttribute()
    {
        if (!empty($this->tags)) {
            return explode(',', $this->tags);
        }

        return [];
    }

    public function getIsBelongsRootCategoryAttribute()
    {
        return in_array($this->category_id, [Category::CATEGORY_NEWS_ID, Category::CATEGORY_KNOWLEDGE_ID]);
    }
}
