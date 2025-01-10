<template>
    <button class="btn btn-success btn-sm btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#UserModal" id="add_user" @click="handleAddUser">Add
    </button>

    <div class="modal fade modal-bookmark" id="UserModal" ref="UserModal" tabindex="-1" role="dialog"
        aria-labelledby="UserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UserModalLabel"> {{this.isEdit ? "Edit User" : "Add User" }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        @click="clearForm"></button>
                </div>
                <form class="form-bookmark needs-validation" id="bookmark-form" novalidate
                    @submit.prevent="submitUser">
                    <div class="modal-body">
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input v-model="first_name" class="form-control" type="text"
                                        placeholder="First Name" required>
                                    <div v-if="validationErrors.first_name" class="text-danger err">
                                        {{ validationErrors.first_name[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input v-model="last_name" class="form-control" type="text" placeholder="Last Name"
                                        required>
                                    <div v-if="validationErrors.last_name" class="text-danger err">
                                        {{ validationErrors.last_name[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input v-model="email" class="form-control" type="email" placeholder="Email"
                                        required>
                                    <div v-if="validationErrors.email" class="text-danger err">
                                        {{ validationErrors.email[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input v-model="username" class="form-control" type="text" placeholder="Username"
                                        required>
                                    <div v-if="validationErrors.username" class="text-danger err">
                                        {{ validationErrors.username[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input v-model="password" class="form-control" type="password"
                                        placeholder="Password" required>
                                    <div v-if="validationErrors.password" class="text-danger err">
                                        {{ validationErrors.password[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Phone number</label>
                                    <input v-model="phone_number" class="form-control" type="text"
                                        placeholder="Phone Number" required>
                                    <div v-if="validationErrors.phone_number" class="text-danger err">
                                        {{ validationErrors.phone_number[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Alternate Number -->
                            <div class="col-md-4">
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
                                    <select v-model="status" class="form-control " required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- User Type -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select v-model="user_role" class="form-control" required>
                                        <option value="">Select User Role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.role_name }}
                                        </option>
                                    </select>
                                    <div v-if="validationErrors.role_id" class="text-danger err">
                                        {{ validationErrors.role_id[0] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea v-model="address" class="form-control" placeholder="Enter your address"
                                        required></textarea>
                                </div>
                            </div>

                            <!-- Profile Picture Section -->
                            <div class="col-md-12">
                                <h6 class="mb-3">Profile Picture</h6>
                                <div class="mb-3">
                                    <input type="file" class="form-control" @change="handleFileChange"
                                        ref="profilePicture" accept="image/*">
                                </div>
                                <div v-if="validationErrors.profile_picture" class="text-danger err">
                                    {{ validationErrors.profile_picture[0] }}
                                </div>
                                <div v-if="profilePicPreview" class="mb-3">
                                    <img :src="profilePicPreview" alt="Profile Preview" class="img-fluid"
                                        style="max-width: 100px; max-height: 100px;">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">Save</button>
                        <button class="btn btn-primary ms-2" type="button" data-bs-dismiss="modal"
                            @click="clearForm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
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
            id : '',
            isEdit:false,
            validationErrors: {},
            roles: [],
            user_role:""
        };
    },
    methods: {
        handleAddUser() {
            this.fetchRolesAndOpenModal();
        },
        fetchRolesAndOpenModal() {
            this.fetchUserRoles();
        },
        async fetchUserRoles() {
            try {
                const user = JSON.parse(localStorage.getItem('User')); // Parse the stored JSON string
                const client_id = user?.client_id;
                const response = await this.$axios.get(`/client/users/user_roles`);
                this.roles = response.data;
                console.log('Roles fetched:', this.roles);
            } catch (error) {
                console.error('Error fetching roles:', error);
            }
        },
        async submitUser() {
            const formData = new FormData();

            formData.append('username', this.username);
            formData.append('email', this.email);
            formData.append('password', this.password);
            formData.append('first_name', this.first_name);
            formData.append('last_name', this.last_name);
            formData.append('phone_number', this.phone_number);
            formData.append('alter_phone_number', this.alter_phone_number);
            formData.append('status', this.status);
            formData.append('user_type', 'User');
            formData.append('address', this.address);
            const selectedRole = this.roles.find((role) => role.id === this.user_role);
            if (selectedRole) {
                formData.append('role_id', selectedRole.id);
            } else {
                console.error('No valid role selected.');
            }

            // Append profile picture if selected
            if (this.profilePic) {
                formData.append('profile_picture', this.profilePic);
            }
            try {
                const user_ajax_url = (this.id) ? `/client/users/${this.id}/update`: '/client/users' ;
                const alert_message = (this.id) ? 'User Updated Successfully..!' : 'User Created Successfully..!' ;
                // Adjust the URL to match your API route
                await this.$axios.post(user_ajax_url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                toast.success(alert_message);
                this.$emit('reloadTable');
                this.clearForm();
                const modal = bootstrap.Modal.getInstance(document.getElementById('UserModal'));
                modal.hide();
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach((backdrop) => backdrop.remove());
                }, 1000);
            } catch (error) {
                if (error?.data?.errors) {
                    this.validationErrors = error.data.errors;
                } else {
                    console.error('Error saving user:', error?.data || error.message);
                    toast.warn('Failed to save user');
                }
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
            this.fetchUserRoles().then(() => {
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
                this.user_type = "User"; // If you have a field for user type
                this.profilePic = null; // Reset profile picture selection
                this.profilePicPreview = null; // Reset profile picture preview
                this.isEdit = true; // Reset profile picture preview

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
                const selectedRole = this.roles.find(role => role.id === userData.role_id);
                if (selectedRole) {
                    this.user_role = selectedRole.id;
                } else {
                    console.warn('No matching role found for user.');
                }
                // Open the modal using Bootstrap
                const modal = new bootstrap.Modal(this.$refs.UserModal);
                modal.show();
            });
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
            this.status = 'Active';
            this.user_type = 'User';
            this.address = '';
            this.profilePic = null;
            this.profilePicPreview = null;
            this.$refs.profilePicture.value = '';
            this.role_id = '';
            this.user_role = "";
            this.isEdit = false;
            this.errors = {};
            this.validationErrors = {};
        },
    },
};
</script>
