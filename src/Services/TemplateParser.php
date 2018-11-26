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

    public function loadPredefinedIdentifiers(
        string $path, string $template, bool &$editable = null
    ): array {
        $contents = [];

        $xml = $this->loadTemplateDefinition($path, $template);

        foreach ($xml->content as $content) {
            if ($this->notYetParsed($content, $contents)) {
                $this->constructContentDefinitionArray($contents, $content);
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

    /**
     * @param $contents
     * @param $content
     * @return mixed
     */
    private function constructContentDefinitionArray(array &$contents, $content
    ) {
        $contents[(string)$content->identifier] = [];
        $contents[(string)$content->identifier]['type'] = (string)$content['type'];
    }

    /**
     * @param $content
     * @param $contents
     * @return bool
     */
    private function notYetParsed($content, $contents): bool {
        return !array_key_exists((string)$content->identifier, $contents);
    }
}