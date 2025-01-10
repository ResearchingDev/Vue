<template>
  <label :class="labelClass">
    <slot></slot>
  </label>
</template>

<script>
export default {
  name: "Label",
  props: {
    labelClass: {
      type: String,
      default: "",
    },
  },
};
</script>
