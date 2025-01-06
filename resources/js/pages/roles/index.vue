<template>
    <Breadcrumbs title="User Roles" main="Roles" />
    <div class="col-md-2 pull-right mb-4" >
        <router-link class="link btn btn-primary mb-4" to="/client/roles"> Add User Roles</router-link>
    </div>
    <div class="container-fluid ">
        <div class="user-profile">
            <div class="row">
                <table class="table card-table table-vcenter text-nowrap" id="userTable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created On</th>
                            <th>Status</th>
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
</template>


<script>
import projects from '../clients/tableList.vue';

export default {
    name: 'userProfile',
    components: {
        projects,
    },
    methods: {
        redirectToRoles() {
        this.$router.replace({ name: 'Roles' });  // Redirect without adding to history
        }
    }

}
</script>
