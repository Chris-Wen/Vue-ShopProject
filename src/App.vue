<template>
  <div id="app">
    <HeaderDiv/>    
    <router-view/>
    <p>主页 app.vue 字体  字体大小测试</p>
  </div>
</template>

<script>
import HeaderDiv from '@/components/Header'

export default {
  name: 'app',
  components:{
    HeaderDiv
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: black;
  margin-top: 100px;
  width: 100%;
  min-height: 100%;
}


</style>



