<template>
        <base-content-block :identifier="content.identifier"
                            :editable="editable"
                            :deleteable="deleteable"
                            :type="type"
                            :languages="languages"
                            :changed="content.changed">
            <b-tabs>
                 <b-tab v-for="language in languages"
                        :key="language.id"
                        :title="language.label">
                     <textarea :id="setInputId(language.id)"
                               :ref="getInputRef(language)"
                               :data-lang_id="language.id"
                               class="form-control"
                               @keydown.once="getDirty"
                               :placeholder="language.label + ' Content'"
                               rows="10"
                               :disabled="!editable"
                               content
                     ></textarea>
                 </b-tab>
            </b-tabs>
    </base-content-block>
</template>

<script>
    import Extension from "../packages/ContentBlockExtension"

    export default {
      extends: Extension,
      name   : "PlainTextContentBlock"
    }
</script>
