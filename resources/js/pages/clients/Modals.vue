<template>
    <Button buttonClass="btn btn-success btn-sm btn-block btn-mail w-100" data-bs-toggle="modal"
        data-bs-target="#clientModal">Add
    </Button>
    <div class="modal fade modal-bookmark" id="clientModal" ref="clientModal" tabindex="-1" role="dialog"
        aria-labelledby="clientModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">{{this.isEdit ? "Edit Client" : "Add Client" }}</h5>
                    <Button buttonClass="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="clearForm"></Button>
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
                                            <Label labelClass="form-label">Client Name</Label>
                                            <Commoninput v-model="client_name" inputClass="form-control" type="text"
                                                placeholder="Client Name" required/>
                                            <ErrorMessage :errorMessage="errors.client_name ? errors.client_name[0] : ''" />

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="mb-3">
                                            <Label labelClass="form-label">Email Address</Label>
                                            <Commoninput v-model="email" inputClass="form-control" type="email" placeholder="Client Email"
                                                required/>
                                            <ErrorMessage :errorMessage="errors.email ? errors.email[0] : ''" />

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <Label labelClass="form-label">Phone Number</Label>
                                            <Commoninput v-model="phone_number" inputClass="form-control" type="text"
                                                placeholder="Phone Number" required />
                                            <ErrorMessage :errorMessage="errors.phone_number ? errors.phone_number[0] : ''" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <Label labelClass="form-label">Alternate Phone Number</Label>
                                            <Commoninput v-model="alternate_phone_number" inputClass="form-control" type="text"
                                                placeholder="Alternate Phone Number" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <Label labelClass="form-label">Address</Label>
                                            <Commoninput v-model="address" inputClass="form-control" type="text"
                                                placeholder="Client Address" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
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
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- Login Credentials Section -->
                                <div>
                                    <h6 class="mb-2 pb-2 border-bottom">Login Credentials</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <Label labelClass="form-label">Username</Label>
                                                <Commoninput v-model="username" inputClass="form-control" type="text"
                                                    placeholder="Username" required />
                                                <ErrorMessage :errorMessage="errors.username ? errors.username[0] : ''" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <Label labelClass="form-label">Password</Label>
                                                <Commoninput v-model="password" inputClass="form-control" type="password"
                                                    placeholder="Password" required />
                                                <ErrorMessage :errorMessage="errors.password ? errors.password[0] : ''" />
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
                                    <ErrorMessage :errorMessage="errors.profile_picture ? errors.profile_picture[0] : ''" />
                                    <div v-if="profilePicPreview">
                                        <img :src="profilePicPreview" alt="Profile Preview" class="img-fluid"
                                            style="max-width: 100px; max-height: 100px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    <div class="modal-footer">
                        <Button buttonClass="btn btn-secondary" type="submit">Save Client</Button>
                        <Button buttonClass="btn btn-primary ms-2" type="button" data-bs-dismiss="modal"
                            @click="clearForm">Cancel</Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Button from '../../components/formElements/Button.vue';
import Label from '../../components/formElements/Label.vue';
import Commoninput from '../../components/formElements/Commoninput.vue';
import CommonSelect from '../../components/formElements/CommonSelect.vue';
import ErrorMessage from '../../components/formElements/ErrorMessage.vue';
import { toast } from 'vue3-toastify';
export default {
    components: {
        Button,
        Label,
        Commoninput,
        CommonSelect,
        ErrorMessage,
    },
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
            isEdit : false
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
                const alert_message = (this.id) ? 'Client Updated Successfully..!' : 'Client Created Successfully..!';
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
            this.isEdit = false;
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
            this.isEdit = true;
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
