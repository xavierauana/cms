<?php

namespace Anacreation\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Permission extends Model
{
    protected $fillable = [
        'label',
        'code'
    ];

    public function roles(): Relation {
        return $this->belongsToMany(Role::class);
    }
}
