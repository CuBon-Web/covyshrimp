<template>
  <div>
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="card-title mb-1">Cài đặt Role dễ dùng</h4>
            <p class="text-muted mb-0">Mỗi module chọn 1 mức quyền: Không có quyền, Xem, Biên tập, Toàn quyền.</p>
          </div>
          <vs-button type="border" size="small" @click="showPermissionLegend = !showPermissionLegend">
            {{ showPermissionLegend ? "Ẩn danh sách permission" : "Xem danh sách permission" }}
          </vs-button>
        </div>
        <div v-if="showPermissionLegend" class="permission-legend mt-3">
          <div v-for="group in permissionGroups" :key="`fixed-${group.module}`" class="permission-group-card">
            <h6 class="permission-group-title">{{ moduleLabel(group.module) }}</h6>
            <div class="permission-fixed-list">
              <span class="permission-chip" v-for="p in group.items" :key="`fixed-chip-${p.id}`">{{ p.slug }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <h4 class="card-title">Tạo Role mới</h4>
        <div class="row">
          <div class="col-md-3 form-group">
            <label>Tên role</label>
            <vs-input class="w-100" v-model.trim="roleForm.name" />
          </div>
          <div class="col-md-3 form-group">
            <label>Slug role</label>
            <vs-input class="w-100" v-model.trim="roleForm.slug" placeholder="content-manager" />
          </div>
          <div class="col-md-3 form-group">
            <label>Mẫu nhanh</label>
            <vs-select class="w-100" v-model="createPresetType" @change="applyRolePreset(createPresetType)">
              <vs-select-item value="custom" text="Tuỳ chỉnh" />
              <vs-select-item value="viewer" text="Viewer" />
              <vs-select-item value="editor" text="Editor" />
              <vs-select-item value="operator" text="Operator" />
            </vs-select>
          </div>
          <div class="col-md-3 form-group">
            <label>Tổng quyền đã chọn</label>
            <vs-input class="w-100" :value="selectedPermissionPreview(roleForm.permission_ids)" disabled />
          </div>
        </div>
        <div class="permission-level-grid">
          <div v-for="group in editablePermissionGroups" :key="`create-level-${group.module}`" class="permission-level-card">
            <div class="permission-level-head">
              <label>{{ moduleLabel(group.module) }}</label>
              <span class="level-badge" :class="levelBadgeClass(getModuleLevelForIds(roleForm.permission_ids, group))">
                {{ levelBadgeText(getModuleLevelForIds(roleForm.permission_ids, group)) }}
              </span>
            </div>
            <vs-select
              class="w-100"
              :value="getModuleLevelForIds(roleForm.permission_ids, group)"
              @input="setModuleLevelForRoleForm(group, $event)"
            >
              <vs-select-item value="none" text="Không có quyền" />
              <vs-select-item value="view" text="Chỉ xem" />
              <vs-select-item value="delete" text="Xem + Xóa" />
              <vs-select-item value="edit" text="Biên tập (xem + thêm + sửa)" />
              <vs-select-item value="full" text="Toàn quyền" />
            </vs-select>
          </div>
        </div>
        <div class="d-flex" style="gap:8px">
          <vs-button color="success" :disabled="!canCreateRole" @click="createRoleItem">Tạo role</vs-button>
          <vs-button type="border" @click="clearCreateRolePermissions">Xóa chọn quyền</vs-button>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-0">Danh sách Role</h4>
          <vs-input class="rbac-search" icon="search" placeholder="Tìm role..." v-model.trim="roleKeyword" />
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0">
            <thead>
              <tr>
                <th>Role</th>
                <th>Slug</th>
                <th>Số quyền</th>
                <th width="180">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="role in filteredRoles" :key="role.id">
                <td>{{ role.name }}</td>
                <td>{{ role.slug }}</td>
                <td>{{ (role.permission_ids || []).length }}</td>
                <td>
                  <vs-button size="small" type="border" @click="startEditRole(role)">Chỉnh quyền</vs-button>
                </td>
              </tr>
              <tr v-if="filteredRoles.length === 0">
                <td colspan="4" class="text-center text-muted">Chưa có role</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-if="editingRole" class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="card-title mb-1">Chỉnh quyền cho role: {{ editingRole.name }}</h4>
            <p class="text-muted mb-0">Chọn mức quyền cho từng module rồi bấm Lưu.</p>
          </div>
          <div class="d-flex" style="gap:8px">
            <vs-button size="small" type="border" @click="applyEditPreset('viewer')">Preset Viewer</vs-button>
            <vs-button size="small" type="border" @click="applyEditPreset('editor')">Preset Editor</vs-button>
            <vs-button size="small" type="border" @click="applyEditPreset('operator')">Preset Operator</vs-button>
          </div>
        </div>
        <div class="permission-level-grid">
          <div v-for="group in editablePermissionGroups" :key="`edit-level-${group.module}`" class="permission-level-card">
            <div class="permission-level-head">
              <label>{{ moduleLabel(group.module) }}</label>
              <span class="level-badge" :class="levelBadgeClass(getModuleLevelForIds(editingRole.permission_ids, group))">
                {{ levelBadgeText(getModuleLevelForIds(editingRole.permission_ids, group)) }}
              </span>
            </div>
            <vs-select
              class="w-100"
              :value="getModuleLevelForIds(editingRole.permission_ids, group)"
              @input="setModuleLevelForEditingRole(group, $event)"
            >
              <vs-select-item value="none" text="Không có quyền" />
              <vs-select-item value="view" text="Chỉ xem" />
              <vs-select-item value="delete" text="Xem + Xóa" />
              <vs-select-item value="edit" text="Biên tập (xem + thêm + sửa)" />
              <vs-select-item value="full" text="Toàn quyền" />
            </vs-select>
          </div>
        </div>
        <div class="d-flex" style="gap:8px">
          <vs-button size="small" color="success" @click="saveEditingRole">Lưu thay đổi</vs-button>
          <vs-button size="small" type="border" @click="cancelEditRole">Hủy</vs-button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="card-title mb-0">Gán Role cho tài khoản quản trị</h4>
          <vs-input class="rbac-search" icon="search" placeholder="Tìm admin..." v-model.trim="adminKeyword" />
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0">
            <thead>
              <tr>
                <th>Admin</th>
                <th>Email</th>
                <th>Roles</th>
                <th width="140">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in filteredAdminUsers" :key="u.id">
                <td>{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>
                  <vs-select multiple class="w-100" v-model="u.role_ids" :disabled="isFixedSuperAdmin(u)">
                    <vs-select-item v-for="r in availableRolesForUser(u)" :key="r.id" :value="r.id" :text="r.name" />
                  </vs-select>
                  <small v-if="isFixedSuperAdmin(u)" class="text-muted">Tài khoản Super Admin cố định, không đổi role.</small>
                </td>
                <td>
                  <vs-button size="small" color="success" :disabled="isFixedSuperAdmin(u)" @click="assignRoles(u)">Lưu role</vs-button>
                </td>
              </tr>
              <tr v-if="filteredAdminUsers.length === 0">
                <td colspan="4" class="text-center text-muted">Chưa có quản trị viên</td>
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
  name: "rbac-page",
  data() {
    return {
      permissions: [],
      roles: [],
      adminUsers: [],
      roleKeyword: "",
      adminKeyword: "",
      roleForm: { name: "", slug: "", permission_ids: [] },
      createPresetType: "custom",
      editingRole: null,
      showPermissionLegend: false
    };
  },
  computed: {
    canCreateRole() {
      return !!this.roleForm.name && !!this.roleForm.slug;
    },
    filteredRoles() {
      const key = (this.roleKeyword || "").toLowerCase();
      if (!key) return this.roles;
      return this.roles.filter((r) => (r.name || "").toLowerCase().includes(key) || (r.slug || "").toLowerCase().includes(key));
    },
    filteredAdminUsers() {
      const key = (this.adminKeyword || "").toLowerCase();
      if (!key) return this.adminUsers;
      return this.adminUsers.filter((u) => (u.name || "").toLowerCase().includes(key) || (u.email || "").toLowerCase().includes(key));
    },
    permissionGroups() {
      const groups = {};
      (this.permissions || []).forEach((permission) => {
        const slug = permission.slug || "";
        const parts = slug.split(".");
        const module = parts[0] || "other";
        const action = parts[1] || "other";
        if (!groups[module]) {
          groups[module] = {
            module,
            items: []
          };
        }
        groups[module].items.push({ ...permission, module, action });
      });
      const actionOrder = ["view", "create", "update", "delete", "manage"];
      return Object.values(groups)
        .map((group) => ({
          ...group,
          items: group.items.sort((a, b) => {
            const ai = actionOrder.indexOf(a.action);
            const bi = actionOrder.indexOf(b.action);
            return (ai === -1 ? 99 : ai) - (bi === -1 ? 99 : bi);
          })
        }))
        .sort((a, b) => a.module.localeCompare(b.module));
    },
    editablePermissionGroups() {
      return this.permissionGroups.filter((group) => group.module !== "dashboard");
    }
  },
  watch: {
    "roleForm.name"(value) {
      if (!this.roleForm.slug) {
        this.roleForm.slug = this.slugify(value || "");
      }
    }
  },
  methods: {
    ...mapActions([
      "listPermissions",
      "listRoles",
      "createRoleRbac",
      "updateRolePermissions",
      "listAdminUsersRbac",
      "assignRolesToAdminUser",
      "loadings"
    ]),
    normalizeRoleRows() {
      this.roles = this.roles.map((role) => ({
        ...role,
        permission_ids: Array.isArray(role.permissions) ? role.permissions.map((p) => p.id) : []
      }));
    },
    normalizeUserRows() {
      this.adminUsers = this.adminUsers.map((u) => ({
        ...u,
        role_ids: Array.isArray(u.roles) ? u.roles.map((r) => r.id) : []
      }));
    },
    slugify(value) {
      return (value || "")
        .toString()
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d")
        .replace(/[^a-z0-9\s-.]/g, "")
        .trim()
        .replace(/\s+/g, "-");
    },
    isFixedSuperAdmin(user) {
      return Number(user.id) === 1;
    },
    moduleLabel(module) {
      const labels = {
        dashboard: "Dashboard",
        rbac: "Role & Permission",
        menu: "Menu",
        language: "Ngôn ngữ",
        bill: "Đơn hàng",
        product: "Sản phẩm",
        blog: "Bài viết",
        service: "Dịch vụ",
        project: "Dự án",
        solution: "Giải pháp",
        pagecontent: "Trang nội dung",
        website: "Website",
        customer: "Khách hàng",
        promotion: "Khuyến mãi",
        variant: "Biến thể",
        tag: "Tag",
        bannerads: "Banner/Ads",
        review: "Review",
        construction: "Công trình",
        message: "Tin nhắn liên hệ",
        library: "Thư viện"
      };
      return labels[module] || module;
    },
    actionLabel(action) {
      const labels = {
        view: "Xem",
        create: "Thêm",
        update: "Sửa",
        delete: "Xóa",
        manage: "Toàn quyền"
      };
      return labels[action] || action;
    },
    levelBadgeText(level) {
      const labels = {
        none: "Không quyền",
        view: "Chỉ xem",
        delete: "Xem + Xóa",
        edit: "Biên tập",
        full: "Toàn quyền"
      };
      return labels[level] || "Tuỳ chỉnh";
    },
    levelBadgeClass(level) {
      return `level-badge--${level || "none"}`;
    },
    levelIdsForGroup(group, level) {
      const byAction = (action) => group.items.filter((item) => item.action === action).map((item) => item.id);
      const idsView = byAction("view");
      const idsCreate = byAction("create");
      const idsUpdate = byAction("update");
      const idsDelete = byAction("delete");
      const idsManage = byAction("manage");
      if (level === "none") return [];
      if (level === "view") return idsView;
      if (level === "delete") return Array.from(new Set([...idsView, ...idsDelete]));
      if (level === "edit") return Array.from(new Set([...idsView, ...idsCreate, ...idsUpdate]));
      if (level === "full") return Array.from(new Set([...group.items.map((item) => item.id), ...idsManage]));
      return [];
    },
    getModuleLevelForIds(permissionIds, group) {
      const ids = permissionIds || [];
      if (!group.items.length) return "none";
      const fullIds = this.levelIdsForGroup(group, "full");
      const editIds = this.levelIdsForGroup(group, "edit");
      const deleteIds = this.levelIdsForGroup(group, "delete");
      const viewIds = this.levelIdsForGroup(group, "view");
      const hasAll = (arr) => arr.length > 0 && arr.every((id) => ids.includes(id));
      if (hasAll(fullIds)) return "full";
      if (hasAll(editIds)) return "edit";
      if (hasAll(deleteIds)) return "delete";
      if (hasAll(viewIds)) return "view";
      return "none";
    },
    applyModuleLevel(permissionIds, group, level) {
      const current = Array.isArray(permissionIds) ? permissionIds : [];
      const groupIds = group.items.map((item) => item.id);
      const removedGroup = current.filter((id) => !groupIds.includes(id));
      const levelIds = this.levelIdsForGroup(group, level);
      return Array.from(new Set([...removedGroup, ...levelIds]));
    },
    setModuleLevelForRoleForm(group, level) {
      this.roleForm.permission_ids = this.applyModuleLevel(this.roleForm.permission_ids, group, level);
      this.createPresetType = "custom";
    },
    setModuleLevelForEditingRole(group, level) {
      if (!this.editingRole) return;
      this.editingRole.permission_ids = this.applyModuleLevel(this.editingRole.permission_ids, group, level);
    },
    selectedPermissionPreview(permissionIds) {
      const count = Array.isArray(permissionIds) ? permissionIds.length : 0;
      if (count === 0) return "Chưa chọn quyền nào";
      return `Đã chọn ${count} quyền`;
    },
    availableRolesForUser(user) {
      if (this.isFixedSuperAdmin(user)) {
        return this.roles;
      }
      return this.roles.filter((r) => r.slug !== "super-admin");
    },
    loadAll() {
      this.loadings(true);
      Promise.all([this.listPermissions(), this.listRoles(), this.listAdminUsersRbac()])
        .then(([permissionsRes, rolesRes, usersRes]) => {
          this.permissions = Array.isArray(permissionsRes.data) ? permissionsRes.data : [];
          this.roles = Array.isArray(rolesRes.data) ? rolesRes.data : [];
          this.adminUsers = Array.isArray(usersRes.data) ? usersRes.data : [];
          this.normalizeRoleRows();
          this.normalizeUserRows();
        })
        .catch((error) => {
          this.$errorFromApi(error, "Không thể tải dữ liệu phân quyền.");
        })
        .finally(() => this.loadings(false));
    },
    createRoleItem() {
      if (!this.canCreateRole) {
        this.$error("Vui lòng nhập đầy đủ tên và slug role.");
        return;
      }
      this.createRoleRbac(this.roleForm)
        .then(() => {
          this.$success("Tạo role thành công");
          this.roleForm = { name: "", slug: "", permission_ids: [] };
          this.createPresetType = "custom";
          this.loadAll();
        })
        .catch((error) => {
          this.$errorFromApi(error, "Tạo role thất bại.");
        });
    },
    applyRolePreset(type) {
      if (type === "viewer") {
        const viewerIds = this.permissions
          .filter((p) => (p.slug || "").endsWith(".view") || p.slug === "dashboard.view")
          .map((p) => p.id);
        this.roleForm.permission_ids = Array.from(new Set(viewerIds));
        this.$success("Đã áp dụng preset Viewer.");
        return;
      }
      if (type === "editor") {
        const slugs = [
          "dashboard.view",
          "product.view", "product.create", "product.update",
          "blog.view", "blog.create", "blog.update",
          "service.view", "service.create", "service.update",
          "project.view", "project.create", "project.update",
          "solution.view", "solution.create", "solution.update",
          "pagecontent.view", "pagecontent.create", "pagecontent.update",
          "bannerads.view", "bannerads.create", "bannerads.update",
          "language.view", "language.update",
          "review.view", "review.create", "review.update"
        ];
        const ids = this.permissions
          .filter((p) => slugs.includes(p.slug))
          .map((p) => p.id);
        this.roleForm.permission_ids = ids;
        this.$success("Đã áp dụng preset Editor.");
        return;
      }
      if (type === "operator") {
        const slugs = [
          "dashboard.view",
          "bill.view", "bill.update",
          "message.view", "message.update",
          "customer.view", "customer.update",
          "review.view", "review.update",
          "service.view",
          "product.view"
        ];
        const ids = this.permissions
          .filter((p) => slugs.includes(p.slug))
          .map((p) => p.id);
        this.roleForm.permission_ids = ids;
        this.$success("Đã áp dụng preset Operator.");
      }
    },
    applyEditPreset(type) {
      if (!this.editingRole) return;
      const original = [...this.roleForm.permission_ids];
      this.roleForm.permission_ids = [...this.editingRole.permission_ids];
      this.applyRolePreset(type);
      this.editingRole.permission_ids = [...this.roleForm.permission_ids];
      this.roleForm.permission_ids = original;
    },
    clearCreateRolePermissions() {
      this.roleForm.permission_ids = [];
      this.createPresetType = "custom";
    },
    startEditRole(role) {
      this.editingRole = {
        ...role,
        permission_ids: [...(role.permission_ids || [])]
      };
    },
    cancelEditRole() {
      this.editingRole = null;
    },
    saveEditingRole() {
      if (!this.editingRole) return;
      this.updateRolePermissions({
        id: this.editingRole.id,
        permission_ids: this.editingRole.permission_ids
      })
        .then(() => {
          this.$success("Cập nhật permission cho role thành công");
          this.editingRole = null;
          this.loadAll();
        })
        .catch((error) => {
          this.$errorFromApi(error, "Cập nhật permission thất bại.");
        });
    },
    saveRolePermission(role) {
      this.updateRolePermissions({ id: role.id, permission_ids: role.permission_ids })
        .then(() => this.$success("Cập nhật permission cho role thành công"))
        .catch((error) => {
          this.$errorFromApi(error, "Cập nhật permission thất bại.");
        });
    },
    assignRoles(user) {
      if (this.isFixedSuperAdmin(user)) {
        this.$error("Tài khoản Super Admin cố định, không thể thay đổi role");
        return;
      }
      this.assignRolesToAdminUser({ id: user.id, role_ids: user.role_ids })
        .then(() => this.$success("Gán role thành công"))
        .catch((error) => {
          this.$errorFromApi(error, "Gán role thất bại.");
        });
    }
  },
  mounted() {
    this.loadAll();
  }
};
</script>
<style scoped>
.rbac-search {
  min-width: 260px;
}
.permission-fixed-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.permission-chip {
  display: inline-flex;
  border: 1px solid #d8e2f5;
  background: #f6f8ff;
  border-radius: 999px;
  padding: 4px 10px;
  font-size: 12px;
  color: #22345f;
}
.permission-group-card {
  border: 1px solid #e5ebfb;
  border-radius: 10px;
  padding: 10px;
  margin-bottom: 10px;
}
.permission-group-title {
  margin: 0 0 8px;
  color: #3f4d72;
}
.permission-legend {
  max-height: 320px;
  overflow: auto;
}
.permission-level-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 10px;
  margin-bottom: 12px;
}
.permission-level-card {
  border: 1px solid #dfe7fb;
  border-radius: 10px;
  padding: 10px;
  background: #fbfcff;
}
.permission-level-card label {
  display: block;
  margin-bottom: 6px;
  font-size: 12px;
  color: #495a84;
}
.permission-level-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 6px;
}
.permission-level-head label {
  margin: 0;
}
.level-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  padding: 3px 9px;
  font-size: 11px;
  font-weight: 600;
  border: 1px solid transparent;
}
.level-badge--none {
  background: #f3f4f8;
  color: #5f6475;
  border-color: #d7dbea;
}
.level-badge--view {
  background: #e8f1ff;
  color: #1f57b8;
  border-color: #b9d3ff;
}
.level-badge--delete {
  background: #ffecec;
  color: #b42318;
  border-color: #f6b5b5;
}
.level-badge--edit {
  background: #fff7df;
  color: #8a6200;
  border-color: #f1d58b;
}
.level-badge--full {
  background: #eafaf0;
  color: #1f7a3f;
  border-color: #b8e5c8;
}
.permission-module-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 10px;
  margin-bottom: 12px;
}
.permission-module-grid--compact {
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}
.permission-module-card {
  border: 1px solid #dfe7fb;
  border-radius: 10px;
  padding: 8px;
  background: #fbfcff;
}
.permission-module-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 6px;
}
.permission-actions {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 4px 8px;
}
.permission-check {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  margin: 0;
}
.permission-toggle-all {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #4e5d84;
  margin: 0;
}
</style>
