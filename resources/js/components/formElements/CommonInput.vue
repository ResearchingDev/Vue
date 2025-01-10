<template>
  <div class="form-group">
    <input :value="modelValue" :class="inputClass" :type="type" :placeholder="placeholder" :required="required"
      @input="$emit('update:modelValue', $event.target.value)" />
  </div>
</template>

<script>
export default {
  name: "CommonInput",
  props: {
    modelValue: {
      type: [String, Number],
      required: true,
    },
    type: {
      type: String,
      default: "text",
    },
    placeholder: {
      type: String,
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
    inputClass: {
      type: String,
      default: "",
    },
  },
};
</script>