<template>
  <el-dialog
    :visible.sync="localVisible"
    width="420px"
    :close-on-click-modal="false"
    @close="cancel"
  >
    <div class="crm-confirm-dialog">
      <h4>{{ title }}</h4>
      <p>{{ message }}</p>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="cancel">{{ cancelText }}</el-button>
      <el-button type="primary" @click="confirm">{{ confirmText }}</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: "crm-confirm-dialog",
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: "Xác nhận"
    },
    message: {
      type: String,
      default: "Bạn có chắc chắn muốn thực hiện thao tác này?"
    },
    cancelText: {
      type: String,
      default: "Hủy"
    },
    confirmText: {
      type: String,
      default: "Đồng ý"
    }
  },
  computed: {
    localVisible: {
      get() {
        return this.visible;
      },
      set(value) {
        this.$emit("update:visible", value);
      }
    }
  },
  methods: {
    cancel() {
      this.$emit("update:visible", false);
      this.$emit("cancel");
    },
    confirm() {
      this.$emit("confirm");
      this.$emit("update:visible", false);
    }
  }
};
</script>

<style scoped>
.crm-confirm-dialog h4 {
  margin: 0 0 6px;
  color: #1e2b57;
}

.crm-confirm-dialog p {
  margin: 0;
  color: #5f6b84;
}
</style>
