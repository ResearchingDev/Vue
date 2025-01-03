<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 p-0">
          <div class="login-card">
            <div>
              <div>
                <a class="logo">
                  <img class="img-fluid for-light" src="../../assets/images/logo/logo.png" width="121" height="35"
                    alt="loginpage" />
                </a>
              </div>
              <div class="login-main">
                <form class="theme-form">
                  <h4>Sign in to account</h4>
                  <p>Enter your email & password to login</p>

                  <!-- Show error or success messages -->
                  <div v-if="alertMessage" :class="['alert', alertMessage.type === 'success' ? 'alert-success' : 'alert-danger']">
                    <span>{{ alertMessage.message }}</span>
                    <span class="close" @click="alertMessage = null">&times;</span>
                  </div>

                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                    <input class="form-control" type="email" required="" placeholder="Test@gmail.com"
                      v-model="user.email.value">
                    <span class="validate-error" v-if="!user.email.value || !validEmail(user.email.value)">{{ user.email.errormsg }}</span>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                      <!-- Password input with dynamic type based on showPassword state -->
                      <input 
                        class="form-control" 
                        :type="showPassword ? 'text' : 'password'" 
                        name="login[password]" 
                        required="" 
                        placeholder="*********" 
                        v-model="user.password.value">
                      
                      <span class="validate-error" v-if="user.password.value.length < 7">{{ user.password.errormsg }}</span>

                      <!-- Show/Hide password button -->
                      <div class="show-hide" @click="togglePasswordVisibility">
                        <span :class="showPassword ? 'hide' : 'show'"></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <div class="checkbox p-0">
                      <input id="checkbox1" type="checkbox">
                      <label class="text-muted" for="checkbox1">Remember password</label>
                    </div>
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit" @click.prevent="login">Sign
                        in</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'login',
  data() {
    return {
      result: { email: '', password: '' },
      logging: false, // Flag to disable the button while login is in progress
      user: {
        email: {
          value: '',
          errormsg: ''
        },
        password: {
          value: '',
          errormsg: ''
        }
      },
      alertMessage: null, // Holds the alert message and its type (success or error)
      showPassword: false // Flag to toggle password visibility
    };
  },

  methods: {
    // Login function
    async login() {
      // Validate password
      if (!this.user.password.value || this.user.password.value.length < 7) {
        this.user.password.errormsg = 'Password must be at least 7 characters.';
      } else {
        this.user.password.errormsg = '';
      }

      // Validate email
      if (!this.user.email.value) {
        this.user.email.errormsg = 'Email is required.';
      } else if (!this.validEmail(this.user.email.value)) {
        this.user.email.errormsg = 'Please enter a valid email address.';
      } else {
        this.user.email.errormsg = '';
      }

      // Only proceed if no validation errors
      if (!this.user.email.errormsg && !this.user.password.errormsg) {
        this.logging = true; // Disable button during login process

        try {
          // Send the login request to the backend
          const response = await axios.post('http://localhost:8000/api/login', {
            email: this.user.email.value,
            password: this.user.password.value
          });
          // Handle successful login
          if (response.data.status === 'success' && response.data.data.token) {
            // Store the token and user info in localStorage
            // Redirect based on the user role
            const user = response.data.data.user;
            localStorage.setItem('User', JSON.stringify(user));
            localStorage.setItem('token', response.data.data.token);
            if (user.role_code === 'admin') {
              this.$router.push('/admin/dashboard');
            } else {
              this.$router.push('/client/home');
            }

            // Show success alert
            this.alertMessage = {
              type: 'success',
              message: 'Login successful! Redirecting...'
            };
          } else {
            this.alertMessage = {
              type: 'error',
              message: 'Invalid credentials. Please try again.'
            };
          }
        }
        catch (error) {
          this.alertMessage = {
            type: 'error',
            message: 'Invalid credentials. Please try again.'
          };
        } finally {
          this.logging = false; // Re-enable the button after the login process
        }
      }
    },

    // Email validation function
    validEmail: function (email) {
      const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      return re.test(email);
    },

    // Toggle the visibility of the password
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    }
  }
};
</script>
<style scoped>
.validate-error {
  color: red; /* Add red color to error messages */
  font-size: 12px;
  margin-top: 5px;
}
</style>
