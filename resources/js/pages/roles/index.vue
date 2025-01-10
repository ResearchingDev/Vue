<template>
    <div id="rolesListing">
        <Breadcrumbs title="User Roles" main="Roles" />
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 pull-left">Manage User Roles</h4>
                <div class="pull-right">
                    <!-- <router-link class="link btn btn-success btn-sm" to="/client/roles/add"> Add User Role</router-link> -->
                    <button class="link btn btn-success btn-sm add_role"> Add User Role </button>

                </div>
            </div>
            <div class="container-fluid">
                <div class="user-profile">
                    <div class="row">
                        <DataTableComponent ref="dataTableComponent" :columns="columns" apiUrl="/api/client/roles/list"
                            @edit="loadRoleForEdit" @delete="deleteRole" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addRoleForm" style="display: none;">
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
                                    <input id="roleName" v-model="role.roleName" type="text" class="form-control"
                                        placeholder="Enter Role Name" />
                                    <span class="validate-error text-danger err mt-4" v-if="errors.roleName">{{
                                        errors.roleName }}</span>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label for="roleCode" class="">Role Code</label>
                                    <input id="roleCode" v-model="role.roleCode" :readonly="isEditMode" type="text"
                                        class="form-control" placeholder="Enter Role Code" />
                                    <span class="validate-error text-danger err mt-4" v-if="errors.roleCode">{{
                                        errors.roleCode }}</span>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label class="">User Access</label>
                                    <div class="form-check">
                                        <input id="webAccess" v-model="role.userAccess" value="Web Access"
                                            type="checkbox" class="form-check-input" />
                                        <label for="webAccess">Web Access</label>
                                    </div>
                                    <div class="form-check">
                                        <input id="mobileAccess" v-model="role.userAccess" value="Mobile Access"
                                            type="checkbox" class="form-check-input" />
                                        <label for="mobileAccess">Mobile Access</label>
                                    </div>
                                    <span class="validate-error text-danger err mt-4" v-if="errors.userAccess">{{
                                        errors.userAccess }}</span>
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
                                <span class="validate-error text-danger err mt-4" v-if="errors.permissions">{{
                                    errors.permissions }}</span>
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
                                <button class="btn btn-primary" @click="saveRole">{{ isEditMode ? "Update" : "Save"
                                    }}</button>
                                <button class="btn btn-secondary cancelEdit" @click="cancelEdit">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from 'jquery';
import Swal from 'sweetalert2';
import DataTableComponent from '../../components/datatables/index.vue';

export default {
    name: 'userProfile',
    components: {
        DataTableComponent
    },
    data() {
        return {
            columns: [
                { label: 'Role Name', field: 'role_name' },
                {
                    label: 'Access',
                    field: 'user_access',
                    customRender: (row) => {
                        let access = [];
                        if (row.web_access === 'Yes') access.push('Web Access');
                        if (row.mobile_access === 'Yes') access.push('Mobile Access');
                        return access.length > 0 ? access.join(', ') : '-';
                    }
                },
                { label: 'Status', field: 'status' },
                {
                    label: 'Action',
                    field: 'action',
                    customRender: (row) => {
                        return `
                                <button class="btn btn-primary btn-sm edit-btn" data-edit-id="${row.id}"">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-delete-id="${row.id}">
                                    <i class="fa fa-trash"></i>
                                </button>`;
                    }
                },
            ],
            role: {
                id: null,
                roleName: '',
                roleCode: '',
                userAccess: [],
                status: 'Active'
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
    mounted() {
        const vueInstance = this; // Preserve the Vue instance context
        $(document).on('click', '.add_role', function () {
            $('#rolesListing').css('display', 'none');
            $('#addRoleForm').css('display', 'block');
            vueInstance.fetchModules();
        });
        $(document).on('click', '.cancelEdit', function () {
            $('#rolesListing').css('display', 'block');
            $('#addRoleForm').css('display', 'none');
        });
    },
    methods: {
        async fetchModules() {
            try {
                const response = await this.$axios.get(`/client/roles/menus`);
                if (response?.status === "success") {
                    this.modules = response.data.map(module => ({
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
                    console.error('Failed to fetch modules:', response.message);
                    this.modules = [];
                }
            } catch (error) {
                console.error('Error fetching modules:', error.message);
                this.modules = [];
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
                const apiUrl = `/client/roles${this.isEditMode ? `/${this.role.id}` : ""}`;
                const method = this.isEditMode ? "put" : "post";
                const response = await this.$axios({
                    method: method,
                    url: apiUrl,
                    data: formData
                });
                if (response?.status === "success") {
                    this.clearForm();
                    this.reloadDataTable();
                    $('#rolesListing').css('display', 'block');
                    $('#addRoleForm').css('display', 'none');
                } else {
                    alert(response.message || "Failed to save role.");
                }
            } catch (error) {
                if (error.response?.errors) {
                    this.errors = { ...this.errors, ...error.response.errors };
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
        },
        clearForm() {
            this.role = { id: null, roleName: "", roleCode: "", userAccess: [], status: "Active" };
            this.modules = [];
        },
        receiveData(data) {
            this.role = data;
            this.modules = data.modules;
        },
        async loadRoleForEdit(roleId) {
            $('#rolesListing').css('display', 'none');
            this.isEditMode = true;
            try {
                const response = await this.$axios.get(`/client/roles/${roleId}`);
                if (response?.status === "success") {
                    const role = response.data;
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
                    this.role.modules = role.user_permission.map(permission => ({
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
                    this.receiveData(this.role);
                    $('#addRoleForm').css('display', 'block');
                } else {
                    const errorMessage = response?.message || "Failed to load role data. Please try again later.";
                    console.error("Failed to load role data:", errorMessage);
                }
            } catch (error) {
                console.error("Error loading role for edit:", error.response?.message || error.message || "Unknown error");
            }
        },
        // Function to delete the User Role
        deleteRole(roleId) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You won't be able to revert this! Do you want to delete User Role?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#3085d6',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.delete(`/client/roles/${roleId}`)
                        .then((response) => {
                            Swal.fire(
                                'Deleted!',
                                response.message || 'User Role has been deleted successfully.',
                                'success'
                            );
                            this.reloadDataTable();
                        })
                        .catch((error) => {
                            let errorMessage = error.response?.data?.message || 'There was an error deleting the User Role. Please try again later.';
                            Swal.fire('Error!', errorMessage, 'error');
                            console.error('Error deleting User Role:', error);
                        });
                }
            });
        },
        reloadDataTable() {
            this.$refs.dataTableComponent.reloadDataTable();
        },
    }
}
</script>
