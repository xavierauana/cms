<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\CMS\Entities\ContentObject;
use Anacreation\Cms\traits\ContentType;
use Illuminate\Database\Eloquent\Model;

class NumberContent extends Model implements ContentTypeInterface
{
    use ContentType;

    public function updateContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = $contentObject->content;
        $this->save();

        return $this;
    }

    public function saveContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = $contentObject->content;
        $this->save();

        return $this;
    }

    public function deleteContent(array $query = null) {
        $this->content = "";
        $this->save();
    }
}
