<template>
  <div class="q-pa-md">
    <q-layout view="hHh Lpr lff" container  class="full-page shadow-2">
      <HeaderPage/>

    <SideBar :drawer="drawer" />
    <q-page-container>
      <q-page padding>
        <router-view />
      </q-page>
    </q-page-container>
    </q-layout>
  </div>
</template>

<script>
  import SideBar from 'components/SideBar.vue'
  import HeaderPage from 'components/HeaderPage.vue'

  export  default {
    name: 'MainLayout',
    components: { SideBar, HeaderPage },
    data(){
      return {
        drawer: false
      }
    }
  }
</script>
<style>
.full-page {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
