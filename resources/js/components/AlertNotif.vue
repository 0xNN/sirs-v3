<template>
  <div class="alert alert-warning">{{ message }}</div>
</template>

<script>
  export default {
    mounted() {
      console.log('Alert mounted.')
    },
    props: [
      'message'
    ]
  }
</script>