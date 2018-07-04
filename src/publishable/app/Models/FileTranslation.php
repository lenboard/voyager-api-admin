<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileTranslation extends Model
{
    /**
     * {@inheritdoc}
     *
     * @var array $fillable
     */
    protected $fillable = [
        'content',
        'file_path',
        'lang',
        'md5',
    ];
}
