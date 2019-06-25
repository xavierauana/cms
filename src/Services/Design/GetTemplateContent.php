<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-25
 * Time: 11:09
 */

namespace Anacreation\Cms\Services\Design;


use Anacreation\Cms\Enums\DesignType;

class GetTemplateContent extends TemplateService
{

    public function execute(DesignType $type, string $name): string {
        $path = $this->getFilePath($type, $name);

        return file_get_contents($path);
    }

}