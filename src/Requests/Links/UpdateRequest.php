<?php
/**
 * Author: Xavier Au
 * Date: 11/7/2018
 * Time: 11:57 AM
 */

namespace Anacreation\Cms\Requests\Links;


use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateRequest extends FormRequest
{

    private $menu = null;
    private $link = null;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {

        list($menu, $link) = $this->getMenuAndLink();

        return $this->user()->can('update', $link);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        list($menu, $link) = $this->getMenuAndLink();

        $this->sanitizeInputs();
        $parentIds = implode(",", $menu->links()->pluck('id')->toArray());
        $pageIds = implode(',', Page::pluck('id')->toArray()).",0";

        return [
            'name.*.lang_id'  => 'required|exists:languages,id',
            'name.*.content'  => 'required',
            'is_active'       => 'required|boolean',
            'parent_id'       => 'required|in:0,' . $parentIds,
            'page_id'         => 'required_without:external_uri|in:' . $pageIds,
            'external_uri'    => 'required_without:page_id',
            'files'           => "nullable",
            'files.*.lang_id' => 'sometimes|exists:languages,id',
            'files.*.file'    => 'sometimes',
        ];
    }

    private function sanitizeInputs() {

        $inputs = $this->all();

        if (isset($inputs['external_uri']) and !$inputs['external_uri']) {
            unset($inputs['external_uri']);
        }

        if (isset($inputs['page_id']) and (!$inputs['page_id'] or $inputs['page_id'] === '0')) {
            unset($inputs['page_id']);
        }

        $this->replace($inputs);
    }

    /**
     * @return array
     */
    private function getMenuAndLink(): array {

        $menu = $this->route('menu');
        $link = $this->route('link');


        if ($link === null or $menu === null) {
            throw new NotFoundHttpException();
        }

        return [$menu, $link];
    }
}