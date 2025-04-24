<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    //this model should be connected to this table name 'category_post'.
    protected $table = 'category_post';

    // make the model aware that we do not need/want to use the timestamps.
    public $timestamps = false;

    // allow mass assignment for createMany()
    protected $fillable = ['category_id', 'post_id'];

    # to get the name of the category_id
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
