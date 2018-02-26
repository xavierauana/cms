<?php

namespace Anacreation\Cms\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Role extends Model
{
    protected $fillable = [
        'label',
        'code'
    ];

    public function permissions(): Relation {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): Relation {
        return $this->belongsToMany(User::class);
    }
}
