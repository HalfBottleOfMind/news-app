<?php

declare(strict_types=1);

namespace App\Router;

use Illuminate\Support\Facades\Route;

class ApiRoute
{
    
    /**
     * Resource name
     *
     * @var string
     */
    private string $name;

    /**
     * Slug name
     *
     * @var string
     */
    private string $slug;

    /**
     * Controller full name
     *
     * @var string
     */
    private string $controller;
    
    /**
     * policy names as string
     *
     * @var string
     */
    private string $policy;
     
    /**
     * Array of routes to create
     *
     * @var array
     */
    private array $routes;
        
    /**
     * Initialize resource name
     *
     * @param  string $name
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Initialize slug name
     *
     * @param  string $slug
     * @return self
     */
    public function slug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
   
    /**
     * Initialize controller name
     *
     * @param  string $controller
     * @return self
     */
    public function controller(string $controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Initialize policy by models
     *
     * Can be multiple in case of nested routes like:
     * /v1/users/1/emails/1
     *
     * @param  string $policy
     * @return self
     */
    public function policy(string $models): self
    {
        $this->policy = $models;
        return $this;
    }

    /**
     * Api Resource
     *
     * @return self
     */
    public function resource(): self
    {
        $name = $this->name;
        $slug = $this->slug;
        $controller = "\\$this->controller";
        $policy = ",\\$this->policy";
        $this->routes[] = Route::group(['prefix' => $name], function () use ($name, $slug, $controller, $policy) {
                Route::get("/", "$controller@index")
                    ->name("$name.index")
                    ->middleware("permission:viewAny$policy");
                Route::get("/{{$slug}}", "$controller@show")
                    ->name("$name.show")
                    ->middleware("permission:view$policy")
                    ->where($slug, '[0-9]+');
                Route::post("/", "$controller@store")
                    ->name("$name.store")
                    ->middleware("permission:create$policy");
                Route::patch("/{{$slug}}", "$controller@update")
                    ->name("$name.update")
                    ->middleware("permission:update$policy")
                    ->where($slug, '[0-9]+');
                Route::put("/{{$slug}}", "$controller@updateOrCreate")
                    ->name("$name.updateOrCreate")
                    ->middleware("permission:update$policy")
                    ->middleware("permission:create$policy")
                    ->where($slug, '[0-9]+');
                Route::delete("/{{$slug}}", "$controller@destroy")
                    ->name("$name.delete")
                    ->middleware("permission:delete$policy")
                    ->where($slug, '[0-9]+');
        });
        return $this;
    }
    
    /**
     * Api Soft delete
     *
     * @return self
     */
    public function withSoftDelete(): self
    {
        $name = $this->name;
        $slug = $this->slug;
        $controller = "\\$this->controller";
        $policy = ",\\$this->policy";
        $this->routes[] = Route::group(['prefix' => $name], function () use ($name, $slug, $controller, $policy) {
                Route::put("/{{$slug}}/restore", "$controller@restore")
                    ->name("$name.restore")
                    ->middleware("permission:restore$policy")
                    ->where($slug, '[0-9]+');
                Route::delete("/{{$slug}}/soft", "$controller@softDelete")
                    ->name("$name.delete.soft")
                    ->middleware("permission:softDelete$policy")
                    ->where($slug, '[0-9]+');
        });
        return $this;
    }
    
    
    /**
     * Add additional methods
     *
     * @param  array $methods
     * @return self
     */
    public function add(array $methods): self
    {
        $name = $this->name;
        $slug = $this->slug;
        $controller = "\\$this->controller";
        $policy = "\\$this->policy";
        foreach ($methods as $method) {
            $this->routes[] =
            Route::group(['prefix' => $name], function () use ($name, $slug, $controller, $policy, $method) {
                Route::get("/{{$slug}}/$method", "$controller@$method")
                    ->name("$name.$method")
                    ->middleware("permission:view,$policy")
                    ->where($slug, '[0-9]+');
            });
        }
        return $this;
    }
    
    /**
     * Return created Routes
     *
     * @return void
     */
    public function create()
    {
        return $this->routes;
    }
}
