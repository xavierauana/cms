<template>
    <base-content-block :identifier="content.identifier" :editable="editable"
                        :deleteable="deleteable" :type="type"
                        :changed="content.changed" :languages="languages">
        <tabs :tabs="getTabIds(languages)">
            <input v-for="language in languages"
                   :slot="getTabId(language)"
                   @keydown.once="getDirty"
                   type="text"
                   class="form-control"
                   :ref="getInputRef(language)"
                   :data-lang_id="language.id"
                   :placeholder="language.label + ' Content'"
                   :disabled="!editable"
                   content />
            </tabs>
    </base-content-block>
</template>

<script>
    import Extension from "anacreation-cms-content-extension"

    export default {
      extends   : Extension,
      name      : "string-content-block",
      data() {
        return {
          type: 'string'
        }
      }
    }
</script>
