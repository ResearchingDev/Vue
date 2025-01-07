<template>
    <Breadcrumbs title="Users" main="Manage" />
    <div class="container-fluid ">
        <div class="clients">
            <div class="row">
                <tableList />
            </div>
        </div>
    </div>
</template>

<script>
import tableList from './tableList.vue';

export default {
    name: 'clients',
    components: {
        tableList
    },

}
</script>
