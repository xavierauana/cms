<template>
    <div class="progress">
      <div class="progress-bar progress-bar-striped" :class="progressBarType"
           role="progressbar" :aria-valuenow="progress" aria-valuemin="0"
           aria-valuemax="100" :style="computedWidth">
          {{progressText}}
        <span class="sr-only">{{progressText}} Complete</span>
        </div>
    </div>
</template>

<script>
export default {
  name    : "progress-bar",
  props   : ['progress', 'type'],
  computed: {
    progressBarType() {
      return this.type ? `progress-bar-${this.type}` : "progress-bar-success"
    },
    progressText() {
      return `${this.progress}%`
    },
    computedWidth() {
      return `width: ${this.progressText}`;
    }
  }
}
</script>