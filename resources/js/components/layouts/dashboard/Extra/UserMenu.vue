<template>
  <div class="user user__menu">
    <div class="photo">
      <img src="/img/placeholder.jpg" alt="avatar" />
    </div>
    <div class="info">
      <a
        data-toggle="collapse"
        :aria-expanded="!isClosed"
        @click.stop="toggleMenu"
        href="#"
      >
        <span>
          {{ title }}
          <b class="caret"></b>
        </span>
      </a>
      <div class="clearfix"></div>
      <div>
        <collapse-transition>
          <ul class="nav user-menu__nav" v-show="!isClosed">
            <slot>
              <li>
                <router-link to="/account/profile" class="twhite">
                  <span class="sidebar-mini-icon">P</span>
                  <span class="sidebar-normal">Thông tin tài khoản</span>
                </router-link>
              </li>
              <li>
                <router-link to="/account/admin-users" class="twhite">
                  <span class="sidebar-mini-icon">A</span>
                  <span class="sidebar-normal">Quản trị viên</span>
                </router-link>
              </li>
              <li>
                <router-link to="/account/rbac" class="twhite">
                  <span class="sidebar-mini-icon">R</span>
                  <span class="sidebar-normal">Role & Permission</span>
                </router-link>
              </li>

              <li>
                <a href="javascript:;" class="twhite" @click="logout" >
                  <span class="sidebar-mini-icon">L</span>
                  <span class="sidebar-normal">Logout</span>
                </a>
              </li>
            </slot>
          </ul>
        </collapse-transition>
      </div>
    </div>
  </div>
</template>
<script>
import { CollapseTransition } from "vue2-transitions";
import {mapActions} from 'vuex'

export default {
  name: "user-menu",
  components: {
    CollapseTransition,
  },
  data() {
    return {
      isClosed: true,
      title: "Tài khoản",
    };
  },
  methods: {
    ...mapActions(['destroyToken','loadings','getNotification','profileUserCms']),
    loadProfile() {
      this.profileUserCms().then((res) => {
        this.title = res && res.name ? res.name : "Tài khoản";
      }).catch(() => {
        this.title = "Tài khoản";
      });
    },
    logout() {
      this.loadings(true);
      this.destroyToken().then(response => {
        this.loadings(false)
        this.$router.push({ name: "login" })
      }).catch(error => {
        this.loadings(false);
        this.$router.push({ name: "login" })
        this.$error('Đăng nhập thất bại');
      })
    },
    toggleMenu() {
      this.isClosed = !this.isClosed;
    }
  },
  mounted() {
    this.loadProfile();
  }
};
</script>
<style>
.user__menu ul.user-menu__nav {
  margin-top: 0;
  padding-top: 20px;
}
a.twhite:hover {
  color: white !important;
}
</style>
