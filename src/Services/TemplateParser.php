<?php
/**
 * Author: Xavier Au
 * Date: 26/7/2018
 * Time: 4:01 PM
 */

namespace Anacreation\Cms\Services;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class TemplateParser
{

    /**
     * @param string      $template
     * @param string|null $path
     * @return \SimpleXMLElement|null
     */
    public function loadTemplateDefinition(string $template, string $path = null
    ) {

        $path = $path ?: getActiveThemePath();

        if($layoutDefinition = $this->fetchLayoutDefinition($path,
                                                            $template)) {

            $filePath = $path."/definition/".$layoutDefinition;

            try {
                return simplexml_load_file($filePath);
            } catch (\Exception $e) {
                Log::error('failed to parse definition file for '.$template);

                return null;
            }
        }

        return null;
    }

    private function fetchLayoutDefinition(string $path, string $template
    ): ?string {
        $path = $path."/definition";

        if( !File::isDirectory($path)) {
            throw new \Exception("No definition directory for theme '{$template}' ");
        }

        $files = File::files($path);
        $layoutDefinition = null;
        foreach($files as $file) {
            if($file->getFilename() === $template.".xml") {
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

        $xml = $this->loadTemplateDefinition($template,
                                             $path);

        try {
            foreach($xml->content as $content) {
                if($this->notYetParsed($content,
                                       $contents)) {
                    $this->constructContentDefinitionArray($contents,
                                                           $content);
                }
            }
        } catch (\Exception $e) {
            Log::info('Cannot load definition file for template: '.$template.' in path: '.$path);
        }

        if($editable != null) {
            $this->setLayoutEditable($xml,
                                     $editable);
        }

        return $contents;
    }

    public function setLayoutEditable($xml, bool &$editable) {
        $editable = false;
        if($xml !== null) {
            foreach($xml->attributes() as $attribute => $value) {
                if($attribute == 'editable' and $value == 'false') {
                    $editable = false;
                    break;
                }
            }
        }
    }

    public function getModelNodeByName($page, string $name): ?SimpleXMLElement {
        if($definitions = $this->loadTemplateDefinition($page->template,
                                                        "")) {
            foreach($definitions->model as $node) {
                if((string)$node->name === $name) {
                    return $node;
                }
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
        $contents[(string)$content->identifier]['helper'] = (string)$content->helper;
    }

    /**
     * @param $content
     * @param $contents
     * @return bool
     */
    private function notYetParsed($content, $contents): bool {
        return !array_key_exists((string)$content->identifier,
                                 $contents);
    }

}