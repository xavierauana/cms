<template>
     <input type="text"
            class="form-control"
            :ref="`datetime-input-${_uid}`"
            />
</template>
<script>
    import flatpickr from 'flatpickr'

    export default {
      name: "base-datetime-input",
      mounted() {
        const refId = `datetime-input-${this._uid}`
        const el = this.$refs[refId]
        flatpickr(el, {
          enableTime: true,
          dateFormat: "Y-m-d H:i"
        })
      }
    }
</script>
<style scoped>
    .form-control[readonly] {
        background-color: white
    }
</style>