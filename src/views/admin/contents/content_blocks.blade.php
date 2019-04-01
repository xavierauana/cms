<content-blocks
		:contents="{{ count($contents) > 0 ? json_encode($contents,JSON_PRETTY_PRINT) : (new stdClass()) }}"
		:editable="{{json_encode(auth()->user()->hasPermission('edit_content'),JSON_PRETTY_PRINT)}}"
		:deleteable="{{json_encode(auth()->user()->hasPermission('delete_content'),JSON_PRETTY_PRINT)}}"
		:languages="{{$languages}}"
		:can-add="{{json_encode($contentOwner->editable,JSON_PRETTY_PRINT)}}"
		:types="{{json_encode((new \Anacreation\Cms\Services\ContentService())->getTypesForJs(),JSON_PRETTY_PRINT)}}"
></content-blocks>