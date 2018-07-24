<template>
    <div class="container-fluid">
        <div class="row">
            <div :id="_id" class="code-editor"></div>
        </div>
        <br>
        <div class="row">
            <button class="btn btn-default btn-block"
                    @click.prevent="showContent">Click</button>
        </div>
    </div>
</template>

<script>
    export default {
      name   : "code-editor",
      props  : {
        id         : {
          type: String
        },
        content_uri: {
          type: String
        },
      },
      data() {
        return {
          editor: null,
          _id   : null
        }
      },
      created() {
        this._id = this.id ? this.id : 'code_editor_' + this._uid
      },
      mounted() {
        Vue.nextTick(() => {
          this.editor = ace.edit(this._id)
          this.editor.setTheme("ace/theme/twilight");
          this.editor.session.setMode("ace/mode/php");
          this.getContent();
        })
      },
      methods: {
        getContent() {
          axios.get(this.content_uri)
               .then(({data}) => this.editor.setValue(data))
        },
        showContent() {
          if (this.editor) {
            const data = {code: this.editor.getValue()}

            axios.put(this.content_uri, data)
                 .then(res => window.location.href = "/admin/designs")
          }
        }
      }
    }
</script>

<style scoped>
.code-editor {
    width: 100%;
    min-height: 300px;
    max-height: 100vh;
}
</style>