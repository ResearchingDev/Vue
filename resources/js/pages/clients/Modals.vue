<template>
    <button class="btn btn-success btn-sm btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#clientModal">Add
    </button>
    <div class="modal fade modal-bookmark" id="clientModal" ref="clientModal" tabindex="-1" role="dialog"
        aria-labelledby="clientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Add Client</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" @click="clearForm"></button>
                </div>
                <div class="modal-body">
                    <form class="form-bookmark needs-validation" id="client-form" novalidate @submit.prevent="submitClient">
                        <div class="row">
                            <!-- Client Details -->
                            <h6 class="mb-3">Client Details</h6>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Client Name</label>
                                    <input v-model="client_name" class="form-control" type="text" placeholder="Client Name" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input v-model="email" class="form-control" type="email" placeholder="Client Email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input v-model="phone_number" class="form-control" type="text" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alternate Phone Number</label>
                                    <input v-model="alternate_phone_number" class="form-control" type="text" placeholder="Alternate Phone Number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input v-model="address" class="form-control" type="text" placeholder="Client Address">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select v-model="status" class="form-control btn-square" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Login Credentials Section -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Login Credentials</h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input v-model="username" class="form-control" type="text" placeholder="Username" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input v-model="password" class="form-control" type="password" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-secondary" type="submit">Save Client</button>
                        <button class="btn btn-primary ms-2" type="button" data-bs-dismiss="modal" @click="clearForm">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

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
        };
    },
    methods: {
        async submitClient() {
            const formData = {
                client_name: this.client_name,
                email: this.email,
                phone_number: this.phone_number,
                alternate_phone_number: this.alternate_phone_number,
                address: this.address,
                status: this.status,
                username: this.username,
                password: this.password,
            };

            try {
                if(this.id){
                    await axios.put(`/api/clients/${this.id}`, formData);
                }else{
                    await axios.post('/api/clients', formData);
                }
                // Adjust the URL to match your API route
                alert('Client added successfully');
                this.clearForm();
                this.$emit('updateCompleted');
                const modal = bootstrap.Modal.getInstance(document.getElementById('clientModal'));
                modal.hide();
            } catch (error) {
                console.error('Error saving client:', error.response?.data || error.message);
                alert('Failed to save client');
            }
        },
        clearForm() {
            this.client_name = '';
            this.email = '';
            this.phone_number = '';
            this.alternate_phone_number = '';
            this.address = '';
            this.status = '';
            this.username = '';
            this.password = '';
        },
        openModal(userData) {
        this.id = userData.id;
        this.client_name = userData.client_name;
        this.username = userData.user.username;
        this.email = userData.email;
        this.password = "";
        this.phone_number = userData.phone_number;
        this.alternate_phone_number = userData.user.alter_phone_number;
        this.address = userData.address;
        this.status = userData.status;
        // Show the modal using Vue ref
        const modal = new bootstrap.Modal(this.$refs.clientModal);
        modal.show();
      },
    },
};
</script>
