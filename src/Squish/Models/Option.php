<?php

namespace MakeWeb\Squish\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'option_id';

    public $timestamps = false;
}
