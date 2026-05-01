<template>
  <div class="isolated-login-page">
    <video class="isolated-login-video" autoplay muted loop playsinline preload="metadata" poster="/img/bg13.jpg">
      <source src="https://cdn.coverr.co/videos/coverr-moving-through-the-data-center-1561764229955?download=1080p" type="video/mp4" />
    </video>
    <div class="isolated-login-overlay"></div>
    <div class="isolated-login-grid"></div>

    <div class="isolated-login-shell">
      <section class="isolated-login-brand">
        <p class="isolated-login-chip">Tuấn Anh Developer</p>
        <h1>Welcome to Tuấn Anh Developer Admin Panel</h1>
        <p class="isolated-login-description">
          Đăng nhập để quản lý nội dung, người dùng và dữ liệu vận hành với giao diện hiện đại, bảo mật và tối ưu tốc độ.
        </p>
        <ul class="isolated-login-features">
          <li>Bảo mật tài khoản đa lớp</li>
          <li>Theo dõi dữ liệu theo thời gian thực</li>
          <li>Quản trị nội dung tập trung</li>
        </ul>
      </section>

      <section class="isolated-login-card">
        <h2>Đăng nhập quản trị</h2>
        <p>Chỉ dành cho tài khoản được phân quyền.</p>

        <form @submit.prevent="login" autocomplete="on">
          <label for="login-username">Tên đăng nhập</label>
          <input
            id="login-username"
            v-model.trim="objLogin.name"
            type="text"
            name="username"
            autocomplete="username"
            placeholder="Nhập tên đăng nhập"
            required
          />

          <label for="login-password">Mật khẩu</label>
          <input
            id="login-password"
            v-model="objLogin.password"
            type="password"
            name="password"
            autocomplete="current-password"
            placeholder="Nhập mật khẩu"
            required
          />

          <button type="submit">Đăng nhập</button>
        </form>

        <small>Kết nối được mã hóa và giám sát an toàn.</small>
      </section>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      objLogin: {
        name: "",
        password: "",
      },
    };
  },
  methods: {
    ...mapActions(["retrieveToken", "loadings"]),
    login() {
      this.loadings(true);
      this.retrieveToken(this.objLogin)
        .then(() => {
          this.$router.push({ name: "listProduct" });
          window.location.reload();
        })
        .catch((error) => {
          this.$errorFromApi(error, "Đăng nhập thất bại");
        })
        .finally(() => this.loadings(false));
    },
  },
  created() {},
};
</script>

<style scoped>
.isolated-login-page,
.isolated-login-page * {
  box-sizing: border-box;
}

.isolated-login-page {
  position: relative;
  min-height: 100vh;
  overflow: hidden;
  isolation: isolate;
  background: radial-gradient(circle at top right, #2a57bb 0%, #0a1023 48%, #05070f 100%);
  font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
}

.isolated-login-video {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.3;
  z-index: 0;
}

.isolated-login-overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  background:
    radial-gradient(circle at 15% 25%, rgba(58, 113, 255, 0.4), transparent 40%),
    radial-gradient(circle at 80% 10%, rgba(0, 246, 255, 0.2), transparent 35%),
    linear-gradient(145deg, rgba(6, 11, 26, 0.85) 20%, rgba(10, 18, 40, 0.92) 70%);
  pointer-events: none;
}

.isolated-login-grid {
  position: absolute;
  inset: 0;
  z-index: 1;
  background-image:
    linear-gradient(rgba(71, 117, 255, 0.12) 1px, transparent 1px),
    linear-gradient(90deg, rgba(71, 117, 255, 0.12) 1px, transparent 1px);
  background-size: 46px 46px;
  mask-image: radial-gradient(circle at center, black 45%, transparent 90%);
  animation: moveGrid 18s linear infinite;
  pointer-events: none;
}

.isolated-login-shell {
  position: relative;
  z-index: 2;
  min-height: inherit;
  width: min(1180px, 92vw);
  margin: 0 auto;
  padding: 40px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 48px;
}

