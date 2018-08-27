<?php
/**
 * Author: Xavier Au
 * Date: 30/3/2018
 * Time: 9:34 AM.
 */

namespace Anacreation\Cms\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

interface CmsPageInterface
{
    public function getActivePages(): Collection;

    public function injectLayoutModels(string $path = null, string $template
    ): array;

    public function loadContents(string $directoryPath, string $template);

    public function children(): Relation;

    public function contentIndices(): Relation;
}
