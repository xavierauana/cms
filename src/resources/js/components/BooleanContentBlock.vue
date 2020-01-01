<template>
    <base-content-block :identifier="content.identifier"
                        :editable="editable"
                        :deleteable="deleteable"
                        :type="type"
                        :changed="content.changed"
                        :languages="languages">

         <b-tabs>
            <b-tab v-for="language in languages"
                   :key="language.id"
                   :title="language.label">
                <select @change.once="getDirty"
                        class="form-control"
                        :ref="getInputRef(language)"
                        :data-lang_id="language.id"
                        :slot="getTabId(language)"
                        :disabled="!editable"
                        content>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            </b-tab>
         </b-tabs>
    </base-content-block>
</template>

<script>
     import Extension from "../packages"

     export default {
       extends: Extension,
       name   : "boolean-content-block",
       methods: {
         setValue(data) {
           _.forEach(data, item => {
             const el = this.$refs[this.getInputRef({id: item.lang_id})][0]
             el.value = item.content.content
           })
         },
       }
     }
</script>
