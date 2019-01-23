<div class="container">
	
	@include("cms::components.alert")
	
	<div class="my-3 card">
		<div class="card-header">
	    {{$title}}
	  </div>
		<div class="card-body">
			{{$slot}}
		</div>
	</div>
</div>
