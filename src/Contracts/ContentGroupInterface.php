<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 9:01 AM
 */

namespace Anacreation\Cms\Contracts;


use Illuminate\Database\Eloquent\Relations\Relation;

interface ContentGroupInterface
{
    public function contentIndices(): Relation;

    public function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string;
}