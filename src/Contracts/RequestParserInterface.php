<?php
/**
 * Author: Xavier Au
 * Date: 2019-01-05
 * Time: 10:39
 */

namespace Anacreation\Cms\Contracts;


use Illuminate\Http\Request;

interface RequestParserInterface
{
    public function parse(Request $request, array $vars = null): ?array;
}