<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 1:54 PM
 */

if (!config('cms.use_spark')) {
    Route::group([
        'namespace'  => "App\\Http\\Controllers",
        "middleware" => ['web']
    ], function () {
        Auth::routes();
    });
}

@include "../../vendor/xavierau/multi-auth/src/Routes/routes.php";


Route::group([
    "namespace"  => "Anacreation\\Cms\\Controllers",
    'middleware' => [
        'web'
    ]
],
    function () {

        Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'],
            function () {

                Route::get('profile', "HomeController@getProfile")
                     ->name('profile');
                Route::put('profile/{admin}', "HomeController@putProfile")
                     ->name('update.profile');

                Route::get('designs', "DesignsController@index");
                Route::get('designs/edit/{type}', "DesignsController@edit");
                Route::put('designs/edit/{type}',
                    "DesignsController@update");
                Route::delete('pages/{page}/contents/child/{childId}',
                    'ContentsController@destroyChild');
                Route::put('menus/{menu}/order',
                    'MenusController@updateOrder');
                Route::resource('menus', 'MenusController');
                Route::post('pages/order', 'PagesController@postOrder');
                Route::resource('pages', 'PagesController');
                Route::get('pages/{page}/contents',
                    'ContentsController@index');
                Route::post('pages/{page}/contents/update',
                    'ContentsController@update');
                Route::post('pages/{page}/contents',
                    'ContentsController@store');
                Route::delete('pages/{page}/contents/{identifier}',
                    'ContentsController@destroy');

                Route::get('pages/{page}/contents/create',
                    'ContentsController@create');

                Route::resource('menus.links', 'LinksController');

                Route::resource('roles', 'RolesController');
                Route::resource('permissions', 'PermissionsController');
                Route::resource('languages', 'LanguagesController');
            });


        Route::get('setLocale/{locale}', function (string $locale) {
            session()->put('locale', $locale);

            return redirect()->back();
        });


        Route::get("{segment1?}/{segment2?}/{segment3?}/{segment4?}/{segment5?}",
            "RoutesController@resolve");

    });