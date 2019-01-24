<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\CMS\Entities\ContentObject;
use Anacreation\Cms\traits\ContentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    public function show(array $params = []) {
        return $this->link ? url($this->link) : null;
    }

    public function showBackEnd() {
        return $this->link;
    }

    public function deleteContent(array $query = null) {
        $path = $this->link = public_path(substr($this->link, 1));
        File::delete($path);
        $this->link = "";
        $this->save();
    }

    private function isUploadFile($contentObject) {
        return $contentObject->file and get_class($contentObject->file) === UploadedFile::class;
    }

    /**
     * @param \Anacreation\CMS\Entities\ContentObject $contentObject
     */
    private function MoveFile(ContentObject $contentObject): void {
        $path = Storage::putFileAs('public/files',
            $contentObject->file,
            $contentObject->file->getClientOriginalName());

        $path = str_replace("public", "storage", $path);

        $this->link = $path;
    }
}
