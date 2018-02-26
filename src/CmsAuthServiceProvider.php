<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:39 PM
 */

namespace Anacreation\CMS;


use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Link;
use Anacreation\Cms\Models\Menu;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Models\Permission;
use Anacreation\Cms\Models\Role;
use Anacreation\Cms\Policies\ContentIndexPolicy;
use Anacreation\Cms\Policies\DesignPolicy;
use Anacreation\Cms\Policies\LanguagePolicy;
use Anacreation\Cms\Policies\LinkPolicy;
use Anacreation\Cms\Policies\MenuPolicy;
use Anacreation\Cms\Policies\PagePolicy;
use Anacreation\Cms\Policies\PermissionPolicy;
use Anacreation\Cms\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class CmsAuthServiceProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class         => RolePolicy::class,
        Link::class         => LinkPolicy::class,
        Menu::class         => MenuPolicy::class,
        Page::class         => PagePolicy::class,
        Design::class       => DesignPolicy::class,
        Language::class     => LanguagePolicy::class,
        Permission::class   => PermissionPolicy::class,
        ContentIndex::class => ContentIndexPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        //
    }
}