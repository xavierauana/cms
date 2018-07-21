{{--<div role="tabpanel" class="tab-pane @if($isActive)active @endif"--}}
     {{--id="{{$tabPanelId}}">--}}
	{{----}}
	{{--{{$slot}}--}}

{{--</div>--}}

<div class="tab-pane fade @if($isActive) show active @endif" id="{{$tabPanelId}}" role="tabpanel" aria-labelledby="profile-tab">
	{{$slot}}
</div>
