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
                       :placeholder="'Datetime for ' + language.label"
                       :disabled="!editable"
                       content />
        </tabs>
    </base-content-block>
</template>
<script>

    import Extension from "anacreation-cms-content-extension"

    export default {
      extends: Extension,
      name   : "datetime-content-block",
      mounted() {
        console.log('datetime mounted')
        this.languages.map(language => this.getInputEl(language))
            .forEach(el => $(el).datepicker())
      },
      data() {
        return {
          type: 'datetime'
        }
      }
    }
</script>
<style>
    #ui-datepicker-div {
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: gray 5px 5px 5px;
    }

    #ui-datepicker-div .ui-datepicker-header.ui-widget-header {
        display: flex;
        justify-content: space-between;
    }

    #ui-datepicker-div .ui-datepicker-header.ui-widget-header .ui-corner-all {
        padding: 5px;
    }

    #ui-datepicker-div .ui-datepicker-prev.ui-corner-all {
        order: 1;
    }

    #ui-datepicker-div .ui-datepicker-next.ui-corner-all {
        order: 3;
        text-align: right;
    }

    #ui-datepicker-div .ui-datepicker-title {
        order: 2;
        text-align: center;
        padding: 5px;
    }

    #ui-datepicker-div .ui-datepicker-calendar {
        width: 100%;
    }

    #ui-datepicker-div .ui-datepicker-calendar th,
    #ui-datepicker-div .ui-datepicker-calendar td {
        text-align: center;
        width: 30px;
        height: 30px;
        border-radius: 5px;
    }

    #ui-datepicker-div .ui-datepicker-days-cell-over.ui-datepicker-today {
        background-color: blue;
    }

    #ui-datepicker-div .ui-datepicker-days-cell-over.ui-datepicker-today a {
        color: white
    }
</style>