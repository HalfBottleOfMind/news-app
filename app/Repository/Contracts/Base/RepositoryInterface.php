<?php

declare(strict_types=1);

namespace App\Repository\Contracts\Base;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get a collection of filtered Model instances
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function query(Request $request): Collection;

    /**
     * Create Model
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Request $request): Model;

    /**
     * Update Model
     *
     * @param int   $id
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function update(int $id, Request $request): void;

    /**
     * Update or create a Model with the given ID
     *
     * @param int   $id
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateOrCreate(int $id, Request $request): void;
 
    /**
     * Find Model by ID
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): ?Model;
    
    /**
     * Delete Model by ID
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
