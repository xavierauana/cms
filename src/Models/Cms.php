<?php
/**
 * Author: Xavier Au
 * Date: 14/4/2018
 * Time: 6:48 PM
 */

namespace Anacreation\Cms\Models;


use Anacreation\Cms\Plugin\CmsPluginCollection;
use Anacreation\Cms\Plugin\Plugin;
use Anacreation\Cms\Services\ApiAuthentication;
use Anacreation\Cms\Services\ApiAuthStatus;
use Anacreation\Cms\Services\SitemapGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Cms
{
    public static function routes(): void {

        if (!config('cms.use_spark')) {

            Route::group([
                'namespace'  => "\\App\\Http\\Controllers",
                "middleware" => ['web']
            ], function () {
                Auth::routes();
            });
        }

        Route::group([
            "namespace"  => "\\Anacreation\\Cms\\Controllers",
            'middleware' => ['web']
        ], function () {

            Route::group(['prefix' => config('admin.route_prefix')],
                function () {
                    Route::group(['middleware' => 'auth:admin'],
                        function () {
                            Route::get('profile',
                                "HomeController@getProfile")
                                 ->name('profile');

                            Route::put('profile/{admin}',
                                "HomeController@putProfile")
                                 ->name('profile.update');

                            Route::get('designs', "DesignsController@index")
                                 ->name('designs.index');

                            Route::get('designs/upload/layout',
                                "DesignsController@uploadLayout")
                                 ->name('designs.upload.layout');
                            Route::post('designs/upload/layout',
                                "DesignsController@postUploadLayout");
                            Route::get('designs/upload/definition',
                                "DesignsController@uploadDefinition")
                                 ->name('designs.upload.definition');
                            Route::post('designs/upload/definition',
                                "DesignsController@postUploadDefinition");

                            Route::get('designs/edit/{type}',
                                "DesignsController@edit")
                                 ->name('designs.edit');
                            Route::post('designs/edit/{type}',
                                "DesignsController@store")
                                 ->name('designs.store');
                            Route::get('designs/edit/{type}/create',
                                "DesignsController@create")
                                 ->name('designs.create');

                            Route::put('designs/edit/{type}',
                                "DesignsController@update")
                                 ->name('update.design');

                            Route::put('menus/{menu}/order',
                                'MenusController@updateOrder')
                                 ->name('menus.order.update');
                            Route::resource('menus', 'MenusController');

                            Route::delete('pages/{page}/contents/child/{childId}',
                                'ContentsController@destroyChild')
                                 ->name('content.delete');
                            Route::get('pages/{page}/contents',
                                'ContentsController@index')
                                 ->name('contents.index');
                            Route::post('pages/{page}/contents/update',
                                'ContentsController@update')
                                 ->name('contents.update');
                            Route::post('pages/{page}/contents',
                                'ContentsController@store')
                                 ->name('contents.store');
                            Route::delete('pages/{page}/contents/{identifier}',
                                'ContentsController@destroy')
                                 ->name('contents.delete');

                            Route::get('pages/{page}/contents/create',
                                'ContentsController@create')
                                 ->name('contents.create');

                            Route::post('pages/order',
                                'PagesController@postOrder')
                                 ->name('pages.update.order');
                            Route::resource('pages', 'PagesController');


                            Route::delete('menus/{menu}/links/{link}/images/{langCode}',
                                'LinksController@deleteImage')
                                 ->name('menus.links.image.delete');

                            Route::resource('menus.links',
                                'LinksController');

                            Route::resource('roles', 'RolesController');
                            Route::resource('permissions',
                                'PermissionsController');
                            Route::resource('languages',
                                'LanguagesController');

                            Route::resource('settings',
                                'CmsSettingsController');

                            Route::resource('administrators',
                                "AdministratorsController");
                            Route::resource('admin_roles',
                                "AdministratorRolesController");
                            Route::resource('admin_permissions',
                                "AdministratorRolesController");

                            Route::post('logout', function () {

                                Auth::guard('admin')->logout();

                                return redirect("/");

                            })->name('admin.logout');

                        });
                });

            Route::get('_sitemap', function (SitemapGenerator $generator
            ) {

                $content = $generator->generate();

                return response($content->render(), 200,
                    ['Content-Type' => "text/xml, application/xml"]);
            });

            Route::get('setLocale/{locale}', function (string $locale) {
                session()->put('locale', $locale);

                return redirect()->back();
            })
                 ->name('set.locale');

        });

        CmsPluginCollection::RegisterRoutes();

        Route::group([
                         "namespace"  => "\\Anacreation\\Cms\\Controllers",
                         'middleware' => ['web']
                     ], function () {

            Route::get("modules", "ModulesController@resolve");
            Route::post("modules", "ModulesController@resolve");
            Route::get("{segment1?}/{segment2?}/{segment3?}/{segment4?}/{segment5?}",
                       "RoutesController@resolve");
        });
    }

    public static function apiRoutes(): void {
        Route::group([
            "namespace"  => "\\Anacreation\\Cms\\Controllers\\Api",
            'prefix'     => 'api',
            'middleware' => ['web']
        ], function () {

            Route::post('authenticate', "AuthController@authenticate");

            Route::get('user', function (Request $request) {
                switch (ApiAuthentication::isAuthenticated()) {
                    case ApiAuthStatus::AUTHENTICATED:
                        return response()->json([
                            'user'       => $request->user(),
                            'session_id' => session()->get('id')
                        ]);
                    default:
                        return response()->json('not login', 401);
                }
            });

            Route::get("{segment1?}/{segment2?}/{segment3?}/{segment4?}/{segment5?}",
                "RoutesController@resolve");

        });
    }

    public static function registerPlugin(Plugin $plugin): void {
        CmsPluginCollection::AddPlugin($plugin);
    }
}