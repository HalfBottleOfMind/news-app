<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Permission extends Model
{
    /**
     * Turns on/off default laravel timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'laravel_through_key',
    ];

     /**
     * Assigned to roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function roles(): Relation
    {
        return $this->belongsToMany(Role::class);
    }
}
