@if(session()->has('status'))
	<div class=" alert alert-primary alert-dismissible fade show mt-3 "
	     role="alert">
              {{session()->get('status')}}
		<button type="button" class="close" data-dismiss="alert"
		        aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
    </div>
@endif