<?php

namespace MakeWeb\Squish\Models;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'meta_id';

    protected $table = 'postmeta';

    public $timestamps = false;
}


