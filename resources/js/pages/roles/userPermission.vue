<template>
    <Breadcrumbs :title="isEditMode ? 'Edit User Roles' : 'Add User Roles'" main="Roles" />
    <div class="container-fluid">
        <div class="card">
            <div class="row">
                <div class="add-user-roles">
                    <div class="container">
                        <h3 class="mb-4">{{ isEditMode ? "Update User Roles and Permissions" : "Add User Roles and Permissions" }}</h3>
                        <!-- Role Details Section -->
                        <div class="role-details row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="roleName" class="fw-bold">Role Name</label>
                                <input id="roleName" v-model="role.roleName" type="text" class="form-control" placeholder="Enter Role Name" />
                                <span class="validate-error text-danger mt-4" v-if="errors.roleName">{{ errors.roleName }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="roleCode" class="fw-bold">Role Code</label>
                                <input id="roleCode" v-model="role.roleCode" type="text" class="form-control" placeholder="Enter Role Code" />
                                <span class="validate-error text-danger mt-4" v-if="errors.roleCode">{{ errors.roleCode }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label class="fw-bold">User Access</label>
                                <div class="form-check">
                                    <input
                                        id="webAccess"
                                        v-model="role.userAccess"
                                        value="Web Access"
                                        type="checkbox"
                                        class="form-check-input"
                                    />
                                    <label for="webAccess">Web Access</label>
                                </div>
                                <div class="form-check">
                                    <input
                                        id="mobileAccess"
                                        v-model="role.userAccess"
                                        value="Mobile Access"
                                        type="checkbox"
                                        class="form-check-input"
                                    />
                                    <label for="mobileAccess">Mobile Access</label>
                                </div>
                                <span class="validate-error text-danger mt-4" v-if="errors.userAccess">{{ errors.userAccess }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="status" class="fw-bold">Status</label>
                                <select id="status" v-model="role.status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- Permissions Section -->
                        <div class="permissions">
                            <h3>Select Modules</h3>
                            <span class="validate-error text-danger mt-4" v-if="errors.permissions">{{ errors.permissions }}</span>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Module</th>
                                        <th class="fw-bold">Delete</th>
                                        <th class="fw-bold">Update</th>
                                        <th class="fw-bold">Add</th>
                                        <th class="fw-bold">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(module, index) in modules" :key="index">
                                        <td>{{ module.name }}</td>
                                        <td><input type="checkbox" v-model="module.permissions.delete" /></td>
                                        <td><input type="checkbox" v-model="module.permissions.update" /></td>
                                        <td><input type="checkbox" v-model="module.permissions.add" /></td>
                                        <td><input type="checkbox" v-model="module.permissions.view" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Actions -->
                        <div class="actions">
                            <button class="btn btn-primary" @click="saveRole">{{ isEditMode ? "Update" : "Save" }}</button>
                            <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
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
        props: ['id'],
        data() {
            return {
                isEditMode: false,
                role: {
                    id: null,
                    roleName: '',
                    roleCode: '',
                    userAccess: [],
                    status: 'Active',
                },
                modules: [],
                errors: {
                    roleName: '',
                    roleCode: '',
                    userAccess: '',
                    permissions: '',
                },
            };
        },
        methods: {
            async fetchModules() {
                try {
                    const response = await axios.get('/api/modules_list');
                    if (response.data && response.data.status === 'success') {
                        this.modules = response.data.data.map(module => ({
                            name: module.name,
                            permissions: {
                                delete: false,
                                update: false,
                                add: false,
                                view: false,
                            },
                        }));
                    } else {
                        console.error('Failed to fetch modules:', response.data.message);
                    }
                } catch (error) {
                    console.error('Error fetching modules:', error.message);
                }
            },
            async saveRole() {
                this.errors = {
                    roleName: '',
                    roleCode: '',
                    userAccess: '',
                    permissions: '',
                };
                // Validate Role Name
                if (!this.role.roleName) {
                    this.errors.roleName = 'Role name is required.';
                }
                // Validate Role Code
                if (!this.role.roleCode) {
                    this.errors.roleCode = 'Role code is required.';
                }
                // Validate User Access
                if (!this.role.userAccess.length) {
                    this.errors.userAccess = 'At least one User Access option must be selected.';
                }
                // Validate Permissions
                let hasPermission = false;
                for (const module of this.modules) {
                    if (module.permissions.delete || module.permissions.update || module.permissions.add || module.permissions.view) {
                        hasPermission = true;
                        break;
                    }
                }
                if (!hasPermission) {
                    this.errors.permissions = 'At least one permission must be selected for each module.';
                }
                // If validation fails, stop here
                if (this.errors.roleName || this.errors.roleCode || this.errors.userAccess || this.errors.permissions) {
                    return;
                }
                // Validation Logic
                const formData = {
                    roleName: this.role.roleName,
                    roleCode: this.role.roleCode,
                    userAccess: this.role.userAccess,
                    status: this.role.status,
                    permissions: this.modules.map(module => ({
                        moduleName: module.name,
                        delete: module.permissions.delete,
                        update: module.permissions.update,
                        add: module.permissions.add,
                        view: module.permissions.view,
                    })),
                };
                try {
                    let response;
                    if (this.isEditMode) {
                        response = await axios.put(`/api/client/update_role/${this.role.id}`, formData);
                    } else {
                        response = await axios.post('/api/client/add_role', formData);
                    }

                    if (response.data && response.data.status === 'success') {
                        this.clearForm();
                        window.location.href = '/client';
                    } else {
                        alert('Failed to save role');
                    }
                } catch (error) {
                    if (error.response && error.response.data.errors) {
                        this.errors = { ...this.errors, ...error.response.data.errors };
                    } else {
                        console.error('Error saving role:', error.message);
                    }
                }
            },
            loadRoleForEdit(role) {
                this.isEditMode = true;
                this.role = {
                    id: role.role_id, // Updated to match response key
                    roleName: role.role_name, // Updated to match response key
                    roleCode: role.role_unique_code, // Updated to match response key
                    webAccess: role.web_access, // Added for web access
                    mobileAccess: role.mobile_access, // Added for mobile access
                    status: role.status, // Updated to match response key
                };
                this.modules = [{
                    name: "Permissions", // Example module name for all permissions
                    permissions: {
                        add: role.can_add === "Yes", // Convert "Yes" to boolean
                        update: role.can_update === "Yes",
                        view: role.can_view === "Yes",
                        delete: role.can_delete === "Yes",
                    },
                }];
            },
            cancelEdit() {
                this.isEditMode = false;
                this.clearForm();
                window.location.href = '/client';
            },
            clearForm() {
                this.role = {
                    id: null,
                    roleName: '',
                    roleCode: '',
                    userAccess: [],
                    status: 'Active',
                };
                this.modules = [];
            },
            editRole(roleId) {
                axios.get(`/api/client/roles/${roleId}`)
                .then(response => {
                    if (response.data) {
                        this.loadRoleForEdit(response.data);
                    } else {
                        console.error('No data received for the role');
                    }
                })
                .catch(error => {
                    console.error('Error fetching role details:', error.message);
                    alert('Failed to load role details. Please try again.');
                });
            },
        },
        mounted() {
            this.fetchModules();
            let url_roleId = this.$route.params.id;
            this.editRole(url_roleId)
        },
    };
</script>

<style>
    .add-user-roles {
        padding: 20px;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
    }
    .table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .actions {
        margin-top: 20px;
        text-align: center;
    }
    .actions .btn {
        margin: 0 10px;
    }
</style>
