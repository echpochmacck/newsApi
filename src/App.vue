<script setup>
  import {RouterLink, RouterView} from 'vue-router'
  import HelloWorld from './components/HelloWorld.vue'
  import {provide, ref} from 'vue'
  // передача всем компонентам 
  const token = ref(localStorage.getItem('token') || null)
  const avatar = ref(localStorage.getItem('avatar') || null)

  provide("url", "http://newsapi/api");
  provide('token', token)
  provide('avatar', avatar);

  async function logout() {
    localStorage.clear()
    token.value = null;
    avatar.value = null
  }
</script>

<template>
  <header class="custom-header">
    <div class="container d-flex justify-content-between align-items-center">
      <nav class="nav">
        <router-link class="nav-link" to="/">Home</router-link>
        <router-link class="nav-link" to="/profile" v-if="token">Profile</router-link>
        <router-link class="nav-link" to="/logout" v-if="token" @click.prevent="logout">Logout</router-link>
      </nav>
      <div class="d-flex gap-3 align-items-center" v-if="!token">
        <router-link class="nav-link" to="/register">Register</router-link>
        <router-link class="nav-link" to="/login">Login</router-link>
      </div>

      <div class="avatar-img" v-if="token && avatar"
        :style="{ backgroundImage: `url('http://newsapi/uploads/${avatar}')` }">

      </div>
      <div class="avatar-icon" v-if="token && !avatar">
        <router-link to="/profile">

        </router-link>
      </div>

    </div>
  </header>

  <RouterView />
</template>
<style>
  .avatar-img {
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    background-position: center;
    background-size: cover;
    transition: .4s;
    background-repeat: no-repeat;

    &:hover {
      transform: scale(1.08);
    }
  }

  .custom-header {
    background-color: #0d6efd;
    /* Синий фон */
    padding: 1.5rem 1rem;
    /* Увеличенный пэддинг */
    color: white;
  }

  .custom-header .nav-link {
    padding: 5px;
    color: white;
    font-size: 1.1rem;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    border-radius: 5Fpx;
  }

  .custom-header .nav-link:hover {
    border-bottom: 2px solid #ffff;
  }



  .avatar-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #f8f9fa;
    /* Светлый фон для контраста */
    display: flex;
    justify-content: center;
    align-items: center;
    color: #0d6efd;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }
</style>