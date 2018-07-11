<template>
     <b-card no-body class="mb-1" :bg-variant="changed?'warning':'default'">
         <form @submit.prevent="update">
            <b-card-header header-tag="header" class="p-1" role="tab">
            <div class="row">
                <div class="col-12">
                         <b-input-group>
                             <input class="form-control input-sm"
                                    placeholder="Content Identifier"
                                    name="identifier"
                                    @keydown.once="getDirty"
                                    v-model="inputIdentifier"
                                    :disabled="!editable" />

                             <b-input-group-append>
                                  <b-btn :class="{'btn-primary':!open,'btn-success':open}"
                                         @click.prevent="open = !open"
                                         v-text="open?'Hide':'Show'"
                                  ></b-btn>
                             </b-input-group-append>

                         </b-input-group>
                    </div>
            </div>
        </b-card-header>
            <b-collapse :id="`collapse_${_uid}`" :visible="open"
                    role="tabpanel">
            <b-card-body>
              <slot></slot>
            </b-card-body>
            <b-card-footer>
                 <button type="submit"
                         class="btn btn-success"
                         v-if="editable">Update</button>
                <button @click.prevent="remove(identifier)" v-if="deleteable"
                        class="btn btn-danger pull-right">Delete</button>
            </b-card-footer>
        </b-collapse>
         </form>
     </b-card>
</template>
<script>
    import BaseMixin from "../mixins/BaseContentBlock"

    export default {
      name  : "BaseContentController",
      mixins: [BaseMixin]
    }
</script>