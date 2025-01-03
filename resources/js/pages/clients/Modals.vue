<template>
    <button class="btn btn-success btn-sm btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#exampleModal">Add
    </button>
    <div class="modal fade modal-bookmark" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" @click="clearForm"></button>
                </div>                
                <form class="form-bookmark needs-validation" id="bookmark-form" novalidate @submit.prevent="submitBookmark">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input v-model="username" class="form-control" type="text" placeholder="Username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input v-model="email" class="form-control" type="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input v-model="password" class="form-control" type="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Secondary Password</label>
                                    <input v-model="secondary_password" class="form-control" type="password" placeholder="Secondary Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input v-model="first_name" class="form-control" type="text" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input v-model="last_name" class="form-control" type="text" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone number</label>
                                    <input v-model="phone_number" class="form-control" type="text" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alternate number</label>
                                    <input v-model="alter_phone_number" class="form-control" type="text" placeholder="Alternate number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select v-model="status" class="form-control" required>
                                        <option value="">--Select--</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select v-model="user_type" class="form-control" required>
                                        <option value="">--Select--</option>
                                        <option value="Super Admin">Super Admin</option>
                                        <option value="Client">Client</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mobile Login</label>
                                    <select v-model="can_login" class="form-control" required>
                                        <option value="">--Select--</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">Save</button>
                        <button class="btn btn-primary ms-2" type="button" data-bs-dismiss="modal" @click="clearForm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            username: '',
            email: '',
            password: '',
            secondary_password:'',
            first_name: '',
            last_name: '',
            phone_number: '',
            alter_phone_number: '',
            status: '',
            user_type: '',
            can_login: '',
        };
    },
    methods: {
        async submitBookmark() {
            const formData = {
                username: this.username,
                email: this.email,
                password: this.password,
                secondary_password:this.secondary_password,
                first_name: this.first_name,
                last_name: this.last_name,
                phone_number: this.phone_number,
                alter_phone_number: this.alter_phone_number,
                status: this.status,
                user_type: this.user_type,
                can_login: this.can_login,
            };

            try {
                // Adjust the URL to match your API route
                await axios.post('/api/admin/users', formData);
                alert('User added successfully');
                this.clearForm();
                const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                modal.hide();
            } catch (error) {
                console.error('Error saving user:', error.response?.data || error.message);
                alert('Failed to save user');
            }
        },
        clearForm() {
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
            this.can_login = '';
        },
    },
};
</script>