<template>
  <div class="wrapper crm-dashboard-shell" :class="{ 'nav-open': $sidebar.showSidebar }">
    <side-bar :background-color="sidebarBackground">
      <template slot-scope="props" slot="links">
        <user-menu></user-menu>
        <sidebar-item
          v-if="canAccessPath('/')"
          :link="{
            name: 'Dashboard',
            icon: 'now-ui-icons design_app',
            path: '/',
          }"
        >
        </sidebar-item>
        <sidebar-item v-for="(i, key) in filteredSidebar" :key="'side'+key"
          :link="{
            name: i.name,
            icon: i.icon,
          }"
        >
          <sidebar-item v-for="(item, key) in i.sub" :key="'sub'+key"
            :link="{ name: item.name, path: item.path }"
          ></sidebar-item>
        </sidebar-item>

      </template>
    </side-bar>
    <div class="main-panel">
      <header class="crm-topbar">
        <button class="crm-menu-toggle" type="button" @click="toggleSidebarVisibility">
          <i class="now-ui-icons text_align-center"></i>
        </button>
        <div>
          <p class="crm-topbar-subtitle">CRM Admin</p>
          <h1 class="crm-topbar-title">{{ pageTitle }}</h1>
        </div>
      </header>
      <sidebar-share
        :color.sync="sidebarBackground"
        :fixed-navbar.sync="fixedNavbar"
        :sidebarMini.sync="sidebarMini"
        style="cursor: pointer"
      >
      </sidebar-share>

      <div
        :class="{ content: !$route.meta.hideContent }"
        @click="toggleSidebar"
      >
        <zoom-center-transition :duration="200" mode="out-in">
          <!-- your content here -->
          <router-view></router-view>
        </zoom-center-transition>
      </div>
      <content-footer v-if="!$route.meta.hideFooter"></content-footer>
    </div>
  </div>
</template>
<script>
import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";

function hasElement(className) {
  return document.getElementsByClassName(className).length > 0;
}

function initScrollbar(className) {
  if (hasElement(className)) {
    new PerfectScrollbar(`.${className}`);
  } else {
    // try to init it later in case this component is loaded async
    setTimeout(() => {
      initScrollbar(className);
    }, 100);
  }
}

import ContentFooter from "../layouts/dashboard/ContentFooter.vue";
import UserMenu from "../layouts/dashboard/Extra/UserMenu.vue";
import { SlideYDownTransition, ZoomCenterTransition } from "vue2-transitions";
import Vuex from "vuex";
import SidebarShare from "../layouts/dashboard/Extra/SidebarSharePlugin";

