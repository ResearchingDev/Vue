<template>
    <Breadcrumbs title="Edit Profile" main="Users" />
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <editProfile />
            </div>
        </div>
</div>
</template>
<script>
import editProfile from "./editProfile.vue"
export default {
    components: {
        editProfile
    }
}
</script>
