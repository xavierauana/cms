<div class="card my-3">
    <div class="card-header align-middle" style="display: inline-block" id="heading_{{$id}}">
      <h5 class="mb-0" >
        <button class="btn btn-link" data-toggle="collapse" data-target="#{{$id}}" aria-expanded="true" aria-controls="{{$id}}">
          {{$title}}
        </button>
        {{isset($panelActions)?$panelActions:""}}
      </h5>
      
    </div>

    <div id="{{$id}}" class="collapse show" aria-labelledby="heading_{{$id}}" data-parent="#{{$parentId}}">
      <div class="card-body">
        {{$slot}}
      </div>
    </div>
  </div>