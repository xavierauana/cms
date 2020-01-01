<?php
/**
 * Author: Xavier Au
 * Date: 30/3/2018
 * Time: 9:34 AM.
 */

namespace Anacreation\Cms\Contracts;

use Anacreation\Cms\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

interface CmsPageInterface
{
    public static function ActivePages(): array;

    public function getActivePages(): array;

    public function getAllPages(): array;

    public function injectLayoutModels(string $path = null, string $template
    ): array;

    public function loadContents(string $directoryPath, string $template);

    public function children(): Relation;

    public function contentIndices(): Relation;

    public function scopeActive(Builder $query): Builder;

    public function create(array $attributes = []);

    public function getTemplate(): ?string;

    public function getPermission(): ?Permission;

    public function isRestricted(): bool;

    public function getDefinitionNodeByModelName(string $name);

    public function callModel(string $name, string $method = null, array $arguments = []);
}
