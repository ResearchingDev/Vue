<template>
  <div class="form-group">
    <select :value="modelValue" :class="selectClass" :required="required"
      @change="$emit('update:modelValue', $event.target.value)">
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.text }}
      </option>
    </select>
  </div>
</template>

<script>
export default {
  name: "CommonSelect",
  props: {
    modelValue: {
      type: String,
      required: true,
    },
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
    selectClass: {
      type: String,
      default: "form-control",
    },
    required: {
      type: Boolean,
      default: false,
    },
  },
};
</script>