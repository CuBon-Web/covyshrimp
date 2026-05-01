<template>
  <div class="row profile-page">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h4 class="card-title mb-0">Thông tin tài khoản</h4>
            <span class="badge badge-light">Tài khoản đang đăng nhập</span>
          </div>
          <p class="text-muted mb-3">Cập nhật chính xác email để nhận thông báo hệ thống.</p>
          <div class="form-group">
            <label>Họ tên</label>
            <vs-input class="w-100" v-model.trim="profile.name" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <vs-input class="w-100" v-model.trim="profile.email" />
            <small v-if="profile.email && !isValidEmail(profile.email)" class="text-danger">Email chưa đúng định dạng.</small>
          </div>
          <div class="form-group">
            <label>Số điện thoại</label>
            <vs-input class="w-100" v-model.trim="profile.phone" />
          </div>
          <div class="form-group">
            <label>Địa chỉ</label>
            <vs-input class="w-100" v-model.trim="profile.address" />
          </div>
          <div class="d-flex" style="gap:8px">
            <vs-button color="primary" :disabled="!canSaveProfile" @click="saveProfile">Lưu thông tin</vs-button>
            <vs-button type="border" @click="loadProfile">Khôi phục</vs-button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Đổi mật khẩu</h4>
          <p class="text-muted mb-3">Mật khẩu mới nên có cả chữ hoa, chữ thường, số và ký tự đặc biệt.</p>
          <div class="form-group">
            <label>Mật khẩu hiện tại</label>
            <vs-input type="password" class="w-100" v-model="password.current_password" />
          </div>
          <div class="form-group">
            <label>Mật khẩu mới</label>
            <vs-input type="password" class="w-100" v-model="password.new_password" />
          </div>
          <div class="form-group">
            <label>Nhập lại mật khẩu mới</label>
            <vs-input type="password" class="w-100" v-model="password.new_password_confirmation" />
          </div>
          <div class="d-flex align-items-center" style="gap:8px">
            <vs-button color="success" :disabled="!canChangePassword" @click="changePassword">Đổi mật khẩu</vs-button>
            <small :class="passwordHintClass">{{ passwordHint }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "profile-page",
  data() {
    return {
      initialProfile: {
        name: "",
        email: "",
        phone: "",
        address: ""
      },
      profile: {
        name: "",
        email: "",
        phone: "",
        address: ""
      },
      password: {
        current_password: "",
        new_password: "",
        new_password_confirmation: ""
      }
    };
  },
  computed: {
    canSaveProfile() {
      return !!this.profile.name && this.isValidEmail(this.profile.email || "");
    },
    canChangePassword() {
      return (
        !!this.password.current_password &&
        this.password.new_password.length >= 6 &&
        this.password.new_password_confirmation.length >= 6 &&
        this.password.new_password === this.password.new_password_confirmation
      );
    },
    passwordHint() {
      if (!this.password.new_password && !this.password.new_password_confirmation) {
        return "Tối thiểu 6 ký tự.";
      }
      if (this.password.new_password.length < 6) {
        return "Mật khẩu mới phải từ 6 ký tự.";
      }
      if (this.password.new_password !== this.password.new_password_confirmation) {
        return "Mật khẩu xác nhận chưa khớp.";
      }
      return "Mật khẩu hợp lệ.";
    },
    passwordHintClass() {
      return this.canChangePassword ? "text-success" : "text-warning";
    }
  },
  methods: {
    ...mapActions(["profileUserCms", "updateProfileCms", "changePasswordCms", "loadings"]),
    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email || "");
    },
    loadProfile() {
      this.profileUserCms().then((res) => {
        this.profile = {
          name: res.name || "",
          email: res.email || "",
          phone: res.phone || "",
          address: res.address || ""
        };
        this.initialProfile = { ...this.profile };
      });
    },
    saveProfile() {
      this.loadings(true);
      this.updateProfileCms(this.profile)
        .then(() => {
          this.$success("Cập nhật thông tin thành công");
        })
        .catch((error) => {
          this.$errorFromApi(error, "Cập nhật thông tin thất bại.");
        })
        .finally(() => this.loadings(false));
    },
    changePassword() {
      this.loadings(true);
      this.changePasswordCms(this.password)
        .then(() => {
          this.$success("Đổi mật khẩu thành công");
          this.password = { current_password: "", new_password: "", new_password_confirmation: "" };
        })
        .catch((error) => {
          this.$errorFromApi(error, "Đổi mật khẩu thất bại.");
        })
        .finally(() => this.loadings(false));
    }
  },
  mounted() {
    this.loadProfile();
  }
};
</script>
