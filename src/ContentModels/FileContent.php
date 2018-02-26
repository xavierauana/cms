<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\CMS\Entities\ContentObject;
use Anacreation\Cms\traits\ContentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileContent extends Model implements ContentTypeInterface
{
    use ContentType;

    public function updateContent(ContentObject $contentObject
    ): ContentTypeInterface {
        if ($this->isUploadFile($contentObject)) {
            $this->MoveFile($contentObject);
            $this->save();
        }

        return $this;
    }

    public function saveContent(ContentObject $contentObject
    ): ContentTypeInterface {
        if ($this->isUploadFile($contentObject)) {
            $this->MoveFile($contentObject);
        } else {
            $this->link = "";
        }

        $this->save();

        return $this;
    }

    public function show() {
        return $this->link;
    }

    public function deleteContent(array $query = null) {
        $path = $this->link = public_path(substr($this->link, 1));
        File::delete($path);
        $this->link = "";
        $this->save();
    }

    private function isUploadFile($contentObject) {
        return get_class($contentObject->content) === UploadedFile::class;
    }

    /**
     * @param \Anacreation\CMS\Entities\ContentObject $contentObject
     */
    private function MoveFile(ContentObject $contentObject): void {
        $contentObject->content->move("files",
            $contentObject->content->getClientOriginalName());
        $this->link = "/files/" . $contentObject->content->getClientOriginalName();
    }
}
