<!-- resources/js/pages/Home.vue -->
<template>
    <AppLayout>
      <template v-slot:default>
        <h1>Welcome to the Dashboard!</h1>
        <p>This is the homepage content.</p>
      </template>
    </AppLayout>
  </template>
  
  <script>
  import AppLayout from '@/layouts/AppLayout.vue';
  
  export default {
    name: 'HomePage',
    components: {
      AppLayout,
    },
  };
  </script>
  