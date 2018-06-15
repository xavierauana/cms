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
    /**
     * Relation with ContentIndex::class
     * copy below relation
     * return $this->morphMany(ContentIndex::class, 'has_content');
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function index(): Relation;

    /**
     * How do display in backend cms
     * @return mixed
     */
    public function showBackEnd();

    /**
     * Update existing content
     * @param \Anacreation\CMS\Entities\ContentObject $content
     * @return \Anacreation\Cms\Contracts\ContentTypeInterface
     */
    public function updateContent(ContentObject $content): ContentTypeInterface;

    /**
     * Create new content
     * @param \Anacreation\CMS\Entities\ContentObject $content
     * @return \Anacreation\Cms\Contracts\ContentTypeInterface
     */
    public function saveContent(ContentObject $content): ContentTypeInterface;

    /**
     * Default show for frontend
     * @return mixed
     */
    public function show(array $params = []);

    /**
     * How to delete the content
     * @param array|null $query
     * @return mixed
     */
    public function deleteContent(array $query = null);
}