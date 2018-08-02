<?php
/**
 * Author: Xavier Au
 * Date: 26/7/2018
 * Time: 4:01 PM
 */

namespace Anacreation\Cms\Services;


use Illuminate\Support\Facades\File;

class TemplateParser
{

    public function loadTemplateDefinition(string $path = null, string $template
    ) {

        $path = $path ?: getActiveThemePath();

        if ($layoutDefinition = $this->fetchLayoutDefinition($path,
            $template)) {

            $filePath = $path . "/definition/" . $layoutDefinition;

            $xml = simplexml_load_file($filePath);

            return $xml;
        }

        return null;
    }

    private function fetchLayoutDefinition(string $path, string $template
    ): ?string {
        $path = $path . "/definition";
        $files = File::files($path);
        $layoutDefinition = null;
        foreach ($files as $file) {
            if ($file->getFilename() === $template . ".xml") {
                $layoutDefinition = $file->getFilename();
                break;
            }
        }

        return $layoutDefinition;
    }

    public function loadPredefinedIdentifiers($xml, bool &$editable = null
    ): array {
        $contents = [];
        foreach ($xml->content as $content) {
            if (!array_key_exists((string)$content->identifier, $contents)) {
                $contents[(string)$content->identifier] = [];
                $contents[(string)$content->identifier]['type'] = (string)$content['type'];
            }
        }

        if ($editable != null) {
            $this->setLayoutEditable($xml, $editable);
        }


        return $contents;
    }

    public function setLayoutEditable($xml, bool &$editable) {
        foreach ($xml->attributes() as $attribute => $value) {
            if ($attribute == 'editable' and $value == 'false') {
                $editable = false;
                break;
            }
        }
    }
}