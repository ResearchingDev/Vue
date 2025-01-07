<template>
    <button class="btn btn-success btn-sm btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#exampleModal">Add
    </button>

    <div class="modal fade modal-bookmark" id="exampleModal" ref="UserModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        @click="clearForm"></button>
                </div>
                <div class="modal-body">
                    <form class="form-bookmark needs-validation" id="bookmark-form" novalidate
                        @submit.prevent="submitBookmark">
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input v-model="first_name" class="form-control" type="text"
                                        placeholder="First Name" required>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input v-model="last_name" class="form-control" type="text" placeholder="Last Name"
                                        required>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input v-model="email" class="form-control" type="email" placeholder="Email"
                                        required>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input v-model="username" class="form-control" type="text" placeholder="Username"
                                        required>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input v-model="password" class="form-control" type="password"
                                        placeholder="Password" required>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone number</label>
                                    <input v-model="phone_number" class="form-control" type="text"
                                        placeholder="Phone Number" required>
                                </div>
                            </div>

                            <!-- Alternate Number -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alternate number</label>
                                    <input v-model="alter_phone_number" class="form-control" type="text"
                                        placeholder="Alternate number">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select v-model="status" class="form-control btn-square" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- User Type -->
                            <div class="col-sm-6 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select v-model="user_type" class="form-control btn-square" required>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea v-model="address" class="form-control" placeholder="Enter your address"
                                        required></textarea>
                                </div>
                            </div>

                            <!-- Profile Picture Section -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="mb-3">Profile Picture</h6>
                                <div class="mb-3">
                                    <input type="file" class="form-control" @change="handleFileChange"
                                        ref="profilePicture" accept="image/*">
                                </div>
                                <div v-if="profilePicPreview" class="mb-3">
                                    <img :src="profilePicPreview" alt="Profile Preview" class="img-fluid"
                                        style="max-width: 100px; max-height: 100px;">
                                </div>
                            </div>

                        </div>
                        <button class="btn btn-secondary" type="submit">Save</button>
                        <button class="btn btn-primary ms-2" type="button" data-bs-dismiss="modal"
                            @click="clearForm">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { toast } from 'vue3-toastify';
export default {
    data() {
        return {
            username: '',
            email: '',
            password: '',
            first_name: '',
            last_name: '',
            phone_number: '',
            alter_phone_number: '',
            status: 'Active',
            user_type: 'User',
            address: '',
            profilePic: null,
            profilePicPreview: null,
            id : ''
        };
    },
    methods: {
        async submitBookmark() {
            const formData = new FormData();

            formData.append('username', this.username);
            formData.append('email', this.email);
            formData.append('password', this.password);
            formData.append('first_name', this.first_name);
            formData.append('last_name', this.last_name);
            formData.append('phone_number', this.phone_number);
            formData.append('alter_phone_number', this.alter_phone_number);
            formData.append('status', this.status);
            formData.append('user_type', this.user_type);
            formData.append('address', this.address);

            // Append profile picture if selected
            if (this.profilePic) {
                formData.append('profile_picture', this.profilePic);
            }
            try {
                const user_ajax_url = (this.id) ? ' /api/admin/users/update/'+this.id : '/api/admin/users' ;
                const alert_message = (this.id) ? 'User Updated SuccessFully..!' : 'User Created SuccessFully..!' ;
                // Adjust the URL to match your API route
                await axios.post(user_ajax_url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data', // Important to set this when uploading files
                    },
                });
                toast.success(alert_message);
                this.$emit('updateCompleted');
                this.clearForm();
                const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                modal.hide();
            } catch (error) {
                console.error('Error saving user:', error.response?.data || error.message);
                toast.warn('Failed to save user');
            }
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.profilePic = file;
                const reader = new FileReader();
                reader.onload = () => {
                    this.profilePicPreview = reader.result;
                };
                reader.readAsDataURL(file);
            }
        },
        openModal(userData) {
            // Update fields to match the new form structure
            this.id = userData.id;
            this.first_name = userData.first_name;  // Updated
            this.last_name = userData.last_name;    // Updated
            this.email = userData.email;
            this.username = userData.username;
            this.password = '';  // Reset password field (if needed)
            this.phone_number = userData.phone_number;
            this.alter_phone_number = userData.alternate_phone_number; // Updated
            this.address = userData.address; // Assuming userData has address field
            this.status = userData.status;
            this.user_type = userData.user_type; // If you have a field for user type
            this.profilePic = null; // Reset profile picture selection
            this.profilePicPreview = null; // Reset profile picture preview

            // Check if profile picture exists in the user data
            if (userData.profile_picture) {
                // Construct the image URL based on the user data
                const imagePath = `${window.location.origin}/storage/${userData.profile_picture}`;

                // Check if the image exists by attempting to load it
                const img = new Image();
                img.onload = () => {
                    // If the image is successfully loaded, set the preview
                    this.profilePicPreview = imagePath;
                };
                img.onerror = () => {
                    // If image loading fails (file does not exist), set the preview to null
                    this.profilePicPreview = null;
                };
                // Trigger image loading
                img.src = imagePath;
            }
            // Open the modal using Bootstrap
            const modal = new bootstrap.Modal(this.$refs.UserModal);
            modal.show();
        },
        clearForm() {
            this.id = '';
            this.username = '';
            this.email = '';
            this.password = '';
            this.secondary_password = '';
            this.first_name = '';
            this.last_name = '';
            this.phone_number = '';
            this.alter_phone_number = '';
            this.status = '';
            this.user_type = '';
            this.address = '';
            this.profilePic = null;
            this.profilePicPreview = null;
            this.$refs.profilePicture.value = '';
        },
    },
};
</script>
