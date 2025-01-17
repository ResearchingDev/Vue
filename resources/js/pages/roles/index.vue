<template>
    <div id="rolesManagement">
        <!-- Breadcrumb -->
        <Breadcrumbs :title="isEditMode ? 'Edit User Role' : 'User Roles'" main="Roles" />

        <!-- Role Listing Section -->
        <div v-if="showRolesListing" class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 pull-left">Manage User Roles</h4>
                <button class="btn btn-success btn-sm pull-right" @click="openAddRoleForm">Add User Role</button>
            </div>
            <div class="card-body">
                <DataTableComponent ref="dataTableComponent" :columns="columns" apiUrl="/api/client/roles/list"
                    @edit="loadRoleForEdit" @delete="deleteRole" />
            </div>
        </div>

        <!-- Add/Edit Role Form -->
        <div v-else class="card">
            <div class="card-header">
                <h4>{{ isEditMode ? "Edit User Role" : "Add User Role" }}</h4>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveRole">
                    <!-- Role Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roleName">Role Name</label>
                                <input id="roleName" v-model="role.roleName" type="text" class="form-control" placeholder="Enter Role Name" />
                                <span class="text-danger" v-if="errors.roleName">{{ errors.roleName }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roleCode">Role Code</label>
                                <input id="roleCode" v-model="role.roleCode" :readonly="isEditMode" type="text" class="form-control" placeholder="Enter Role Code" />
                                <span class="text-danger" v-if="errors.roleCode">{{ errors.roleCode }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- User Access and Status -->
                     <div class="row">
                        <div class="form-group col-md-6 mt-3">
                            <label>User Access</label>
                            <div class="form-check">
                                <input id="webAccess" value="Web Access" type="checkbox" v-model="role.userAccess" class="form-check-input" />
                                <label for="webAccess" class="form-check-label">Web Access</label>
                            </div>
                            <div class="form-check">
                                <input id="mobileAccess" value="Mobile Access" type="checkbox" v-model="role.userAccess" class="form-check-input" />
                                <label for="mobileAccess" class="form-check-label">Mobile Access</label>
                            </div>
                            <span class="text-danger" v-if="errors.userAccess">{{ errors.userAccess }}</span>
                        </div>
                        <div class="form-group col-md-6 mt-3">
                            <label for="status" class="">Status</label>
                            <select id="status" v-model="role.status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <!-- Permissions -->
                    <div class="form-group">
                        <h5>Permissions</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Delete</th>
                                    <th>Update</th>
                                    <th>Add</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="module in modules" :key="module.module_id">
                                    <td>{{ module.name }}</td>
                                    <td><input type="checkbox" v-model="module.permissions.delete" class="form-check-input" /></td>
                                    <td><input type="checkbox" v-model="module.permissions.update" class="form-check-input" /></td>
                                    <td><input type="checkbox" v-model="module.permissions.add" class="form-check-input" /></td>
                                    <td><input type="checkbox" v-model="module.permissions.view" class="form-check-input" /></td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="text-danger" v-if="errors.permissions">{{ errors.permissions }}</span>
                    </div>
                    <!-- Actions -->
                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-primary m-3">{{ isEditMode ? "Update" : "Save" }}</button>
                        <button type="button" class="btn btn-secondary" @click="cancelBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import DataTableComponent from "../../components/datatables/index.vue";
import { toast } from 'vue3-toastify';
export default {
    name: "RolesManagement",
    components: { DataTableComponent },
    data() {
        return {
            isEditMode: false,
            showRolesListing: true,
            columns: [
                { label: "Role Name", field: "role_name" },
                { label: "Access", field: "user_access", customRender: this.renderAccess },
                { label: "Status", field: "status" },
                { label: "Action", field: "action", customRender: this.renderActions },
            ],
            role: {
                id: null,
                roleName: "",
                roleCode: "",
                userAccess: [],
                status: "Active",
            },
            modules: [],
            errors: {},
        };
    },
    methods: {
        renderAccess(row) {
            const access = [];
            if (row.web_access === "Yes") access.push("Web Access");
            if (row.mobile_access === "Yes") access.push("Mobile Access");
            return access.join(", ") || "-";
        },
        renderActions(row) {
            let actions = `
                <button class="btn btn-primary btn-sm edit-btn" data-edit-id="${row.id}">
                    <i class="fa fa-pencil"></i>
                </button>
            `;
            if (row.role_unique_code !== "admin" && row.role_unique_code !== "supervisor") {
                actions += `
                    <button class="btn btn-danger btn-sm delete-btn" data-delete-id="${row.id}">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
            }
            return actions;
        },
        async fetchModules() {
            try {
                const response = await this.$axios.get("/client/roles/menus");
                if (response?.status === "success") {
                    this.modules = response.data.map((module) => ({
                        module_id: module.id,
                        name: module.name || "Unnamed Module",
                        permissions: { delete: false, update: false, add: false, view: false },
                    }));
                }
            } catch (error) {
                console.error("Failed to fetch modules:", error.message);
            }
        },
        validateForm() {
            this.errors = {};
            if (!this.role.roleName) this.errors.roleName = "Role name is required.";
            if (!this.role.roleCode) this.errors.roleCode = "Role code is required.";
            if (!this.role.userAccess.length) this.errors.userAccess = "At least one user access option is required.";
            if (!this.modules.some((m) => Object.values(m.permissions).includes(true))) {
                this.errors.permissions = "At least one permission must be selected.";
            }
            return Object.keys(this.errors).length === 0;
        },
        async saveRole() {
            if (!this.validateForm()) return;

            const apiUrl = this.isEditMode ? `/client/roles/${this.role.id}` : "/client/roles";
            const method = this.isEditMode ? "put" : "post";
            const alert_message = (this.isEditMode) ? 'Role Updated Successfully..!' : 'Role Created Successfully..!' ;
            try {
                const response = await this.$axios({
                    url: apiUrl,
                    method,
                    data: {
                        ...this.role,
                        permissions: this.modules.map((module) => ({
                            moduleID: module.module_id,
                            ...module.permissions,
                        })),
                    },
                });
                if (response.status === "success") {
                    this.cancelBtn();
                    toast.success(alert_message);
                }
            } catch (error) {
                console.error("Failed to save role:", error.message);
                toast.warn("Failed to save role:", error.message);
            }
        },
        loadRoleForEdit(roleId) {
            this.showRolesListing = false;
            this.isEditMode = true;
            this.fetchRoleDetails(roleId);
        },
        async fetchRoleDetails(roleId) {
            try {
                const response = await this.$axios.get(`/client/roles/${roleId}`);
                if (response.status === "success") {
                    const roleData = response.data;
                    this.role = {
                        id: roleData.id,
                        roleName: roleData.role_name,
                        roleCode: roleData.role_unique_code,
                        userAccess: [
                            roleData.web_access === "Yes" ? "Web Access" : null,
                            roleData.mobile_access === "Yes" ? "Mobile Access" : null,
                        ].filter(Boolean),
                        status: roleData.status,
                    };
                    this.modules = roleData.user_permission.map(permission => ({
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
                }
            } catch (error) {
                console.error("Failed to load role details:", error.message);
            }
        },
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
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.delete(`/client/roles/${roleId}`)
                        .then((response) => {
                            Swal.fire(
                                'Deleted!',
                                response.message || 'User Role has been deleted.',
                                'success'
                            );
                            this.reloadTable();
                        })
                        .catch((error) => {
                            Swal.fire('Error', error.message || 'Failed to delete user role', 'error');
                        });
                }
            });
        },
        openAddRoleForm() {
            this.showRolesListing = false;
            this.isEditMode = false;
            this.role = { id: null, roleName: "", roleCode: "", userAccess: [], status: "Active" };
            this.errors = {};
            this.fetchModules();
        },
        cancelBtn() {
            this.showRolesListing = true;
            this.isEditMode = false;
            this.role = { id: null, roleName: "", roleCode: "", userAccess: [], status: "Active" };
            this.errors = {};
        },
        reloadTable() {
            this.$refs.dataTableComponent.reloadDataTable();
        },
    },
    mounted() {
        this.fetchModules();
    }
};
</script>
<style scoped >
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

.table th,
.table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

.form-check {
  margin-bottom: 10px;
}

.form-check-input {
  margin-right: 5px;
}

.text-right {
  text-align: right;
}
</style>

