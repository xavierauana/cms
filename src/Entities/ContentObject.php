<?php
/**
 * Author: Xavier Au
 * Date: 11/2/2018
 * Time: 6:11 PM
 */

namespace Anacreation\Cms\Entities;


use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class ContentObject
{
    public $identifier;
    public $lang_id;
    public $content;
    public $content_type;
    public $file;

    /**
     * ContentObject constructor.
     * @param                                    $identifier
     * @param                                    $lang_id
     * @param                                    $content
     * @param                                    $content_type
     * @param UploadedFile|null                  $file
     */
    public function __construct(
        $identifier, $lang_id, $content, $content_type,
        UploadedFile $file = null
    ) {
        $this->identifier = $identifier;
        $this->lang_id = $lang_id;
        $this->content = $content;
        $this->content_type = $content_type;
        $this->file = $file;
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFileContent(): string {
        if($this->file) {
            return $this->file->get();
        }

        throw new InvalidArgumentException('file is null not Uploadfile');
    }
}
