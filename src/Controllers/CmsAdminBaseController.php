<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-28
 * Time: 15:41
 */

namespace Anacreation\Cms\Controllers;


use App\Http\Controllers\Controller;
use MyCLabs\Enum\Enum;

class CmsAdminBaseController extends Controller
{

    public function authorize($ability, $arguments = []) {

        if ($ability instanceof Enum) {
            $ability = $ability->getValue();
        }

        return parent::authorize($ability, $arguments);
    }

}