<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="heading_{{$id}}">
    <h4 class="panel-title clearfix">
      <a role="button" data-toggle="collapse" data-parent="#{{$parentId}}"
         href="#{{$id}}" aria-expanded="true" aria-controls="{{$id}}">
        {{$title}}
      </a>
	    {{isset($panelActions)?$panelActions:""}}
    </h4>
  </div>
  <div id="{{$id}}" class="panel-collapse collapse {{$active? 'in':''}}"
       role="tabpanel"
       aria-labelledby="heading_{{$id}}">
  <div class="panel-body">
    {{$slot}}
  </div>
</div>
</div>