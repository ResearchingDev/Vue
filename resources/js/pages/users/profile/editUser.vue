<template>
    <div class="modal fade modal-bookmark" id="editUserModal" tabindex="-1" role="dialog"
        aria-labelledby="editUserModalLabel" aria-hidden="true" ref="editUserModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" @click="clearForm"></button>
                </div>
                <div class="modal-body">
                    <form class="form-bookmark needs-validation" id="edit-bookmark-form" novalidate @submit.prevent="submitEdituser" >
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input v-model="username" class="form-control" type="text" placeholder="Username" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input v-model="email" class="form-control" type="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input v-model="password" class="form-control" type="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Secondary Password</label>
                                    <input v-model="secondary_password" class="form-control" type="password" placeholder="Secondary Password" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input v-model="first_name" class="form-control" type="text" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
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
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select v-model="status" class="form-control btn-square" required>
                                        <option value="">--Select--</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select v-model="user_type" class="form-control btn-square" required>
                                        <option value="">--Select--</option>
                                        <option value="Super Admin">Super Admin</option>
                                        <option value="Client">Client</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Mobile Login</label>
                                    <select v-model="can_login" class="form-control btn-square" required>
                                        <option value="">--Select--</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-secondary" type="submit">Update</button>
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
    name: 'editUser',
    data() {
        return {
            id: '',
            username: '',
            email: '',
            password: '',
            secondary_password: '',
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
        openModal(userData) {
            console.log(userData);
            this.id = userData.id;
            this.username = userData.username;
            this.email = userData.email;
            this.password = userData.password;
            this.secondary_password = userData.secondary_password;
            this.first_name = userData.first_name;
            this.last_name = userData.last_name;
            this.phone_number = userData.phone_number;
            this.alter_phone_number = userData.alter_phone_number;
            this.status = userData.status;
            this.user_type = userData.user_type;
            this.can_login = userData.can_login;
            // Show the modal using Vue ref
            console.log(this.$refs.editUserModal);
            const modal = new bootstrap.Modal(this.$refs.editUserModal);
            modal.show();
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
        async submitEdituser() {
            try {
                const token = localStorage.getItem('token');
                const response = await axios.put(
                    `/api/admin/users/update/${this.id}`,
                    {
                        username: this.username,
                        email: this.email,
                        password: this.password,
                        secondary_password: this.secondary_password,
                        first_name: this.first_name,
                        last_name: this.last_name,
                        phone_number: this.phone_number,
                        alter_phone_number: this.alter_phone_number,
                        status: this.status,
                        user_type: this.user_type,
                        can_login: this.can_login
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    }
                );

                // Handle success (e.g., show a success message or close the modal)
                // Notify parent to reload DataTable
                this.$emit('updateCompleted');
                this.clearForm();
                const modal = bootstrap.Modal.getInstance(this.$refs.editUserModal);
                modal.hide();
            } catch (error) {
                // Handle error (e.g., show an error message)
                // alert('Failed to update user');
                console.error(error);
            }
        }
    },
};
</script>