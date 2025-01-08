<template>
    <Breadcrumbs title="User Roles" main="Roles" />
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0 pull-left">Manage User Roles</h4>
            <div class="pull-right">
                <router-link class="link btn btn-success btn-sm" to="/client/roles/add_role"> Add User Role</router-link>
            </div>
        </div>
        <div class="container-fluid">
            <div class="user-profile">
                <div class="row">
                    <table class="table card-table text-center text-nowrap" id="userRolesTable">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Role Name</th>
                                <th class="text-center">Access</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- Data will be populated here by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';
    import Swal from 'sweetalert2';
    import axios from 'axios';

    export default {
        name: 'userProfile',
        components: {
        },
        mounted() {
            const vueInstance = this;
            window.vueInstance = vueInstance;

            // Initialize DataTable after the component is mounted
            $(document).ready(function () {
                const user = localStorage.getItem('User');
                const parsedUser = JSON.parse(user);
                const clientId = parsedUser.client_id;
                $('#userRolesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/client/'+clientId, // Adjusted URL to match the route
                        type: 'GET',
                        data: function (d) {
                        // Pass necessary parameters for pagination, sorting, etc.
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                        }
                    },
                    columns: [
                        {
                            data: null,
                            render: function (data, type, row, meta) {
                                // Calculate serial number based on row index (meta.row) and page number
                                return meta.row + meta.settings._iDisplayStart + 1; // +1 for 1-based index
                            },
                        },
                        { data: 'role_name' },
                        {
                            data: null, // Custom rendering for web and mobile access
                            render: function (data, type, row) {
                                let access = [];
                                if (row.web_access === 'Yes') access.push('Web Access');
                                if (row.mobile_access === 'Yes') access.push('Mobile Access');
                                return access.length > 0 ? access.join(', ') : '-';
                            }
                        },
                        { data: 'status' },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm" onclick="window.vueInstance.editRole(${row.id})"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-danger btn-sm sweet-11" type="button" onclick="window.vueInstance.deleteRole(${row.id})"><i class="fa fa-trash"></i></button>
                                `;
                            },
                            orderable: false,
                            searchable: false,
                        }
                    ]
                });
            });
        },
        methods: {
            // Function to delete the User Role
            editRole(roleId) {
                window.location.href = '/client/roles/edit/' + roleId;
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
                        axios
                            .delete(`/api/client/roles/delete/${roleId}`)
                            .then((response) => {
                                Swal.fire(
                                    'Deleted!',
                                    response.data.message || 'User Role has been deleted successfully.',
                                    'success'
                                );
                                $('#userRolesTable').DataTable().ajax.reload();
                            })
                            .catch((error) => {
                                let errorMessage = error.response?.data?.message || 'There was an error deleting the User Role. Please try again later.';
                                Swal.fire('Error!', errorMessage, 'error');
                                console.error('Error deleting User Role:', error);
                            });
                    }
                });
            },
        }
    }
</script>
