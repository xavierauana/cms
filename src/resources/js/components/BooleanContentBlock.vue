<template>
    <wrapper :identifier="content.identifier" :editable="editable"
             :deleteable="deleteable" :type="type"
             :changed="content.changed" :languages="languages">
        <tabs :tabs="getTabIds(languages)">
            <select v-for="language in languages"
                    @change.once="getDirty"
                    class="form-control"
                    :ref="getInputRef(language)"
                    :data-lang_id="language.id"
                    :slot="getTabId(language)"
                    :disabled="!editable"
                    content>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </tabs>
    </wrapper>
</template>

<script>
     import Wrapper from "./BaseContentBlock"
     import ContentMixin from "../mixins/ContentBlock"

     export default {
       name      : "boolean-content-block",
       components: {
         Wrapper
       },
       mixins    : [ContentMixin],
       data() {
         return {
           type: 'boolean'
         }
       },
       methods   : {
         setValue(data) {
           _.forEach(data, item => {
             const el = this.$refs[this.getInputRef({id: item.lang_id})][0]
             el.value = item.content.content
           })
         },
       }
     }
</script>
