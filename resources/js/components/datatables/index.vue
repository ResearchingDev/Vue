<template>
    <div>
        <table ref="datatable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th v-for="(column, index) in columns" :key="index">{{ column.label }}</th>
                </tr>
            </thead>
        </table>
    </div>
</template>

<script>
import router from '../../router'; // Import Vue Router for redirecting
import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-dt/css/dataTables.dataTables.css';
export default {
    name: 'DataTableComponent',
    props: {
        columns: {
            type: Array,
            required: true,
        },
        apiUrl: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            datatable: null,
        };
    },
    methods: {
        initializeDataTable() {
            const self = this;
            const token = localStorage.getItem('token'); // Retrieve the token from localStorage
            this.datatable = $(this.$refs.datatable).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: self.apiUrl,
                    type: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,  // Include the token in the request headers
                    },
                    data: function (d) {
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                        d.search = d.search.value;
                        d.order = d.order;
                    },
                    error: function (xhr, error, thrown) {
                        // You can also handle specific error codes here
                        if (xhr.status === 401) {
                            // Handle Unauthorized (e.g., user session expired)
                            // Redirect to login page or perform other actions
                            router.push('/login');
                        } else if (xhr.status === 500) {
                            // Handle server errors
                            alert('Internal Server Error. Please try again later.');
                        }
                    }
                },
                columns: [
                    {
                        data: null, // S.No column
                        render: (data, type, row, meta) => {
                            return meta.row + 1 + meta.settings._iDisplayStart; // Calculate S.No
                        },
                        orderable: false, // Disable sorting for S.No column
                    },
                    ...self.columns.map((col) => ({
                        data: col.field,
                        render: col.customRender
                            ? (data, type, row) => col.customRender(row)
                            : undefined,
                    })),
                ],
            });
            // Add event listeners for edit and delete buttons
              $(this.$refs.datatable).on('click', '.edit-btn', function () {
                const editId = $(this).data('edit-id');
                self.$emit('edit', editId); // Emit an event to the parent
              });

              $(this.$refs.datatable).on('click', '.delete-btn', function () {
                const deleteId = $(this).data('delete-id');
                self.$emit('delete', deleteId); // Emit an event to the parent
              });
        },
        reloadDataTable() {
          if (this.datatable) {
            this.datatable.ajax.reload();
          }
        },
    },
    mounted() {
        this.initializeDataTable();
    },
    beforeDestroy() {
        if (this.datatable) {
            this.datatable.destroy(true);
        }
    },
};
</script>

<style>
/* Include DataTables styling */
.display {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}
</style>