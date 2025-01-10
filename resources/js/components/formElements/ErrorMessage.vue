<template>
    <span v-if="errorMessage" :class="errorClass">{{ errorMessage }}</span>
</template>

<script>
export default {
    name: "ErrorMessage",
    props: {
        errorMessage: {
            type: String,
            required: false,
            default: "",
        },
        errorClass: {
            type: String,
            default: "text-danger",
        },
    },
};
</script>