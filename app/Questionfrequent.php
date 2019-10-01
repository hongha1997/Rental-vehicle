<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionfrequent extends Model
{
	protected $table 		= "question_frequent";
    protected $primaryKey 	= "id";
    public $timestamps 		= true;
    protected $fillable = [
        'title', 'content', 'status',
    ];
}
