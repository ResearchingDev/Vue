<template>
    <button class="btn btn-success btn-sm btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#clientModal">Add
    </button>
    <div class="modal fade modal-bookmark" id="clientModal" ref="clientModal" tabindex="-1" role="dialog"
        aria-labelledby="clientModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Add Client</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        @click="clearForm"></button>
                </div>
                <form class="form-bookmark needs-validation" id="client-form" novalidate
                    @submit.prevent="submitClient">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="mb-2 pb-2 border-bottom">Client Details</h6>
                                <div class="row">
                                    <!-- Client Details -->
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Client Name</label>
                                            <input v-model="client_name" class="form-control" type="text"
                                                placeholder="Client Name" required>
                                            <span v-if="errors.client_name" class="text-danger">{{ errors.client_name[0]
                                                }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input v-model="email" class="form-control" type="email" placeholder="Client Email"
                                                required>
                                            <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input v-model="phone_number" class="form-control" type="text"
                                                placeholder="Phone Number" required>
                                            <span v-if="errors.phone_number" class="text-danger">{{ errors.phone_number[0]
                                                }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Alternate Phone Number</label>
                                            <input v-model="alternate_phone_number" class="form-control" type="text"
                                                placeholder="Alternate Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input v-model="address" class="form-control" type="text"
                                                placeholder="Client Address">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select v-model="status" class="form-control btn-square" required>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- Login Credentials Section -->
                                <div>
                                    <h6 class="mb-2 pb-2 border-bottom">Login Credentials</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input v-model="username" class="form-control" type="text"
                                                    placeholder="Username" required>
                                                <span v-if="errors.username" class="text-danger">{{ errors.username[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input v-model="password" class="form-control" type="password"
                                                    placeholder="Password" required>
                                                <span v-if="errors.password" class="text-danger">{{ errors.password[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Picture Section -->
                                <div>
                                    <h6 class="mb-2 pb-2 border-bottom">Profile Picture</h6>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" @change="handleFileChange" ref="profilePicture"
                                            accept="image/*">
                                    </div>
                                    <span v-if="errors.profile_picture" class="text-danger">{{ errors.profile_picture[0] }}</span>
                                    <div v-if="profilePicPreview">
                                        <img :src="profilePicPreview" alt="Profile Preview" class="img-fluid"
                                            style="max-width: 100px; max-height: 100px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">Save Client</button>
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
            client_name: '',
            email: '',
            phone_number: '',
            alternate_phone_number: '',
            address: '',
            status: 'Active',
            username: '',
            password: '',
            profilePic: null,
            profilePicPreview: null,
            errors: {},
        };
    },
    methods: {
        async submitClient() {
            const formData = new FormData();
            formData.append('client_name', this.client_name);
            formData.append('email', this.email);
            formData.append('phone_number', this.phone_number);
            formData.append('alternate_phone_number', this.alternate_phone_number);
            formData.append('address', this.address);
            formData.append('status', this.status);
            formData.append('username', this.username);
            formData.append('password', this.password);
            if (this.profilePic) {
                formData.append('profile_picture', this.profilePic);
            }

            try {
                const alert_message = (this.id) ? 'Client Updated SuccessFully..!' : 'Client Created SuccessFully..!';
                const client_ajax_url = (this.id) ? `/admin/client/${this.id}` : '/admin/client';
                await this.$axios.post(client_ajax_url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.$emit('reloadTable');
                toast.success(alert_message);
                // Adjust the URL to match your API route
                const modal = bootstrap.Modal.getInstance(document.getElementById('clientModal'));
                modal.hide();
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach((backdrop) => backdrop.remove());
                }, 1000);
                this.clearForm();
            } catch (error) {
                console.log(error);
                if (error && error.status_code === 422) {
                    this.errors = error.data.errors; // Assign validation errors
                } else {
                    console.error('Error saving client:', error?.data || error.message);
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
        clearForm() {
            this.id = '';
            this.client_name = '';
            this.email = '';
            this.phone_number = '';
            this.alternate_phone_number = '';
            this.address = '';
            this.status = 'Active';
            this.username = '';
            this.password = '';
            this.profilePic = null;
            this.profilePicPreview = null;
            this.$refs.profilePicture.value = '';
            this.id = null;
            this.errors = {};
        },
        openModal(userData) {
            this.id = userData.id;
            this.client_name = userData.client_name;
            this.username = userData.user.username;
            this.email = userData.email;
            this.password = '';
            this.phone_number = userData.phone_number;
            this.alternate_phone_number = userData.user.alter_phone_number;
            this.address = userData.address;
            this.status = userData.status;
            this.profilePic = null;
            // Assuming userData contains the user object with the profile_picture field
            this.profilePicPreview = null;  // Default value
            // Check if profile picture exists in the user data
            if (userData.user.profile_picture) {
                // Construct the image URL
                const imagePath = `${window.location.origin}/storage/${userData.user.profile_picture}`;

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
            const modal = new bootstrap.Modal(this.$refs.clientModal);
            modal.show();
        },
    },
};
</script>
