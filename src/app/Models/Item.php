<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_image',
        'item_name',
        'brand',
        'content',
        'situation',
        'price',
        'is_sold'
    ];

    protected $casts = [
        'situation' => 'integer',
    ];

    public const SITUATION = [
        0 => '良好',
        1 => '目立った傷や汚れなし',
        2 => 'やや傷や汚れあり',
        3 => '状態が悪い'
    ];

    public function getSituationLabelAttribute()
    {
        return self::SITUATION[$this->situation] ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isFavoritedBy($user)
    {
        if (!$user) {
            return false;
        }
        return $this->favoritedByUsers()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
