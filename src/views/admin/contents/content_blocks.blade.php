<content-blocks
		:contents="{{ count($contents) > 0 ? json_encode($contents,JSON_PRETTY_PRINT) : (new stdClass()) }}"
		:editable="{{json_encode(auth()->user()->hasPermission('edit_content'))}}"
		:deleteable="{{json_encode(auth()->user()->hasPermission('delete_content'))}}"
		:languages="{{$languages}}"
		:can-add="{{json_encode($contentOwner->editable)}}"
		:types="{{json_encode((new \Anacreation\Cms\Services\ContentService())->getTypesForJs())}}"
></content-blocks>