<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\CMS\Entities\ContentObject;
use Anacreation\Cms\traits\ContentType;
use Illuminate\Database\Eloquent\Model;

class BooleanContent extends Model implements ContentTypeInterface
{
    use ContentType;

    public function updateContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = !!$contentObject->content;
        $this->save();

        return $this;
    }

    public function saveContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = !!$contentObject->content;
        $this->save();

        return $this;
    }

    public function show(array $params = []) {
        return !!$this->content;
    }

    public function deleteContent(array $query = null) {
        $this->content = false;
        $this->save();
    }
}
