<template>
  <q-header elevated class="bg-primary">
    <q-toolbar>
      <div class="icon">
        <AllIcon name="CrmIcon" :size="80" color="white"/>
      </div>
      <q-toolbar-title>Header</q-toolbar-title>
    </q-toolbar>
  </q-header>
</template>

<script >

import AllIcon from "components/icons/AllIcon.vue";

export default {
  name: 'HeaderPage',
  components: {AllIcon},
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
