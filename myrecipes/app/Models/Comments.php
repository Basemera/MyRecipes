<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Comments extends Model {
    protected $table = 'comments';
    protected $fillable = ['Comment'];

    public function recipe () {
        return $this->belongsTo('App\Models\Recipe');
    }

    public function commentor () {
        return $this->belongsTo('App\Models\User');
    }
}