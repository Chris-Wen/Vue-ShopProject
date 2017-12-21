<template>
  <div id="app">
    <HeaderDiv/>    
    <router-view/>
    <p>字体大小测试</p>
    <ul>
      <li>1</li>
      <li>2</li>
      <li>3</li>
    </ul>
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
  
}


</style>



