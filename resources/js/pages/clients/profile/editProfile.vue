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
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input class="form-control" type="text" v-model="user.first_name" placeholder="First Name" />
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input class="form-control" type="text" v-model="user.last_name" placeholder="Last Name" />
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input class="form-control" type="text" v-model="user.phone_number" placeholder="Enter Phone Number" />
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">State</label>
              <input class="form-control" type="text" v-model="user.state" placeholder="State" />
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">City</label>
              <input class="form-control" type="text" v-model="user.city" placeholder="City" />
            </div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="mb-3">
              <label class="form-label">Postal Code</label>
              <input class="form-control" type="number" v-model="user.zipcode" placeholder="ZIP Code" />
            </div>
          </div>

          <!-- Profile Picture Section -->
          <div class="mt-4 pt-3 border-top">
            <h6 class="mb-3">Profile Picture</h6>
            <div class="mb-3">
              <input type="file" class="form-control" @change="handleFileChange" ref="profilePicture" accept="image/*">
            </div>
            <div v-if="user.profilePicPreview" class="mb-3">
              <img :src="user.profilePicPreview || '/path/to/default-image.jpg'" alt="Profile Preview" class="img-fluid"
                style="max-width: 100px; max-height: 100px;">
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
import { toast } from 'vue3-toastify';

export default {
  name: 'ProfilePage',
  data() {
    return {
      user: {
        id: '',
        username: '',
        email: '',
        first_name: '',
        last_name: '',
        address: '',
        city: '',
        state: '',
        zipcode: '',
        phone_number: '',
        profilePic: null,
        profilePicPreview: null,
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

        const response = await axios.get(`/api/users/${userId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        // Update the user object with the data returned from the API
        this.user = response.data;
        this.user.profilePic = null; // Reset profile picture selection
        this.user.profilePicPreview = null; // Reset profile picture preview

        // Check if profile picture exists in the user data
        if (response.data.profile_picture) {
          const imagePath = `${window.location.origin}/storage/${response.data.profile_picture}`;
          const img = new Image();

          img.onload = () => {
            this.user.profilePicPreview = imagePath;
          };

          img.onerror = () => {
            this.user.profilePicPreview = '/path/to/default-image.jpg'; // Fallback image
          };

          img.src = imagePath;
        }

        // Set isLoading to false once data is fetched
        this.isLoading = false;
      } catch (error) {
        console.error('Error fetching user details:', error);
        this.isLoading = false; // Hide loading state if there's an error
      }
    },

    async updateProfile() {
      try {
        const formData = new FormData();

        // Directly append the user properties to the FormData
        formData.append('email', this.user.email);
        formData.append('username', this.user.username);
        formData.append('first_name', this.user.first_name);
        formData.append('last_name', this.user.last_name);
        formData.append('phone_number', this.user.phone_number);
        formData.append('state', this.user.state);
        formData.append('city', this.user.city);
        formData.append('zipcode', this.user.zipcode);
        formData.append('status', 'Active');
        formData.append('user_type',  this.user.user_type);

        formData.append('role_id',  this.user.role_id);
        // Append profile picture if selected
        if (this.user.profilePic) {
          formData.append('profile_picture', this.user.profilePic);
        }

        // Update the profile data via an API POST request
        const token = localStorage.getItem('token');
        await axios.post(`/api/users/save_users/${this.user.id}`, formData, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        toast.success('Profile updated successfully!');
      } catch (error) {
        console.error('Error updating profile:', error);
        toast.warn('An error occurred while updating your profile');
      }
    },

    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.user.profilePic = file;
        const reader = new FileReader();
        reader.onload = () => {
          this.user.profilePicPreview = reader.result;
        };
        reader.readAsDataURL(file);
      }
    },
  },
};
</script>
