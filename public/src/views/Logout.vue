<template>
  <div>

  </div>
</template>

<script>
export default {
  //  UITLOGGEN
  methods: {
    logout(){
      return [
        this.$store.dispatch('destroyToken')]
    }
  },
  beforeMount() {
    this.logout();
  }
}
</script>

<style scoped>

</style>
