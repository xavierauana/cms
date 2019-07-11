<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-29
 * Time: 11:53
 */

namespace Anacreation\Cms\Contracts;


use Anacreation\Cms\Entities\ContentObject;
use Illuminate\Http\Request;

interface ICreateContentObjectFromRequest
{
    public function create(Request $request): ContentObject;
}