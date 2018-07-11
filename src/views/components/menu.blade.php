@component('cms::elements.accordion_item',['active'=>$index===0])
	@slot('parentId')menus_accrodion @endslot
	@slot('panelActions')
		<a href="{{route('menus.links.create', $menu->id)}}"
		   class="btn btn-sm btn-success float-right mt-1"
		   style="color:white">Create Link in {{$menu->name}}</a>
	@endslot
	@slot('id'){{$menu->id}} @endslot
	@slot('title'){{$menu->name}} <small> (Code: {{$menu->code}})</small> @endslot
	<div class="sortable-list-container" id="menu_{{$menu->id}}_container">
			@include('cms::components.links',['links'=>$menu->links, 'parentId'=>0])
	</div>
	<br>
	<div class="panel-footer">
		<button onclick="updateOrder(event,'{{$menu->id}}', '{{route('menus.order.update', $menu->id)}}')"
		        class="btn btn-block btn-primary">Update Order for {{$menu->name}}</button>
	</div>
@endcomponent

@section('scripts')
	<script>
		function updateOrder(e, menuId, url) {
          e.preventDefault()
          var lists = document.getElementById("menu_" + menuId + "_container").getElementsByTagName('li')

          var data = _.map(lists, li => {
            return {
              id      : li.dataset.id,
              order   : li.dataset.order,
              parentId: li.dataset.parentid,
            }
          })

          axios.put(url, data)
               .then(response => alert('Order updated!'))
        }

        var setNewParentId = function (li) {
          var new_parent_id = li.parent('ol').attr('data-parentId')
          li.attr('data-parentId', new_parent_id)
        }

        var setNewIndex = function (li) {
          var children = li.parent('ol').children(li)
          for (var i = 0; i < children.length; i++) {
            children[i].dataset.order = i
          }
        }


        $(".sortable-list-container ol").sortable({
                                                    connectWith: ".connectedSortable",
                                                    placeholder: "ui-state-highlight",
                                                    stop       : (event, ui) => {
                                                      setNewParentId(ui.item)
                                                      setNewIndex(ui.item)
                                                    }
                                                  })
	</script>
@endsection
@section('stylesheets')
	<style>
		.ui-state-highlight {
			background-color: yellow;
		}
	</style>
@endsection