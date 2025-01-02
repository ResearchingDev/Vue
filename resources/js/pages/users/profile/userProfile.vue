<template>
    <Breadcrumbs title="User Profile" main="Users" />
    <div class="container-fluid ">
        <div class="user-profile">
            <div class="row">
                <projects />
            </div>
        </div>
    </div>
</template>

<script>
import projects from '../profile/projects.vue';

export default {
    name: 'userProfile',
    components: {
        projects
    },

}
</script>
