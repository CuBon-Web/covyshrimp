<template>
  <div>
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h4 class="card-title mb-0">Thêm quản trị viên</h4>
          <small class="text-muted">Role gán tại màn Role/Permission</small>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Họ tên</label>
            <vs-input class="w-100" v-model.trim="createForm.name" />
          </div>
          <div class="col-md-4 form-group">
            <label>Email</label>
            <vs-input class="w-100" v-model.trim="createForm.email" />
          </div>
          <div class="col-md-2 form-group">
            <label>Mật khẩu</label>
            <vs-input type="text" class="w-100" v-model.trim="createForm.password" />
          </div>
          <div class="col-md-2 form-group">
            <label>Nhập lại mật khẩu</label>
            <vs-input type="text" class="w-100" v-model.trim="createForm.password_confirmation" />
          </div>
        </div>
        <div class="d-flex align-items-center" style="gap:8px">
          <vs-button color="primary" :disabled="!canCreateUser" @click="createUser">Tạo tài khoản</vs-button>
          <vs-button type="border" @click="generateStrongPassword">Sinh mật khẩu mạnh</vs-button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-0">Danh sách quản trị viên</h4>
          <vs-input class="admin-search" icon="search" placeholder="Tìm theo tên hoặc email..." v-model.trim="keyword" />
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Role hiện có</th>
                <th width="260">Đổi mật khẩu</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in filteredUsers" :key="item.id">
                <td>{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.email }}</td>
                <td>
                  <span v-if="item.roles && item.roles.length">{{ item.roles.map(i => i.name).join(', ') }}</span>
                  <span v-else class="text-muted">Chưa gán role</span>
                </td>
                <td>
                  <div class="d-flex align-items-center" style="gap:6px">
                    <vs-input
                      type="password"
                      class="w-100"
                      v-model="passwordForm[item.id].new_password"
                      placeholder="Mật khẩu mới"
                    />
                    <vs-input
                      type="password"
                      class="w-100"
                      v-model="passwordForm[item.id].new_password_confirmation"
                      placeholder="Nhập lại"
                    />
                    <vs-button
                      size="small"
                      color="warning"
                      :disabled="!canResetPassword(item.id)"
                      @click="resetPassword(item.id)"
                    >Đổi</vs-button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="5" class="text-center text-muted">Chưa có tài khoản quản trị</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "admin-users",
  data() {
    return {
      users: [],
      keyword: "",
      passwordForm: {},
      createForm: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      }
    };
  },
  computed: {
    filteredUsers() {
      const key = (this.keyword || "").toLowerCase();
      if (!key) return this.users;
      return this.users.filter((u) => {
        return (u.name || "").toLowerCase().includes(key) || (u.email || "").toLowerCase().includes(key);
      });
    },
    canCreateUser() {
      return (
        !!this.createForm.name &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.createForm.email || "") &&
        (this.createForm.password || "").length >= 6 &&
        this.createForm.password === this.createForm.password_confirmation
      );
    }
  },
  methods: {
    ...mapActions(["listAdminUsers", "createAdminUser", "resetAdminUserPassword", "loadings"]),
    loadUsers() {
      this.listAdminUsers()
        .then((res) => {
          this.users = Array.isArray(res.data) ? res.data : [];
          this.passwordForm = {};
          this.users.forEach((u) => {
            this.$set(this.passwordForm, u.id, {
              new_password: "",
              new_password_confirmation: ""
            });
          });
        })
        .catch((error) => {
          this.$errorFromApi(error, "Không thể tải danh sách quản trị viên.");
        });
    },
    createUser() {
      if (!this.canCreateUser) {
        this.$error("Vui lòng kiểm tra lại thông tin tài khoản và mật khẩu.");
        return;
      }
      this.loadings(true);
      this.createAdminUser(this.createForm)
        .then(() => {
          this.$success("Tạo tài khoản thành công");
          this.createForm = { name: "", email: "", password: "", password_confirmation: "" };
          this.loadUsers();
        })
        .catch((error) => {
          this.$errorFromApi(error, "Tạo tài khoản thất bại.");
        })
        .finally(() => this.loadings(false));
    },
    generateStrongPassword() {
      const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%";
      let generated = "";
      for (let i = 0; i < 12; i += 1) {
        generated += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      this.createForm.password = generated;
      this.createForm.password_confirmation = generated;
      this.$success("Đã sinh mật khẩu mạnh.");
    },
    canResetPassword(userId) {
      const payload = this.passwordForm[userId] || {};
      return (
        (payload.new_password || "").length >= 6 &&
        payload.new_password === payload.new_password_confirmation
      );
    },
    resetPassword(userId) {
      if (!this.canResetPassword(userId)) {
        this.$error("Mật khẩu mới chưa hợp lệ hoặc chưa khớp.");
        return;
      }
      const payload = this.passwordForm[userId] || {};
      this.resetAdminUserPassword({
        id: userId,
        new_password: payload.new_password,
        new_password_confirmation: payload.new_password_confirmation
      })
        .then(() => {
          this.$success("Đổi mật khẩu thành công");
          this.$set(this.passwordForm, userId, { new_password: "", new_password_confirmation: "" });
        })
        .catch((error) => {
          this.$errorFromApi(error, "Đổi mật khẩu thất bại.");
        });
    }
  },
  mounted() {
    this.loadUsers();
  }
};
</script>
<style scoped>
.admin-search {
  min-width: 280px;
}
</style>
