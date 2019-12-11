<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-25
 * Time: 11:38
 */

namespace Anacreation\Cms\Services\Design;


use Anacreation\Cms\Enums\DesignType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateTemplateFile extends TemplateService
{

    public function execute(DesignType $type, string $name) {
        $path = $this->getFilePath($type,
                                   $name);
        $cap = $this->getCap($type);
        $full_path = Str::finish($path,
                                 $cap);

        $this->createFile($full_path);
    }

    private function getCap(DesignType $type): string {
        switch($type) {
            case DesignType::Definition():
                return ".xml";
            default:
                return ".blade.php";
        }
    }

    /**
     * @param string $full_path
     * @throws \Exception
     */
    private function createFile(string $full_path) {

        if(File::exists($full_path)) {
            throw new \Exception("File already exists!");
        }
        file_put_contents($full_path,
                          "");
    }
}