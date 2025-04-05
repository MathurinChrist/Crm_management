<template>
  <div class="container login-content">
    <div class="society-information">
      <AllIcon name="CrmIcon" :size="200" color="white"/>
      <h1> Gerer vos projets et ressource en toute sécurité</h1>
    </div>
    <div class="divider"></div>
    <div class="login-form q-pa-lg">
      <q-form @submit.prevent="handleLogin" class="full-width">
        <div class="icons">
        </div>
        <q-input v-model="email" label="Adresse Email" type="email" outlined dense class="q-mb-md" :rules="[val => !!val || 'Champ requis']"/>
        <q-input v-model="password" label="Mot de passe" type="password" outlined dense class="q-mb-lg" :rules="[val => !!val || 'Champ requis']"/>

        <q-btn label="Connexion" type="submit" color="primary" class="full-width" />
      </q-form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

import AllIcon from "components/icons/AllIcon.vue";

 const email = ref('')
const password = ref('')

const handleLogin = () => {
  if (!email.value || !password.value) return
  console.log('here')
  }
</script>

<style scoped>
.login-content {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-around;
  width: 100vw;
  height: 100vh;
  background-image: url('../assets/background_image.jpg');
  background-size: cover;
  background-position: center;
}

.society-information {
  display: flex;
  align-items: center;

    h1 {
      font-size: 2.5rem;
      color: white;
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
      font-weight: bold;
    }

  span {
    color: white;
    font-size: 16px;
  }
}

.divider {
  width: 2px;
  height: 60%;
  background-color: #ccc;
}

.login-form {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: whitesmoke;
  border-radius: 16px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  width: 450px;
}
</style>
