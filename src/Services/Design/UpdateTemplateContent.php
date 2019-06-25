<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-25
 * Time: 11:19
 */

namespace Anacreation\Cms\Services\Design;


use Anacreation\Cms\Enums\DesignType;

class UpdateTemplateContent extends TemplateService
{

    public function execute(DesignType $type, string $name, string $content
    ): void {

        $path = $this->getFilePath($type, $name);

        $handle = fopen($path, 'w');

        fwrite($handle, $content);

        fclose($handle);

    }
}