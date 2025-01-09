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
                        <DataTableComponent
                                ref="dataTableComponent"
                                :columns="columns"
                                apiUrl="/api/client/roles/"
                                @edit="loadRoleForEdit"
                                @delete="deleteRole"
                            />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <userPermission />
</template>

<script>
    import $ from 'jquery';
    import Swal from 'sweetalert2';
    import DataTableComponent from '../../components/datatables/index.vue';
    import userPermission from './userPermission.vue';

    export default {
        name: 'userProfile',
        components: {
            DataTableComponent,
            userPermission
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
                                <button class="btn btn-primary btn-sm edit-btn" data-edit-id="${row.id}" @click="$emit('edit', ${row.id})">
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
                    status: ''
                },
                modules: []
            };
        },
        mounted(){
            $(document).on('click', '.add_role', function() {
                $('#rolesListing').css('display', 'none');
                $('#addRoleForm').css('display', 'block');
            });
            $(document).on('click', '.cancelEdit', function() {
                $('#rolesListing').css('display', 'block');
                $('#addRoleForm').css('display', 'none');
            });
        },
        methods: {
            async loadRoleForEdit(roleId) {
                console.log('Editing role ID:', roleId);
                $('#rolesListing').css('display', 'none');
                this.isEditMode = true;
                try {
                    const response = await this.$axios.get(`/client/roles/${roleId}`);
                    console.log(response)
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
                        this.$axios.delete(`/client/roles/delete/${roleId}`)
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
