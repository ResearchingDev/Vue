<template>
    <div class="col-xl-12">
      <form class="card" @submit.prevent="updateProfile">
        <div class="card-header">
          <h4 class="card-title mb-0">Edit Profile</h4>
          <div class="card-options">
            <a class="card-options-collapse" href="javascript:void(0)" data-bs-toggle="card-collapse">
              <i class="fe fe-chevron-up"></i>
            </a>
            <a class="card-options-remove" href="javascript:void(0)" data-bs-toggle="card-remove">
              <i class="fe fe-x"></i>
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-5">
              <div class="mb-3">
                <label class="form-label">Company</label>
                <input class="form-control" type="text" v-model="user.company" placeholder="Company" />
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" type="text" v-model="user.username" placeholder="Username" />
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="mb-3">
                <label class="form-label">Email address</label>
                <input class="form-control" type="email" v-model="user.email" placeholder="Email" />
              </div>
            </div>
            <div class="col-sm-6 col-md-6">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input class="form-control" type="text" v-model="user.first_name" placeholder="First Name" />
              </div>
            </div>
            <div class="col-sm-6 col-md-6">
              <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input class="form-control" type="text" v-model="user.last_name" placeholder="Last Name" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">Address</label>
                <input class="form-control" type="text" v-model="user.address" placeholder="Home Address" />
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="mb-3">
                <label class="form-label">City</label>
                <input class="form-control" type="text" v-model="user.city" placeholder="City" />
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="mb-3">
                <label class="form-label">Postal Code</label>
                <input class="form-control" type="number" v-model="user.postal_code" placeholder="ZIP Code" />
              </div>
            </div>
            <div class="col-md-5">
              <div class="mb-3">
                <label class="form-label">Country</label>
                <select class="form-control btn-square" v-model="user.country">
                  <option value="0">--Select--</option>
                  <option value="1">Germany</option>
                  <option value="2">Canada</option>
                  <option value="3">USA</option>
                  <option value="4">Australia</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div>
                <label class="form-label">About Me</label>
                <textarea class="form-control" rows="5" v-model="user.about" placeholder="Enter About your description"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-end">
          <button class="btn btn-primary" type="submit">Update Profile</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'ProfilePage',
    data() {
      return {
        user: {
          company: '',
          username: '',
          email: '',
          first_name: '',
          last_name: '',
          address: '',
          city: '',
          postal_code: '',
          country: '',
          about: ''
        },
        isLoading: true, // Flag to show loading state
      };
    },
    mounted() {
      this.fetchUserDetails();
    },
    methods: {
      async fetchUserDetails() {
        try {
          // Get the token or user ID from localStorage (adjust according to your needs)
          const token = localStorage.getItem('token');
          const userDetails = JSON.parse(localStorage.getItem('User'));
          const userId = userDetails.id;
          const response = await axios.get('/api/users/'+userId, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
  
          // Update the user object with the data returned from the API
          this.user = response.data;
          console.log( this.user)
          // Set isLoading to false once data is fetched
          this.isLoading = false;
        } catch (error) {
          console.error('Error fetching user details:', error);
          this.isLoading = false; // Hide loading state if there's an error
        }
      },
      async updateProfile() {
        try {
          // Update the profile data via an API POST request
          const token = localStorage.getItem('token');
          await axios.put('/api/admin/user', this.user, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
          alert('Profile updated successfully!');
        } catch (error) {
          console.error('Error updating profile:', error);
          alert('An error occurred while updating your profile.');
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add custom styles here */
  </style>
  