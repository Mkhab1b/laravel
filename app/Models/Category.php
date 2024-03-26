<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function isDescendantOf($category)
    {
        $parent = $this->parent;

        while ($parent) {
            if ($parent->id === $category->id) {
                return true;
            }

            $parent = $parent->parent;
        }

        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if ($category->parent) {
                if ($category->isDescendantOf($category)) {
//                    return false;
                    throw new \Exception("Category cannot be a descendant of itself.");

                }
            }
        });
    }
}
