<template>
   
</template>
<script>
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
       
    },
};
</script>
