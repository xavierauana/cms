<?php
/**
 * Author: Xavier Au
 * Date: 2019-06-25
 * Time: 11:21
 */

namespace Anacreation\Cms\Services\Design;


use Anacreation\Cms\Enums\DesignType;

abstract class TemplateService
{

    /**
     * @param \Anacreation\Cms\Enums\DesignType $type
     * @param string                            $name
     * @return string|null
     */
    protected function getFilePath(DesignType $type, string $name) {
        $path = null;

        switch($type) {
            case DesignType::Definition():
                $path = $this->getDefinitionFilePath($name);
                break;
            case DesignType::Layout():
                $path = $this->getLayoutFilePath($name);
                break;
            case DesignType::Partial():
                $path = $this->getPartialFilePath($name);
                break;
        }

        return $path;
    }

    private function getDefinitionFilePath(string $name) {
        return getActiveThemePath()."/definition/".$name;
    }

    private function getLayoutFilePath(string $name): string {

        return getActiveThemePath()."/layouts/".$name.".blade.php";
    }

    private function getPartialFilePath(string $name) {
        return getActiveThemePath()."/partials/".$name.".blade.php";
    }

}