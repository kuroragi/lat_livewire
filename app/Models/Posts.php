<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'tittle',
        'header_image',
        'content',
        'author',
        'post_category',
    ];

    /**
     * Get the postCategory that owns the Posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postCategory(): BelongsTo
    {
        return $this->belongsTo(PostCategories::class, 'post_category', 'slug');
    }
}
