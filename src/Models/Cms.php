<?php
/**
 * Author: Xavier Au
 * Date: 14/4/2018
 * Time: 6:48 PM
 */

namespace Anacreation\Cms\Models;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Cms
{
    public static function routes(): void {

        if (!config('cms.use_spark')) {
            Route::group([
                'namespace'  => "\App\\Http\\Controllers",
                "middleware" => ['web']
            ], function () {
                Auth::routes();
            });
        }

        Route::group([
            "namespace"  => "\Anacreation\\Cms\\Controllers",
            'middleware' => [
                'web'
            ]
        ],
            function () {

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

                                Route::get('designs/edit/{type}',
                                    "DesignsController@edit")
                                     ->name('designs.edit');

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


                                Route::resource('menus.links',
                                    'LinksController');
                                Route::resource('roles', 'RolesController');
                                Route::resource('permissions',
                                    'PermissionsController');
                                Route::resource('languages',
                                    'LanguagesController');
                            });
                    });


                Route::get('setLocale/{locale}', function (string $locale) {
                    session()->put('locale', $locale);

                    return redirect()->back();
                })->name('set.locale');


                Route::get("{segment1?}/{segment2?}/{segment3?}/{segment4?}/{segment5?}",
                    "RoutesController@resolve");

            });
    }
}