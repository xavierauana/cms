<?php

namespace Anacreation\Cms\Api\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ApiChildrenResponseResource extends Resource
{
    private $level;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return (new ApiResponseResource($this))->toArray($request);
    }

}
