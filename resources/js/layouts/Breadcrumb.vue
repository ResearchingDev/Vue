<!-- resources/js/layouts/Breadcrumb.vue -->
<template>
    <nav class="breadcrumb">
      <ul>
        <li><router-link to="/">Home</router-link></li>
        <li v-if="currentPage"><span>{{ currentPage }}</span></li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Breadcrumb',
    props: {
      currentPage: String,
    },
  };
  </script>
  
  <style scoped>
  .breadcrumb {
    margin: 10px 0;
    padding: 10px;
  }
  
  .breadcrumb ul {
    display: flex;
    list-style: none;
  }
  
  .breadcrumb ul li {
    margin-right: 10px;
  }
  
  .breadcrumb ul li a {
    text-decoration: none;
    color: #333;
  }
  
  .breadcrumb ul li span {
    color: #666;
  }
  </style>
  