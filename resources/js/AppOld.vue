<template>
  <router-view v-slot="{ Component }">
    <transition name="fade">
      <component :is="Component" />
    </transition>
  </router-view>
</template>

<script>
export default {
    name: 'App',
    data: () => ({
      //
    }),
    /*
    watch:{
      $route: {
        handler(to){
          if(to.meta.title){
            document.title = to.meta.title;
          }else{
            document.title = 'Webapp';
          }
        }
      }
    },
    mounted(){
      document.title = this.$route.meta.title ?? 'Webapp'; 
    }
    */
}
</script>

<style>

</style>