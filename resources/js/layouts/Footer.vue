<!-- resources/js/layouts/Footer.vue -->
<template>
    <footer class="app-footer">
      <div class="footer-content">
        <p>&copy; 2024 Company Name. All rights reserved.</p>
        <div class="social-links">
          <a href="https://facebook.com" target="_blank">Facebook</a>
          <a href="https://twitter.com" target="_blank">Twitter</a>
          <a href="https://linkedin.com" target="_blank">LinkedIn</a>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer',
  };
  </script>
  
  <style scoped>
  .app-footer {
    background-color: #333;
    color: white;
    padding: 20px;
    text-align: center;
  }
  
  .app-footer .social-links {
    margin-top: 10px;
  }
  
  .app-footer .social-links a {
    margin-right: 10px;
    color: white;
    text-decoration: none;
  }
  </style>
  