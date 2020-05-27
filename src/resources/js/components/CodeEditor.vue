<template>
        <div>
            <div ref="editor" class="code-editor"></div>

            <div class="card-footer">
            <button class="btn btn-primary btn-block"
                    @click.prevent="showContent">Update</button>
            </div>

        </div>
</template>

<script>
    export default {
        name : "code-editor",
        props: {
            type       : {
                type    : String,
                required: true,
            },
            id         : {
                type: String
            },
            csrfToken  : {
                type   : String,
                default: null
            },
            content_uri: {
                type   : String,
                default: null
            },
        },
        data() {
            return {
                editor : null,
                _id    : null,
                content: null
            }
        },
        created() {
            this._id = this.id ? this.id : 'code_editor_' + this._uid

        },
        mounted() {
            Vue.nextTick(() => {
                //this.editor = ace.edit(this._id)
                this.editor = CodeMirror(this.$refs.editor, {
                    lineNumbers: true,
                    mode       : this.type === 'definition' ? 'text/html' : 'application/x-httpd-php',
                    theme      : 'dracula',
                    foldGutter : true,
                    gutters    : ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
                })
                this.getContent();
            })
        },


        methods: {
            getContent() {
                if (this.content_uri) {
                    axios.get(this.content_uri)
                         .then(({data}) => {
                             Vue.nextTick(() => {
                                 this.editor.setValue(data)
                             })
                         })
                }
            },
            showContent() {
                if (this.editor) {
                    let el   = document.getElementById("code"),
                        form = document.getElementById("edit-form")

                    el.value = this.editor.getValue()

                    form.submit();


                    //axios.put(this.content_uri, data)
                    //     .then(res => window.location.href = "/admin/designs")
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
