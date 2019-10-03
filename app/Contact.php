<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table 		= "contact";
    protected $primaryKey 	= "id";
    public $timestamps 		= true;
    protected $fillable = [
        'name', 'number_phone', 'email','address','content','status',
    ];
}
