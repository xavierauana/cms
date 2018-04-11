<template>
    <base-content-block :identifier="content.identifier" :editable="editable"
                        :deleteable="deleteable" :type="type"
                        :changed="content.changed">
        <tabs :tabs="languages">
            <input v-for="language in languages"
                   type="number"
                   @keydown.once="getDirty"
                   :ref="getInputRef(language)"
                   :data-lang_id="language.id"
                   :slot="getTabId(language)" class="form-control"
                   :disabled="!editable"
                   content>
        </tabs>
    </base-content-block>
</template>

<script>
     import Extension from "anacreation-cms-content-extension"

     export default {
       extends: Extension,
       name   : "number-content-block",
       props  : ['identifier', 'editable', 'deleteable'],
       mixins : [ContentMixin],
       created() {
         if (this.identifier) {
           this.loadContent()
         }
       },
       data() {
         return {
           type: 'number'
         }
       }
     }
</script>
