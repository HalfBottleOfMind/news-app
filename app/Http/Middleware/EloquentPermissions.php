<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Repository\Contracts\UserRepositoryContract;

class EloquentPermissions
{
    /**
     * Handle an incoming request and make authorization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function handle($request, Closure $next, $ability, ...$models)
    {
        if ($request->route()->parameters()) {
            Gate::authorize($ability, ...$this->getModelsInstances($request, $models));
        } else {
            Gate::authorize($ability, resolve($models[0]));
        }
        return $next($request);
    }
    
    /**
     * Convert data to array of real Models
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $models
     * @return array
     */
    private function getModelsInstances(Request $request, array $models = []): array
    {
        $data = [];
        $instances = [];
        foreach ($request->route()->parameters() as $model => $id) {
            $data[] = [
                'id' => $id,
                'model' => array_shift($models)
            ];
        }
        foreach ($data as $value) {
            $instance = trim($value['model']);
            if (
                in_array(
                    'Illuminate\Database\Eloquent\SoftDeletes',
                    class_uses($instance)
                ) && $request->isMethod('put')
            ) {
                $instance = $instance::withTrashed()->find($value['id']);
            } else {
                $instance = $instance::find($value['id']);
            }
            if ($instance) {
                $instances[] = $instance;
            } else {
                abort(404, trans('validation.not_found'));
            }
        }
        return $instances;
    }
}
