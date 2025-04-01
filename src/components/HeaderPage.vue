<template>
  <q-header elevated class="bg-primary">
    <q-toolbar>
      <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
      <q-toolbar-title>Header</q-toolbar-title>
    </q-toolbar>
  </q-header>
</template>

<script >

export default {
  name: 'HeaderPage',
  props: {
  },
  data(){
    return {
      drawer: false,
    }
  },

  computed: {
  }
}

</script>
