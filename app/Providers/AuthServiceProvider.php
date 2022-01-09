<?php

namespace App\Providers;

use App\Models\Admin\Role;
use App\Models\Brands\Brand;
use App\Models\Categories\Category;
use App\Models\Categories\Section;
use App\Models\Custom_Fieldes\Custom_Field;
use App\Models\Products\Product;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\Custom_fieldPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\SectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'App\Models\Brand' => 'App\Policies\BrandPolicy',
        Brand::class            => BrandPolicy::class,
        Product::class          => ProductPolicy::class,
        Category::class         => CategoryPolicy::class,
        Custom_field::class     => Custom_fieldPolicy::class,
        Role::class             => RolePolicy::class,
        Section::class          => SectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
//        Auth::extend('jwt', function ($app, $name, array $config) {
//            // Return an instance of Illuminate\Contracts\Auth\Guard...
//            return new JwtGuard(Auth::createUserProvider($config['provider']));
//        });
        Gate::define('Read Brand',[BrandPolicy::class,'view']);
        Gate::define('Create Brand',[BrandPolicy::class,'create']);
        Gate::define('Update Brand',[BrandPolicy::class,'update']);
        Gate::define('Delete Brand',[BrandPolicy::class,'delete']);
        Gate::define('Restore Brand',[BrandPolicy::class,'restore']);

        Gate::define('Read Product',[ProductPolicy::class,'view']);
        Gate::define('Create Product',[ProductPolicy::class,'create']);
        Gate::define('Update Product',[ProductPolicy::class,'update']);
        Gate::define('Delete Product',[ProductPolicy::class,'delete']);
        Gate::define('Restore Product',[ProductPolicy::class,'restore']);

        Gate::define('Read Category',[CategoryPolicy::class,'view']);
        Gate::define('Create Category',[CategoryPolicy::class,'create']);
        Gate::define('Update Category',[CategoryPolicy::class,'update']);
        Gate::define('Delete Category',[CategoryPolicy::class,'delete']);
        Gate::define('Restore Category',[CategoryPolicy::class,'restore']);

        Gate::define('Read Custom_field',[Custom_fieldPolicy::class,'view']);
        Gate::define('Create Custom_field',[Custom_fieldPolicy::class,'create']);
        Gate::define('Update Custom_field',[Custom_fieldPolicy::class,'update']);
        Gate::define('Delete Custom_field',[Custom_fieldPolicy::class,'delete']);
        Gate::define('Restore Custom_field',[Custom_fieldPolicy::class,'restore']);

        Gate::define('Read Role',[RolePolicy::class,'view']);
        Gate::define('Create Role',[RolePolicy::class,'create']);
        Gate::define('Update Role',[RolePolicy::class,'update']);
        Gate::define('Delete Role',[RolePolicy::class,'delete']);
        Gate::define('Restore Role',[RolePolicy::class,'restore']);

        Gate::define('Read Section',[SectionPolicy::class,'view']);
        Gate::define('Create Section',[SectionPolicy::class,'create']);
        Gate::define('Update Section',[SectionPolicy::class,'update']);
        Gate::define('Delete Section',[SectionPolicy::class,'delete']);
        Gate::define('Restore Section',[SectionPolicy::class,'restore']);
   }
}
