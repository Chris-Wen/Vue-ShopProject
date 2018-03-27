<template>
  <div class="banner">
      <cube-slide :data="items"/>
  </div>
</template>

<script>
export default {
    name:'Banner',
    data(){
        return {
            items: [
                { }
            ]
        }
    }
}
</script>

<style scoped>
.banner{
    width: 100%;
    min-height: 300px;
    background: #0f0;
}
</style>
