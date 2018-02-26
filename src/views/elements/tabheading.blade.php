<li role="presentation" class="@if($isActive) active @endif"><a
			href="#{{$tabPanelId}}"
			aria-controls="{{$tabPanelId}}"
			role="tab"
			data-toggle="tab">{{$slot}}</a></li>