.isolated-login-brand {
  flex: 1 1 56%;
  color: #eaf1ff;
  max-width: 580px;
  animation: fadeInUp 0.7s ease both;
}

.isolated-login-chip {
  display: inline-block;
  margin: 0 0 16px;
  padding: 6px 14px;
  border-radius: 999px;
  border: 1px solid rgba(120, 178, 255, 0.48);
  background: rgba(95, 153, 255, 0.22);
  color: #eaf1ff;
  font-size: 12px;
  letter-spacing: 0.12em;
  font-weight: 700;
}

.isolated-login-brand h1 {
  margin: 0 0 12px;
  font-size: clamp(36px, 5vw, 52px);
  line-height: 1.15;
  color: #ffffff;
  white-space: normal;
  overflow-wrap: anywhere;
}

.isolated-login-description {
  margin: 0 0 22px;
  font-size: 16px;
  line-height: 1.7;
  color: #ccdafd;
  max-width: 520px;
}

.isolated-login-features {
  margin: 0;
  padding: 0;
  list-style: none;
}

.isolated-login-features li {
  position: relative;
  margin: 0 0 10px;
  padding-left: 18px;
  color: #d9e5ff;
  font-size: 15px;
}

.isolated-login-features li::before {
  content: "";
  position: absolute;
  left: 0;
  top: 8px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #62d5ff;
  box-shadow: 0 0 10px #62d5ff;
}

.isolated-login-card {
  flex: 1 1 44%;
  width: min(440px, 100%);
  padding: 28px;
  border-radius: 20px;
  border: 1px solid rgba(154, 194, 255, 0.28);
  background: rgba(10, 17, 35, 0.72);
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(12px);
  color: #f4f7ff;
  animation: fadeInUp 0.85s ease both;
}

.isolated-login-card h2 {
  margin: 0 0 8px;
  color: #fff;
  font-size: 30px;
  font-weight: 700;
}

.isolated-login-card p {
  margin: 0 0 18px;
  color: #b9c8ef;
  font-size: 14px;
}

.isolated-login-card form {
  width: 100%;
}

.isolated-login-card label {
  display: block;
  margin-bottom: 8px;
  color: #dce8ff;
  font-size: 13px;
  font-weight: 600;
}

.isolated-login-card input {
  width: 100%;
  margin-bottom: 14px;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid rgba(145, 175, 255, 0.45);
  background: rgba(14, 24, 50, 0.92);
  color: #ffffff;
  outline: none;
  font-size: 15px;
  line-height: 1.4;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.isolated-login-card input:focus {
  border-color: #5ed0ff;
  box-shadow: 0 0 0 3px rgba(73, 183, 255, 0.2);
}

.isolated-login-card button {
  width: 100%;
  margin-top: 6px;
  border: none;
  border-radius: 999px;
  padding: 12px 16px;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  cursor: pointer;
  background: linear-gradient(90deg, #3e7dff 0%, #2ac7ff 100%);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.isolated-login-card button:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 24px rgba(42, 199, 255, 0.3);
}

.isolated-login-card small {
  display: block;
  margin-top: 14px;
  color: #b4c4ea;
  font-size: 12px;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes moveGrid {
  from {
    transform: translate3d(0, 0, 0);
  }
  to {
    transform: translate3d(46px, 46px, 0);
  }
}

@media (max-width: 991px) {
  .isolated-login-shell {
    flex-direction: column;
    justify-content: flex-start;
    gap: 20px;
    padding: 22px 0;
  }

  .isolated-login-brand {
    display: none;
  }

  .isolated-login-card {
    width: min(500px, 100%);
  }
}

@media (max-width: 575px) {
  .isolated-login-card {
    border-radius: 16px;
    padding: 20px 16px;
  }

  .isolated-login-card h2 {
    font-size: 24px;
  }
}
</style>
