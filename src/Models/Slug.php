<?php

namespace Fomvasss\SlugMaker\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    use ScopesTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function slugable()
    {
        return $this->morphTo();
    }
}
