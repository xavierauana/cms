<template>

    <div class="card page-children">
        <h3 class="card-header">Children <a
                :href="createUrl"
                class="pull-right btn btn-success btn-sm">Create Child</a></h3>
        <div class="card-body">

            <ul class="list-unstyled" v-if="internalChildren.length > 0">
                <li class="clearfix row">
                    <span class="col-1 inline"><strong>Order</strong></span>
                    <span class="col-2 inline"><strong>Uri</strong></span>
                    <span class="col-2 inline"><strong>Is Active</strong></span>
                    <span class="col-2 inline"><strong>Is Restricted</strong></span>
                    <span class="col-4 inline"><strong>Actions</strong></span>
                </li>
                <li><hr></li>
                <li>
                    <ol class="list-unstyled children px-1" id="children-list">
                        <li class="child-item row"
                            :data-id="child.id"
                            v-for="(child, index) in internalChildren">
                            <div class="col-1" v-text="index+1"></div>
                            <div class="col-2" v-text="child.uri"></div>
                            <div class="col-2"
                                 v-text="child.is_active?'Yes':'No'"></div>
                            <div class="col-2"
                                 v-text="!child.is_restricted?'No':child.permission?child.permission.label:'Not Specified'"></div>
                            <div class="col-4 clearfix">
                                <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary"
                                                v-if="canEdit"
                                                @click.prevent="goToContentPage(child)">Content</button>
                                        <button class="btn btn-info"
                                                v-if="canEdit"
                                                @click.prevent="goToEditPage(child)">Edit</button>
                                        <button class="btn btn-danger"
                                                v-if="canDelete"
                                                @click.prevent="deleteChild(child)">Delete</button>
                                    </div>
                            </div>
                        </li>
                        <li class=""><button
                                class="btn btn-info btn-block py-1"
                                @click.prevent="updateOrder">Update Order</button></li>
                    </ol>
                </li>
            </ul>

        </div>

    </div>

   
</template>

<script>
    export default {
      name    : 'page-children',
      props   : {
        page     : {
          type    : Object,
          required: true
        },
        children : {
          type: Object,
        },
        baseUrl  : {
          type    : String,
          required: true,
        },
        canEdit  : {
          type   : Boolean,
          default: true,
        },
        canDelete: {
          type   : String,
          default: false,
        },
      },
      data() {
        return {
          internalChildren: [],
          childrenList    : null
        }
      },
      computed: {
        createUrl() {
          return this.baseUrl + "/" + this.page.id + "/contents/create"
        }
      },
      created() {
        this.internalChildren = JSON.parse(JSON.stringify(this.children))
      },
      mounted() {
        $("#children-list").sortable()
        $("#children-list").disableSelection()
      },
      methods : {
        goToContentPage(child) {
          window.location.href = this.baseUrl + "/" + child.id + "/contents"
        },
        goToEditPage(child) {
          window.location.href = this.baseUrl + "/" + child.id + "/edit/"
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

