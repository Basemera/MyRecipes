<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Category extends Model {
    protected $table = 'categories';

    protected $fillable = ['name', 'description'];

    /**
     * Method to return the user a category is associated with
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Local query scope to return categories associated with a user
     *
     * @param $query
     * @param $user_id user_id of the user
     * @return mixed
     */
    public function scopeUserCategories($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Method to return the categories' recipes
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasmany
     */
    public function recipes() {
        return $this->hasMany('App\Models\Recipe');
    }

}