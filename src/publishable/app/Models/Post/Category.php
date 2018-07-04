<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use TCG\Voyager\Traits\HasRelationships;
use TCG\Voyager\Traits\Translatable;

/**
 * Category model.
 */
class Category extends Model
{
    use Translatable,
        HasRelationships;

    protected $translatable = ['name'];

    protected $table = 'categories';

    protected $fillable = ['slug', 'name'];

    /**
     * Id knowledge category.
     *
     * @var int
     */
    const CATEGORY_KNOWLEDGE_ID = 1;

    /**
     * Id news category.
     *
     * @var int
     */
    const CATEGORY_NEWS_ID = 2;

    /**
     * Get list post from category.
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post\Post');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Post\Category', 'parent_id', 'id');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Get children categories by id parent category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query {@inheritdoc}
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChildrenCategoriesById(Builder $query, $id)
    {
        return $query->where('parent_id', $id);
    }
}
