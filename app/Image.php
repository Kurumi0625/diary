<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = array('id');
    
    public static $rules = array(
        'diary_id' => 'required',
        );
        
        public function diaries()
    {
        return $this->belongsTo(Diary::class);
    }
}
