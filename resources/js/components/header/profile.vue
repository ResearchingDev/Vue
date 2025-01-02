<template>
  <div>
    <div v-if="user">
      <!-- Profile Navigation with User Info -->
      <li class="profile-nav onhover-dropdown pe-0 py-0">
        <div class="media profile-media">
          <img class="b-r-10" src="@/assets/images/dashboard/profile.png" alt="" />
          <div class="media-body">
            <span>{{ user.first_name }} {{ user.last_name }}</span>
            <p class="mb-0 font-roboto">
              {{ user.role_name }} <i class="middle fa fa-angle-down"></i>
            </p>
          </div>
        </div>
        <ul class="profile-dropdown onhover-show-div">
          <li>
            <router-link to="/users/profile">
              <vue-feather type="user"></vue-feather><span>Account</span>
            </router-link>
          </li>
          <li>
            <a @click="logout">
              <vue-feather type="log-in"></vue-feather><span>Log out</span>
            </a>
          </li>
        </ul>
      </li>
    </div>
    <!-- Show a message if no user is logged in -->
    <div v-else>
      <p>Please log in to see your profile details.</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfilePage',
  data() {
    return {
      // Retrieve user details from localStorage when the component is created
      user: JSON.parse(localStorage.getItem('User')) || null
    };
  },
  mounted() {
    // Log the user details when the component is mounted
  },
  methods: {
    logout() {
      // Clear user details from localStorage
      localStorage.removeItem('User');
      localStorage.removeItem('token');
      // Redirect to login page
      this.$router.push('/auth/login');
    }
  }
};
</script>