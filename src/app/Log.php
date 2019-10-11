<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['unit_id','keterangan','breakdown','ready','kategori'];
    protected $with = ['unit'];
    public function unit(){
        return $this->belongsTo(\App\Unit::class);
    }
}
