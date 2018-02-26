<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 9:01 AM
 */

namespace Anacreation\Cms\Contracts;


use Anacreation\CMS\Entities\ContentObject;
use Illuminate\Database\Eloquent\Relations\Relation;

interface ContentTypeInterface
{
    public function index(): Relation;

    public function showBackEnd();

    public function updateContent(ContentObject $content): ContentTypeInterface;

    public function saveContent(ContentObject $content): ContentTypeInterface;

    public function show();

    public function deleteContent(array $query = null);
}