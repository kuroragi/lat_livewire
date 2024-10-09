<?php

namespace App\Models;

use App\Livewire\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get all of the Post for the PostCategories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Post(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category', 'slug');
    }
}
