<template>
  <q-drawer
    v-model="drawer"
    show-if-above
    :width="200"
    :breakpoint="500"
    :class="$q.dark.isActive ? 'bg-grey-9' : 'bg-grey-3'"
  >
    <q-scroll-area class="fit">
      <q-list>

        <template v-for="(menuItem, index) in menuList" :key="index">
          <q-item clickable :active="menuItem.label === 'Les projets'" v-ripple @click="goToMenuItem(menuItem?.path ?? 'projects')">
            <q-item-section avatar>
              <q-icon :name="menuItem.icon" />
            </q-item-section>
            <q-item-section>
              {{ menuItem.label }}
            </q-item-section>
          </q-item>
          <q-separator :key="'sep' + index"  v-if="menuItem.separator" />
        </template>

      </q-list>
    </q-scroll-area>
  </q-drawer>
</template>

<script >
import { useRoute } from 'vue-router'
export default {
  name: 'SideBar',
  props: {
  },
  setup(){
    const router = useRoute()
    return {router
    }
  },
  data(){
    return {
      drawer: false,
      menuList: [
        {
          icon: 'inbox',
          label: 'Les projets',
          path: 'projects',
          separator: true
        },
        {
          icon: 'send',
          label: 'Les tâches',
          path: 'tasks',
          separator: false
        },
        {
          icon: 'delete',
          label: 'Les utilisateurs',
          path: 'users',
          separator: false
        },
        {
          icon: 'error',
          label: 'Les groupes',
          path: 'groups',
          separator: true
        },
        {
          icon: 'settings',
          label: 'Settings',
          path: 'Settings',
          separator: false
        },
        {
          icon: 'feedback',
          label: 'Messageries',
          path: 'messages',
          separator: false
        },
        {
          icon: 'help',
          iconColor: 'primary',
          label: 'Help',
          path: 'help',
          separator: false
        }
      ]
    }
  },

  computed: {
  },
  methods: {
    goToMenuItem(path = 'mathurin') {
      this.$router.push({ name: path })
    }
  },
  mounted() {
    this.goToMenuItem('projects')
  }
}

</script>
