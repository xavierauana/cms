<?php
/**
 * Author: Xavier Au
 * Date: 2019-01-05
 * Time: 14:17
 */

namespace Anacreation\Cms\Exceptions;


use Illuminate\Database\Eloquent\ModelNotFoundException;

class LanguageNotFoundException extends ModelNotFoundException
{
    public function render() {
        if (request()->segments()[0] === 'api' or request()->ajax()) {
            return response()->json("Language not found!", 404);
        }
    }
}