<template>
    <Breadcrumbs title="Clients" main="Manage" />
    <div class="container-fluid ">
        <div class="clients">
            <div class="row">
                <div class="col-md-12">
                      <div class="card">
                        <div class="card-header">
                          <h4 class="card-title mb-0">Client List</h4>
                          <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
                            <userModals ref="UserModal" @reloadTable="reloadDataTable"/>
                          </div>
                          <span id="message"  class="text-success d-block"></span>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <DataTableComponent
                              ref="dataTableComponent"
                              :columns="columns"
                              apiUrl="/api/client/users/list"
                               @edit="openEditModal"
                              @delete="deleteUser"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import userModals from './Modals.vue';
import DataTableComponent from '../../components/datatables/index.vue';
import Swal from 'sweetalert2';
export default {
    name: 'clients',
    components: {
        userModals,
        DataTableComponent
    },
    data() {
      return {
        // Define column headers and the data to be displayed in the table
        columns: [
          { label: 'First Name', field: 'first_name' },
          { label: 'Role Type', field: 'role_name' },
          { label: 'Email', field: 'email' },
          { label: 'Created On', field: 'created_at' },
          { label: 'Status', field: 'status' },
          { label: 'Action', field: 'action', 
          customRender: (row) => {
            return `
                <button class="btn btn-primary btn-sm edit-btn" data-edit-id="${row.id}">
                  <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-danger btn-sm delete-btn" data-delete-id="${row.id}">
                  <i class="fa fa-trash"></i>
                </button>`;
          }},
        ],
        users: []
      };
    },
    methods: {
      // Function to open the edit modal and load the user data
      openEditModal(id) {
        this.$axios.get(`/client/users/${id}`)
          .then(response => {
            this.$refs.UserModal.openModal(response); // Pass the client data to the modal
          })
          .catch(error => console.error('Error fetching client data:', error));
      },
  
      // Function to delete the client
      deleteUser(id) {
        Swal.fire({
          title: 'Are you sure?',
          text: `You won't be able to revert this! Do you want to delete client?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancel',
          cancelButtonColor: '#3085d6',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            this.$axios
              .delete(`/client/users/${id}`)
              .then(() => {
                Swal.fire(
                  'Deleted!',
                  'Client has been deleted successfully.',
                  'success'
                );
                this.reloadDataTable();
              })
              .catch((error) => {
                Swal.fire(
                  'Error!',
                  'There was an error deleting the client. Please try again later.',
                  'error'
                );
                console.error('Error deleting client:', error);
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
