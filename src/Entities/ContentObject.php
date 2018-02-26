<?php
/**
 * Author: Xavier Au
 * Date: 11/2/2018
 * Time: 6:11 PM
 */

namespace Anacreation\Cms\Entities;


class ContentObject
{
    public $identifier;
    public $lang_id;
    public $content;
    public $content_type;
    public $file;

    /**
     * ContentObject constructor.
     * @param $identifier
     * @param $lang_id
     * @param $content
     * @param $content_type
     */
    public function __construct(
        $identifier, $lang_id, $content, $content_type, $file = null
    ) {
        $this->identifier = $identifier;
        $this->lang_id = $lang_id;
        $this->content = $content;
        $this->content_type = $content_type;
        $this->file = $file ?? null;
    }


}