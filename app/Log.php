<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * @package App
 */
class Log extends Model
{
    protected $fillable = ['unit_id','keterangan','breakdown','ready','kategori','location', 'checked'];
    protected $with = ['unit'];
    public function unit(){
        return $this->belongsTo(\App\Unit::class);
    }
}
