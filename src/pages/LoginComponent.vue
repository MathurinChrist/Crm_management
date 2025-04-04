<template>
  <div class="container login-content">
    <div class="society-information">
      <h1> Crm management</h1>
    </div>
    <div class="divider"></div>
    <div class="login-form">
      <q-form>
        <q-input outlined square v-model="text" hint="With perfect square borders" :dense="dense"></q-input>

        <q-input outlined v-model="text" :dense="dense">
          <template v-slot:prepend>
            <q-icon name="event"></q-icon>
          </template>
        </q-input>
      </q-form>
    </div>
  </div>

</template>


<script setup>
import {ref} from 'vue'
  const text = ref('')
</script>

<style scoped>
  .login-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 40px;
    height: 100vh;
    background-image: url('../assets/background_image.jpg');
    background-size: cover;
    background-position: center;

    .divider {
      width: 2px;
      height: 60%;
      background-color: #ccc;
    }
    .login-form {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      width: 35%;
      height: 50%;
      background-color: whitesmoke;
    }
  }

</style>