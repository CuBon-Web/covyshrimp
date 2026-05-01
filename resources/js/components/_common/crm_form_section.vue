<template>
  <section class="crm-form-section">
    <header v-if="title" class="crm-form-section-title">{{ title }}</header>
    <slot></slot>
  </section>
</template>

<script>
export default {
  name: "crm-form-section",
  props: {
    title: {
      type: String,
      default: ""
    }
  }
};
</script>

<style scoped>
.crm-form-section-title {
  font-weight: 600;
  color: #1e2b57;
  margin-bottom: 10px;
}
</style>
