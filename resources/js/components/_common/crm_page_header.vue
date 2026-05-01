<template>
  <div class="crm-page-head">
    <div>
      <h4 class="card-title">{{ title }}</h4>
      <p v-if="description" class="card-description">{{ description }}</p>
    </div>
    <div class="crm-page-head-actions">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "crm-page-header",
  props: {
    title: {
      type: String,
      default: ""
    },
    description: {
      type: String,
      default: ""
    }
  }
};
</script>
