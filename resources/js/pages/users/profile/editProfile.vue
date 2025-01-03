<template>
    <div class="col-xl-12">
      <form class="card" @submit.prevent="updateProfile">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Email address</label>
                <input class="form-control" type="email" v-model="user.email" placeholder="Company" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input class="form-control" type="text" v-model="user.first_name" placeholder="First Name" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input class="form-control" type="text" v-model="user.last_name" placeholder="Last Name" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input class="form-control" type="text" v-model="user.phone_number" placeholder="Enter Phone Number" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">State</label>
                <input class="form-control" type="text" v-model="user.state" placeholder="State" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">City</label>
                <input class="form-control" type="text" v-model="user.city" placeholder="City" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Postal Code</label>
                <input class="form-control" type="number" v-model="user.zipcode" placeholder="ZIP Code" />
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
          username: '',
          email: '',
          first_name: '',
          last_name: '',
          address: '',
          city: '',
          state: '',
          zipcode: '',
          phone_number : ''
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
          await axios.put('/api/users/'+this.user.id, this.user, {
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
 
  