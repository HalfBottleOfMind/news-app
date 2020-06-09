<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class Role extends Model
{
    /**
     * An array of searchable attributes
     *
     * @var array
     */
    public array $searchable = [
        'name',
    ];
    
    /**
     * Array of searchable attributes that will be searched via orWhere option
     *
     * @var array
     */
    public array $checkable = [];
    
    /**
     * Array of searchable attributes that will be searched via where option
     *
     * @var array
     */
    public array $selectable = [];

    /**
     * An array of attributes that are not allowed for a mass assignment
     *
     * @var array
     */
    protected $guarded = [];

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
    ];

     /**
     * Joins all relations (Used for a query method)
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithJoins(Builder $query): Builder
    {
        return $query;
    }
 
    /**
     * Return all relations (Used for a query method)
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAll(Builder $query): Builder
    {
        return $query;
    }

    /**
     * Users that have this role
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function users(): Relation
    {
        return $this->belongsToMany(User::class);
    }
    
    /**
     * Assigned permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function permissions(): Relation
    {
        return $this->belongsToMany(Permission::class);
    }
}
