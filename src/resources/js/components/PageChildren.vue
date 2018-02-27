<template>

    <div class="page-children">
        <h3 class="header">Children <a
                :href="'/admin/pages/'+page.id+'/contents/create'"
                class="pull-right btn btn-success btn-sm">Create Child</a></h3>
        <hr>

        <ul class="list-unstyled row" v-if="internalChildren.length > 0">
            <li class="clearfix">
                <div class="col-xs-1"><strong>Order</strong></div>
                <div class="col-xs-2"><strong>Uri</strong></div>
                <div class="col-xs-2"><strong>Is Active</strong></div>
                <div class="col-xs-2"><strong>Is Restricted</strong></div>
                <div class="col-xs-4"><strong>Actions</strong></div>
            </li>
            <li><hr></li>
            <li>
                <ol class="list-unstyled children" id="children-list">
                    <li class="child-item row"
                        :data-id="child.id"
                        v-for="(child, index) in internalChildren">
                        <div class="col-xs-1" v-text="index+1"></div>
                        <div class="col-xs-2" v-text="child.uri"></div>
                        <div class="col-xs-2"
                             v-text="child.is_active?'Yes':'No'"></div>
                        <div class="col-xs-2"
                             v-text="!child.is_restricted?'No':child.permission?child.permission.label:'Not Specified'"></div>
                        <div class="col-xs-4 clearfix">
                            <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary"
                                            @click.prevent="goToContentPage(child)">Content</button>
                                    <button class="btn btn-info"
                                            @click.prevent="goToEditPage(child)">Edit</button>
                                    <button class="btn btn-danger"
                                            @click.prevent="deleteChild(child)">Delete</button>
                                </div>
                        </div>
                    </li>
                    <li><button
                            class="btn btn-info btn-block"
                            @click.prevent="updateOrder">Update Order</button></li>
                </ol>
            </li>
        </ul>
  
    </div>

   
</template>

<script>
    export default {
      name   : 'page-children',
      props  : ['page', 'children'],
      data() {
        return {
          internalChildren: [],
          childrenList    : null
        }
      },
      created() {
        this.internalChildren = JSON.parse(JSON.stringify(this.children))
      },
      mounted() {

        $("#children-list").sortable()
        $("#children-list").disableSelection()
      },
      methods: {
        goToContentPage(child) {
          window.location.href = "/admin/pages/" + child.id + "/contents/"
        },
        goToEditPage(child) {
          window.location.href = "/admin/pages/" + child.id + "/edit/"
        },
        deleteChild(child) {
          if (confirm("delete page cannot be undo! Are you sure?")) {
            const uri = "/" + window.location.pathname.split('/').filter(item => item.length).join('/') + "/child/" + child.id
            axios.delete(uri)
                 .then(({data}) => this.internalChildren = _.reject(this.internalChildren, {id: data.childId}))
          }
        },
        updateOrder() {
          const list = document.getElementById("children-list")

          const items = list.querySelectorAll('li.child-item')

          const data = _.map(items, (li, index) => {
            return {
              id   : li.dataset.id,
              order: index,
            }
          })
          axios.post("/admin/pages/order", data)
               .then(response => console.log(response))
        }
      }
    }
</script>

<style scoped>
    ol.children {
        position: relative;
        padding: 25px;
        padding-top: 0;
    }

    ol li.child-item {
        padding: 5px;
        margin-bottom: 5px;
        border-color: lightgray;
        border-width: 1px;
        border-style: solid;
        border-radius: 5px;
    }
</style>

