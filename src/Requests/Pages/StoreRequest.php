<?php
/**
 * Author: Xavier Au
 * Date: 11/7/2018
 * Time: 11:57 AM
 */

namespace Anacreation\Cms\Requests\Pages;


use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{

    private $menu = null;
    private $link = null;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->user()->can('create', (new Page));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $layouts = getLayoutFiles()['layouts'];

        return [
            'uri'           => [
                'required',
                'not_in:api,modules',
                Rule::unique('pages')
                    ->where(function ($query) {
                        return $query->where('parent_id', 0);
                    })
            ],
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'in_sitemap'    => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'order'         => 'nullable|numeric|min:0',
            'permission_id' => 'required|in:0,' . implode(',',
                    Permission::pluck('id')->toArray()),
        ];
    }
}