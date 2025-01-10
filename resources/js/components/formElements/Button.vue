<template>
  <button :class="buttonClass" type="button">
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "Button",
  props: {
    buttonClass: {
      type: String,
      default: "btn btn-success btn-sm btn-block btn-mail w-100",
    },
  },
};
</script>
