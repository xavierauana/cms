<template>
    <accordion-container>
        <accordion-item
                :item-class="{'panel-info':changed===false, 'panel-warning':changed===true}"
                :ref="contentBlockId">
            <div slot="title" class="col-xs-10">
                <div class="row">
                    <div class="col-xs-1 text-center" style="padding:0">
                        <i class="fa fa-arrows" aria-hidden="true"
                           style="position:relative; top:7px"></i>
                    </div>

                    <div class="col-xs-11">
                        <input class="form-control input-sm"
                               placeholder="Content Identifier"
                               @keydown.once="getDirty"
                               v-model="inputIdentifier"
                               :disabled="!editable" />
                    </div>
                </div>
            </div>
            <span slot="link" class="pull-right"
                  style="top:7px; position:relative" @click="open = !open"
                  v-text="open?'Hide':'Show'"></span>

            <div class="panel-body">
                <slot></slot>
            </div>
            <div class="panel-footer clearfix">
                <button @click.prevent="update"
                        class="btn btn-success"
                        v-if="editable">Update</button>
                <button @click.prevent="remove(identifier)" v-if="deleteable"
                        class="btn btn-danger pull-right">Delete</button>
            </div>
        </accordion-item>
    </accordion-container>
</template>
<script>
    import BaseMixin from "../mixins/BaseContentBlock"

    export default {
      name  : "base-content-block",
      props : ['identifier', 'editable', 'type', 'deleteable', 'changed'],
      mixins: [BaseMixin]
    }
</script>