<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:39 PM.
 */

namespace Anacreation\CMS;

use Anacreation\Cms\Contracts\CmsPageInterface as PageContract;
use Anacreation\Cms\Models\CommonContent;
use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Link;
use Anacreation\Cms\Models\Menu;
use Anacreation\Cms\Models\Permission;
use Anacreation\Cms\Models\Role;
use Anacreation\Cms\Policies\AdministratorPolicy;
use Anacreation\Cms\Policies\CmsSettingsPolicy;
use Anacreation\Cms\Policies\CommonContentPolicy;
use Anacreation\Cms\Policies\ContentIndexPolicy;
use Anacreation\Cms\Policies\Definition;
use Anacreation\Cms\Policies\DesignPolicy;
use Anacreation\Cms\Policies\LanguagePolicy;
use Anacreation\Cms\Policies\MenuPolicy;
use Anacreation\Cms\Policies\PagePolicy;
use Anacreation\Cms\Policies\PartialPolicy;
use Anacreation\Cms\Policies\PermissionPolicy;
use Anacreation\Cms\Policies\RolePolicy;
use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class CmsAuthServiceProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class          => RolePolicy::class,
        Link::class          => PartialPolicy::class,
        Menu::class          => MenuPolicy::class,
        Admin::class         => AdministratorPolicy::class,
        Design::class        => DesignPolicy::class,
        Language::class      => LanguagePolicy::class,
        AdminRole::class     => AdministratorPolicy::class,
        Permission::class    => PermissionPolicy::class,
        ContentIndex::class  => ContentIndexPolicy::class,
        CommonContent::class => CommonContentPolicy::class,
        "CmsSettings"        => CmsSettingsPolicy::class,
        "Definition"         => CmsSettingsPolicy::class,
        "Layout"             => CmsSettingsPolicy::class,
        "Partial"            => PartialPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot() {
        $pageImplementation = app(PageContract::class);

        $this->policies[get_class($pageImplementation)] = PagePolicy::class;

        $this->registerPolicies();
    }
}