export default {
  components: {
    ContentFooter,
    UserMenu,
    ZoomCenterTransition,
    SidebarShare,
  },
  data() {
    return {
      sidebarBackground: "black",
      fixedNavbar: false,
      sidebarMini: false,
      objSidebar: [
        {
          icon: "mdi mdi-crosshairs-gps menu-icon",
          name: "Sản phẩm",
          route_name: "",
          sub: [
            {
              name: "Danh sách sản phẩm",
              path: "/product",
            },
            {
              name: "Danh mục chính",
              path: "/product/category",
            },
            {
              name: "Danh mục cấp 1",
              path: "/product/type",
            }
          ],
        },
        {
          icon: "mdi mdi-newspaper menu-icon",
          name: "Dịch vụ",
          route_name: "",
          sub: [
            {
              name: "Danh sách ",
              path: "/service",
            },
            {
              name: "Danh mục ",
              path: "/service/category",
            }
          ],
        },
        {
          icon: "mdi mdi-newspaper menu-icon",
          name: "Dự Án",
          route_name: "",
          sub: [
            {
              name: "Danh sách ",
              path: "/project",
            }
          ],
        },
        {
          icon: "mdi mdi-newspaper menu-icon",
          name: "Trang nội dung",
          route_name: "",
          sub: [
            {
              name: "Danh sách ",
              path: "/pagecontent",
            }
          ],
        },
        {
          icon: "mdi mdi-newspaper menu-icon",
          name: "Quản lý bài viết",
          route_name: "",
          sub: [
            {
              name: "Danh sách bài viết",
              path: "/blogs",
            },
            {
              name: "Danh mục bài viết",
              path: "/blog/category",
            },
            {
              name: "Loại bài viết",
              path: "/blog/type",
            },
          ],
        },
        {
          icon: "mdi mdi-file-image menu-icon",
          name: "Website",
          route_name: "",
          sub: [
            {
              name: "Quản lý banner",
              path: "/banner",
            },
            {
              name: "Quản lý quảng cáo",
              path: "/bannerads",
            },
            {
              name: "Quản lý đối tác",
              path: "/partner",
            },
            {
              name: "Cài đặt chung",
              path: "/setting",
            },
             {
              name: "Quản lý text cứng",
              path: "/language/keyword",
            },
          ],
        },
        {
          icon: "mdi mdi-shopping-music menu-icon",
          name: "Quản lý đơn hàng",
          route_name: "",
          sub: [
            {
              name: "Tất cả đơn hàng",
              path: "/bill",
            },
          ],
        },
        // {
        //   icon: "mdi mdi-shopping-music menu-icon",
        //   name: "Quản lý khuyến mãi",
        //   route_name: "",
        //   sub: [
        //     {
        //       name: "Danh sách",
        //       path: "/promotion",
        //     }
        //   ],
        // },
        {
          icon: "mdi mdi-shopping-music menu-icon",
          name: "Quản lý tin nhắn liên hệ",
          route_name: "",
          sub: [
            {
              name: "Danh sách",
              path: "/messcontact",
            }
          ],
        },
        {
          icon: "mdi mdi-newspaper menu-icon",
          name: "Quản lý Review",
          route_name: "",
          sub: [
            {
              name: "Danh sách dịch vụ",
              path: "/reviewCus",
            }
          ],
        },
        {
          icon: "mdi mdi-account-cog menu-icon",
          name: "Quản trị hệ thống",
          route_name: "",
          sub: [
            {
              name: "Thông tin tài khoản",
              path: "/account/profile",
            },
            {
              name: "Tài khoản quản trị",
              path: "/account/admin-users",
            },
            {
              name: "Role & Permission",
              path: "/account/rbac",
            }
          ],
        },
      ],
    };
  },
  methods: {
    inferRequiredPermission(path) {
      if (!path) return null;
      if (path.startsWith("/language")) return "language.view";
      if (path.startsWith("/product")) return "product.view";
      if (path.startsWith("/blog") || path.startsWith("/blogs")) return "blog.view";
      if (path.startsWith("/bill")) return "bill.view";
      if (path.startsWith("/service")) return "service.view";
      if (path.startsWith("/project")) return "project.view";
      if (path.startsWith("/solution")) return "solution.view";
      if (path.startsWith("/pagecontent")) return "pagecontent.view";
      if (path.startsWith("/customer")) return "customer.view";
      if (path.startsWith("/promotion")) return "promotion.view";
      if (
        path.startsWith("/banner") ||
        path.startsWith("/bannerslogan") ||
        path.startsWith("/partner") ||
        path.startsWith("/setting") ||
        path.startsWith("/founder") ||
        path.startsWith("/prize") ||
        path.startsWith("/video") ||
        path.startsWith("/albumAffter")
      ) {
        return "bannerads.view";
      }
      if (path.startsWith("/reviewCus")) return "review.view";
      if (path.startsWith("/messcontact")) return "message.view";
      if (path.startsWith("/tag")) return "tag.view";
      if (path.startsWith("/account/rbac") || path.startsWith("/account/admin-users")) return "rbac.manage";
      if (path === "/") return "dashboard.view";
      return null;
    },
    hasPermissionWithManageFallback(requiredPermission) {
      if (!requiredPermission) {
        return true;
      }
      if (this.permissionSlugs.includes(requiredPermission)) {
        return true;
      }
      if (
        requiredPermission.endsWith(".view") ||
        requiredPermission.endsWith(".create") ||
        requiredPermission.endsWith(".update") ||
        requiredPermission.endsWith(".delete")
      ) {
        const prefix = requiredPermission.split(".").slice(0, -1).join(".");
        return this.permissionSlugs.includes(prefix + ".manage");
      }
      return false;
    },
    canAccessPath(path) {
      if (this.isRootUser) {
        return true;
      }
      const requiredPermission = this.inferRequiredPermission(path);
      return this.hasPermissionWithManageFallback(requiredPermission);
    },
    toggleSidebarVisibility() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    initScrollbar() {
      let isWindows = navigator.platform.startsWith("Win");
      if (isWindows) {
        initScrollbar("sidenav");
      }
    },
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    },
    minimizeSidebar() {
      if (this.$sidebar) {
        this.$sidebar.toggleMinimize();
        let text = this.$sidebar.isMinimized ? "activated" : "deactivated";
        this.$notify({ type: "info", message: `Sidebar mini ${text}...` });
      }
    },
  },
  mounted() {
    let docClasses = document.body.classList;
    let isWindows = navigator.platform.startsWith("Win");
    if (isWindows) {
      // if we are on windows OS we activate the perfectScrollbar function
      initScrollbar("sidebar");
      initScrollbar("sidebar-wrapper");

      docClasses.add("perfect-scrollbar-on");
    } else {
      docClasses.add("perfect-scrollbar-off");
    }
  },
  computed: {
    permissionSlugs() {
      return this.$store.getters.permissionSlugs || [];
    },
    currentUser() {
      return this.$store.getters.currentUser || {};
    },
    isRootUser() {
      const user = this.currentUser || {};
      return Boolean(
        user &&
          (user.role === "super_admin" ||
            Number(user.id) === 1 ||
            Number(user.type) === 1)
      );
    },
    filteredSidebar() {
      if (this.isRootUser) {
        return this.objSidebar;
      }
      return this.objSidebar
        .map((group) => {
          const visibleSub = (group.sub || []).filter((item) =>
            this.canAccessPath(item.path)
          );
          return { ...group, sub: visibleSub };
        })
        .filter((group) => group.sub && group.sub.length > 0);
    },
    pageTitle() {
      if (this.$route.meta && this.$route.meta.title) {
        return this.$route.meta.title;
      }
      return this.$route.name || "Dashboard";
    }
  },
  watch: {
    sidebarMini() {
      this.minimizeSidebar();
    },
  },
};
</script>
<style lang="scss">
$scaleSize: 0.95;
@keyframes zoomIn95 {
  from {
    opacity: 0;
    transform: scale3d($scaleSize, $scaleSize, $scaleSize);
  }
  to {
    opacity: 1;
  }
}
.main-panel .zoomIn {
  animation-name: zoomIn95;
}
@keyframes zoomOut95 {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
    transform: scale3d($scaleSize, $scaleSize, $scaleSize);
  }
}
.main-panel .zoomOut {
  animation-name: zoomOut95;
}

.crm-topbar {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 18px 4px;
}

.crm-menu-toggle {
  width: 38px;
  height: 38px;
  border-radius: 12px;
  border: 1px solid #d8dfef;
  background: #fff;
  color: #1e2b57;
  cursor: pointer;
}

.crm-topbar-subtitle {
  margin: 0;
  font-size: 12px;
  color: #6a7490;
}

.crm-topbar-title {
  margin: 0;
  font-size: 22px;
  line-height: 1.2;
  color: #0f1a3d;
}
</style>
