<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    @foreach($tabs as $index=>$tab)
		  @component('cms::elements.tabheading')
			  {{$heading}}
		  @endcomponent
	  @endforeach
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    @foreach($tabs as $index=>$tab)
		  @component('cms::elements.tabpanel')
			  {{$slot}}
		  @endcomponent
	  @endforeach
  </div>