<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'image', 'category', 'status','is_edited'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id');  
    }
    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    // public function getTranslation()
    // {
    //     // აბრუნებს მიმდინარე ენის თარგმანს, ან პირველს რაც მოხვდება
    //     return $this->translations->first() ?? $this;
    // }
}
