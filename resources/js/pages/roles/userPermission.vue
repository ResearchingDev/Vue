<template>
    <Breadcrumbs :title="isEditMode ? 'Edit User Roles' : 'Add User Roles'" main="Roles" />
    <div class="container-fluid">
        <div class="card">
            <div class="row">
                <div class="add-user-roles">
                    <div class="container">
                        <!-- <h3 class="mb-4">{{ isEditMode ? "Update User Roles and Permissions" : "Add User Roles and Permissions" }}</h3> -->
                        <!-- Role Details Section -->
                        <div class="role-details row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="roleName" class="">Role Name</label>
                                <input id="roleName" v-model="role.roleName" type="text" class="form-control" placeholder="Enter Role Name" />
                                <span class="validate-error text-danger err mt-4" v-if="errors.roleName">{{ errors.roleName }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="roleCode" class="">Role Code</label>
                                <input id="roleCode" v-model="role.roleCode"  :readonly="isEditMode"   type="text" class="form-control" placeholder="Enter Role Code" />
                                <span class="validate-error text-danger err mt-4" v-if="errors.roleCode">{{ errors.roleCode }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label class="">User Access</label>
                                <div class="form-check">
                                    <input id="webAccess" v-model="role.userAccess" value="Web Access" type="checkbox" class="form-check-input" />
                                    <label for="webAccess">Web Access</label>
                                </div>
                                <div class="form-check">
                                    <input id="mobileAccess" v-model="role.userAccess" value="Mobile Access" type="checkbox" class="form-check-input" />
                                    <label for="mobileAccess">Mobile Access</label>
                                </div>
                                <span class="validate-error text-danger err mt-4" v-if="errors.userAccess">{{ errors.userAccess }}</span>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="status" class="">Status</label>
                                <select id="status" v-model="role.status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- Permissions Section -->
                        <div class="permissions">
                            <h3>Select Modules</h3>
                            <span class="validate-error text-danger err mt-4" v-if="errors.permissions">{{ errors.permissions }}</span>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="">Module</th>
                                        <th class="">Delete</th>
                                        <th class="">Update</th>
                                        <th class="">Add</th>
                                        <th class="">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="module in modules" :key="module.module_id">
                                        <input type="hidden" v-model="module.module_id" />
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
                loading: false,
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
                    const response = await axios.get("/api/modules_list");
                    if (response.data?.status === "success") {
                        this.modules = response.data.data.map(module => ({
                            module_id: module.id,
                            name: module.module_name || module.name || "Unnamed Module",
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
            validateForm() {
                this.errors = {
                    roleName: '',
                    roleCode: '',
                    userAccess: '',
                    permissions: '',
                };
                if (!this.role.roleName) this.errors.roleName = "Role name is required.";
                if (!this.role.roleCode) this.errors.roleCode = "Role code is required.";
                if (!this.role.userAccess.length) this.errors.userAccess = "At least one User Access option must be selected.";
                if (!this.modules.some(module => Object.values(module.permissions).includes(true))) {
                    this.errors.permissions = "At least one permission must be selected.";
                }
                return !Object.values(this.errors).some(error => error);
            },
            async saveRole() {
                if (!this.validateForm()) return;
                const user = localStorage.getItem('User');
                const parsedUser = JSON.parse(user);
                const clientId = parsedUser.client_id;
                const formData = {
                    roleName: this.role.roleName,
                    roleCode: this.role.roleCode,
                    userAccess: this.role.userAccess,
                    status: this.role.status,
                    client_id: clientId,
                    permissions: this.modules.map(module => ({
                        moduleID: module.module_id,
                        ...module.permissions,
                    })),
                };
                try {
                    this.loading = true;
                    const apiUrl = this.isEditMode
                        ? `/api/client/roles/update/${this.role.id}`
                        : "/api/client/add_role";
                    const response = await axios.post(apiUrl, formData);
                    if (response.data?.status === "success") {
                        this.clearForm();
                        window.location.href = '/client/roles';
                    } else {
                        alert(response.data.message || "Failed to save role.");
                    }
                } catch (error) {
                    if (error.response?.data.errors) {
                        this.errors = { ...this.errors, ...error.response.data.errors };
                    } else {
                        console.error("Error saving role:", error.message);
                    }
                } finally {
                    this.loading = false;
                }
            },
            cancelEdit() {
                this.isEditMode = false;
                this.clearForm();
                window.location.href = '/client/roles';
            },
            clearForm() {
                this.role = { id: null, roleName: "", roleCode: "", userAccess: [], status: "Active" };
                this.modules = [];
            },
            async loadRoleForEdit(roleId) {
                this.isEditMode = true;
                try {
                    const response = await axios.get(`/api/client/roles/${roleId}`);
                    if (response.data?.status === "success") {
                        const role = response.data.data;
                        this.role = {
                            id: role.id,
                            roleName: role.role_name,
                            roleCode: role.role_unique_code,
                            userAccess: [],
                            status: role.status,
                        };
                        if (role.web_access === "Yes") {
                            this.role.userAccess.push("Web Access");
                        }
                        if (role.mobile_access === "Yes") {
                            this.role.userAccess.push("Mobile Access");
                        }
                        this.modules = role.user_permission.map(permission => ({
                            id: permission.menu_id || permission.id,
                            name: permission.module_menu.module_name || permission.module_menu.module_name || "Unnamed Module",
                            module_id: permission.menu_id || permission.id,
                            permissions: {
                                add: permission.can_add === "Yes",
                                update: permission.can_update === "Yes",
                                view: permission.can_view === "Yes",
                                delete: permission.can_delete === "Yes",
                            },
                        }));
                    } else {
                        const errorMessage = response.data?.message || "Failed to load role data. Please try again later.";
                        console.error("Failed to load role data:", errorMessage);
                    }
                } catch (error) {
                    console.error("Error loading role for edit:", error.response?.data?.message || error.message || "Unknown error");
                }
            },
        },
        mounted() {
            this.fetchModules()
                .then(() => {
                    const roleId = this.$route.params.id;
                    if (roleId) return this.loadRoleForEdit(roleId);
                })
                .catch(error => console.error("Initialization error:", error.message));
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
