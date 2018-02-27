<template>
    <wrapper :identifier="content.identifier" :editable="editable"
             :deleteable="deleteable" :type="type"
             :changed="content.changed" :languages="languages">
        <tabs :tabs="getTabIds(languages)">
            <input v-for="language in languages"
                   @keydown.once="getDirty"
                   type="text"
                   class="form-control"
                   :ref="getInputRef(language)"
                   :data-lang_id="language.id"
                   :slot="getTabId(language)"
                   :placeholder="language.label + ' Content'"
                   :disabled="!editable"
                   content />
            </tabs>
    </wrapper>
</template>

<script>
     import Wrapper from "./BaseContentBlock"
     import ContentMixin from "../mixins/ContentBlock"

     export default {
       name      : "string-content-block",
       components: {
         Wrapper
       },
       mixins    : [ContentMixin],
       data() {
         return {
           type: 'string'
         }
       }
     }
</script>
