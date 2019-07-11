<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-29
 * Time: 11:50
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Contracts\ICreateContentObjectFromRequest;
use Anacreation\Cms\Entities\ContentObject;
use Illuminate\Http\Request;

class CreateContentObjectFromRequest implements ICreateContentObjectFromRequest
{
    public function create(Request $request): ContentObject {
        return new ContentObject(
            $request->get('identifier'),
            $request->get('lang_id'),
            $request->get('content'),
            $request->get('content_type'),
            $request->file('content'));
    }
}