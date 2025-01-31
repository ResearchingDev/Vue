<template>
    <Button buttonClass="btn btn-success btn-sm btn-block btn-mail w-100" data-bs-toggle="modal"
        data-bs-target="#UserModal" id="add_user" @click="handleAddUser">Add
    </Button>
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
                                    <Label labelClass="form-label">First Name</Label>
                                    <Commoninput v-model="first_name" inputClass="form-control" type="text"
                                                placeholder="First Name" required/>
                                    <ErrorMessage :errorMessage="validationErrors.first_name ? validationErrors.first_name[0] : ''" />
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Last Name</Label>
                                    <Commoninput v-model="last_name" inputClass="form-control" type="text"
                                            placeholder="Last Name" required/>
                                    <ErrorMessage :errorMessage="validationErrors.last_name ? validationErrors.last_name[0] : ''" />
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Email address</Label>
                                    <Commoninput v-model="email" inputClass="form-control" type="email"
                                            placeholder="Email" required/>
                                    <ErrorMessage :errorMessage="validationErrors.email ? validationErrors.email[0] : ''" />
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Username</Label>
                                    <Commoninput v-model="username" inputClass="form-control" type="text"
                                            placeholder="Username" required/>
                                    <ErrorMessage :errorMessage="validationErrors.username ? validationErrors.username[0] : ''" />
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Password</Label>
                                    <Commoninput v-model="password" inputClass="form-control" type="password"
                                            placeholder="Password" required/>
                                    <ErrorMessage :errorMessage="validationErrors.password ? validationErrors.password[0] : ''" />
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Phone number</Label>
                                    <Commoninput v-model="phone_number" inputClass="form-control" type="text"
                                        placeholder="Phone Number" required/>
                                    <ErrorMessage :errorMessage="validationErrors.phone_number ? validationErrors.phone_number[0] : ''" />
                                </div>
                            </div>

                            <!-- Alternate Number -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Alternate number</Label>
                                    <Commoninput v-model="alter_phone_number" inputClass="form-control" type="text"
                                        placeholder="Alternate number" required/>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Status</Label>
                                    <CommonSelect
                                        v-model="status"
                                        :options="[
                                            { value: 'Active', text: 'Active' },
                                            { value: 'Inactive', text: 'Inactive' }
                                        ]"
                                        selectClass="form-control btn-square"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- User Type -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <Label labelClass="form-label">User Type</Label>
                                    <select v-model="user_role" class="form-control" required>
                                        <option value="">Select User Role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.role_name }}
                                        </option>
                                    </select>
                                    <ErrorMessage :errorMessage="validationErrors.role_id ? validationErrors.role_id[0] : ''" />
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <Label labelClass="form-label">Address</Label>
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
                                <ErrorMessage :errorMessage="validationErrors.profile_picture ? validationErrors.profile_picture[0] : ''" />
                                <div v-if="profilePicPreview" class="mb-3">
                                    <img :src="profilePicPreview" alt="Profile Preview" class="img-fluid"
                                        style="max-width: 100px; max-height: 100px;">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <Button buttonClass="btn btn-secondary" type="submit">Save</Button>
                        <Button buttonClass="btn btn-primary ms-2" type="button" data-bs-dismiss="modal"
                            @click="clearForm">Cancel</Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { toast } from 'vue3-toastify';
import Button from '../../components/formElements/Button.vue';
import Label from '../../components/formElements/Label.vue';
import Commoninput from '../../components/formElements/Commoninput.vue';
import CommonSelect from '../../components/formElements/CommonSelect.vue';
import ErrorMessage from '../../components/formElements/ErrorMessage.vue';
export default {
    components:{
        Button,
        Label,
        Commoninput,
        CommonSelect,
        ErrorMessage,
    },
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
