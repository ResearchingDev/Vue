<template>
  <div>
    <div v-if="user">
      <!-- Profile Navigation with User Info -->
      <li class="profile-nav onhover-dropdown pe-0 py-0">
        <div class="media profile-media">
          <div v-if="user.profile_picture">
            <!-- Display User's Profile Picture -->
            <img class="b-r-10" :src="getProfileImage(user.profile_picture)" alt="Profile Image" width="35px"
              height="35px" />
          </div>
          <div v-else>
            <!-- Default Image if No Profile Picture -->
            <img class="b-r-10" src="@/assets/images/dashboard/profile.png" alt="Default Profile Image" width="35px"
              height="35px" />
          </div>
          <div class="media-body">
            <!-- Display User's Full Name and Role -->
            <span>{{ user.first_name }} {{ user.last_name }}</span>
            <p class="mb-0 font-roboto">
              {{ user.role_name }} <i class="middle fa fa-angle-down"></i>
            </p>
          </div>
        </div>
        <ul class="profile-dropdown onhover-show-div">
          <li>
            <router-link to="/profile">
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
import axios from 'axios';
export default {
  name: 'ProfilePage',
  data() {
    return {
      // Retrieve user details from localStorage when the component is created
      user: JSON.parse(localStorage.getItem('User')) || null
    };
  },
  methods: {
    /**
     * Get the full URL for the profile image or return the default image URL.
     */
    getProfileImage(image) {
      // If image is available, return the full URL; otherwise, return the default image
      return image && image.trim() !== ''
        ? `${window.location.origin}/storage/${image}`
        : '@/assets/images/dashboard/profile.png';
    },

    /**
     * Log out the user and clear session data.
     */
    logout() {
      // Call the API to log out on the server side
      axios.post(`/api/logout`, {}, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}` // If using token-based auth
        }
      })
        .then(response => {
          // On success, clear the user details from localStorage
          localStorage.removeItem('User');
          localStorage.removeItem('token');
          // Redirect to login page
          this.$router.push('/login');
        })
        .catch(error => {
          console.error('Logout failed:', error);

          // Optionally, handle error if logout API fails
        });
    }
  }
};
</script>