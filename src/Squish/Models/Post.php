<?php

namespace MakeWeb\Squish\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'ID';

    public $timestamps = false;
}

