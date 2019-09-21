<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $guarded = array('id');
    
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
    );
    protected $fillable = ['user_id', 'title', 'body', 'date'];
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
