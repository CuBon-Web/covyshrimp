import Vue from 'vue';
export default {
    data() {
        return {
            __notify: null
        }
    },
    methods: {
        consumeRecentApiError() {
            if (typeof window === "undefined") {
                return null;
            }
            const last = window.__LAST_API_ERROR__ || null;
            if (!last || !last.ts) {
                return null;
            }
            // only apply to the immediate UI error flow
            if (Date.now() - Number(last.ts) > 5000) {
                return null;
            }
            window.__LAST_API_ERROR__ = null;
            return last;
        },
        $success(text) {
            this.$vs.notify({
                title: 'Thành công',
                text: text || 'Thao tác đã được thực hiện.',
                color: "success",
                position: "top-right"
          });
        },
        $error(text) {
            const apiError = this.consumeRecentApiError();
            if (apiError && Number(apiError.status) === 403) {
                this.$forbidden(apiError.message || text);
                return;
            }
            if (apiError && Number(apiError.status) === 401) {
                this.$unauthenticated(apiError.message || text);
                return;
            }
            this.$vs.notify({
                title: 'Không thành công',
                text: text || 'Đã xảy ra lỗi. Vui lòng thử lại.',
                color: "danger",
                position: "top-right"
          });
        },
        $forbidden(text) {
            this.$vs.notify({
                title: 'Không đủ quyền',
                text: text || 'Bạn không có quyền thực hiện thao tác này.',
                color: "warning",
                position: "top-right"
          });
        },
        $unauthenticated(text) {
            this.$vs.notify({
                title: 'Phiên đăng nhập hết hạn',
                text: text || 'Vui lòng đăng nhập lại để tiếp tục.',
                color: "warning",
                position: "top-right"
          });
        },
        $errorFromApi(error, fallbackText = '') {
            const status = error && error.response && error.response.status;
            if (status === 403) {
                this.$forbidden((error.response && error.response.data && error.response.data.message) || fallbackText);
                return;
            }
            if (status === 401) {
                this.$unauthenticated((error.response && error.response.data && error.response.data.message) || fallbackText);
                return;
            }
            this.$error((error && error.response && error.response.data && error.response.data.message) || fallbackText);
        },
        $hasPermission(requiredPermission) {
            if (!requiredPermission) return true;
            const permissionSlugs = (this.$store && this.$store.getters && this.$store.getters.permissionSlugs) || [];
            if (permissionSlugs.includes(requiredPermission)) {
                return true;
            }
            if (
                requiredPermission.endsWith(".view") ||
                requiredPermission.endsWith(".create") ||
                requiredPermission.endsWith(".update") ||
                requiredPermission.endsWith(".delete")
            ) {
                const prefix = requiredPermission.split(".").slice(0, -1).join(".");
                return permissionSlugs.includes(prefix + ".manage");
            }
            return false;
        },
        $goIfAllowed(requiredPermission, routeTarget, forbiddenText = "") {
            if (!this.$hasPermission(requiredPermission)) {
                this.$forbidden(forbiddenText || "Bạn không có quyền thực hiện thao tác này.");
                return false;
            }
            if (typeof routeTarget === "function") {
                routeTarget();
                return true;
            }
            if (routeTarget && this.$router) {
                this.$router.push(routeTarget);
            }
            return true;
        },
        showNotification(title, message, type, multi = false) {
            if( this.__notify != null && !multi ) {
                this.__notify.close();
            }
            this.__notify = this.$notify({
                title: title,
                message: message,
                type: type
            });
        }
    }
}