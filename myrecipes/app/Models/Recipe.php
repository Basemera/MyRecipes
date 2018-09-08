<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Recipe extends Model {
        protected $table = 'recipes';
        protected $fillable = ['name', 'description'];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function category () {
            return $this->belongsTo('App\Models\Category');
        }

        public function scopeCategoryRecipes($query, $category_id)
        {
            return $query->where('category_id', $category_id);
        }

        public function comments() {
            return $this->hasMany('App\Models\Comments');
        }
    }