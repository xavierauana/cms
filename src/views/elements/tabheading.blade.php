{{--<li role="presentation" class="@if($isActive) active @endif"><a--}}
{{--href="#{{$tabPanelId}}"--}}
{{--aria-controls="{{$tabPanelId}}"--}}
{{--role="tab"--}}
{{--data-toggle="tab">{{$slot}}</a></li>--}}


<li class="nav-item">
    <a class="nav-link @if($isActive) active @endif"
       id="contact-tab"
       data-toggle="tab"
       href="#{{$tabPanelId}}"
       role="tab"
       aria-controls="{{$tabPanelId}}"
       aria-selected="false">{{$slot}}</a>
  </li>