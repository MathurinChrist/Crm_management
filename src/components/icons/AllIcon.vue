<template>
  <div class="icon">
    <DashboardIcon v-if="name === 'DashboardIcon' " :size="24" :color="color"/>
    <CrmIcon v-if="name === 'CrmIcon' " :size="24" :color="color"/>
  </div>
</template>

<script >

import DashboardIcon from 'components/icons/DashboardIcon.vue'
import CrmIcon from 'components/icons/CrmIcon.vue'

export  default {
  name: 'AllIcon',
  components: { CrmIcon, DashboardIcon },
  props: {
    name: {
      type: String,
      requited: true
    },
    color: {
      type: String,
      default: 'currentColor'
    }
  }

}

</script>

<style scoped>

</style>
