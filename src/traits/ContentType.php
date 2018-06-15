<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 9:32 AM
 */

namespace Anacreation\Cms\traits;


use Anacreation\Cms\Models\ContentIndex;
use Illuminate\Database\Eloquent\Relations\Relation;

trait ContentType
{
    public function index(): Relation {
        return $this->morphMany(ContentIndex::class, 'has_content');
    }

    public function showBackEnd() {
        return $this->show();
    }

    public function show(array $params = []) {
        return $this->content;
    }
}