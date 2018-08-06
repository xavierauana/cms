<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\traits\ContentType;

class PlainTextContent extends TextContent implements ContentTypeInterface
{
    use ContentType;

    protected $table = "text_contents";

    public function show(array $params = []) {
        return nl2br($this->content);
    }

    public function showBackEnd(array $params = []) {
        return $this->content;
    }
}
