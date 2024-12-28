<!-- resources/js/layouts/Header.vue -->
<template>
    <header class="app-header">
      <div class="logo">
        <!-- <img src="@/assets/logo.png" alt="Logo" /> -->
      </div>
      <nav>
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li><router-link to="/about">About</router-link></li>
          <li><router-link to="/contact">Contact</router-link></li>
        </ul>
      </nav>
      <div class="user-profile">
        <span>Welcome, User</span>
      </div>
    </header>
  </template>
  
  <script>
  export default {
    name: 'Header',
  };
  </script>
  
  <style scoped>
  .app-header {
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    background-color: #333;
    color: white;
  }
  
  nav ul {
    display: flex;
    list-style: none;
  }
  
  nav ul li {
    margin-right: 20px;
  }
  
  nav ul li a {
    color: white;
    text-decoration: none;
  }
  </style>
  