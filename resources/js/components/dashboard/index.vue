<template>
  <div class="dashboard-page">
    <div class="dashboard-toolbar">
      <div>
        <h3 class="dashboard-title">Tổng quan quản trị</h3>
        <p class="dashboard-subtitle">Số liệu cập nhật theo đơn hàng hiện tại</p>
      </div>
      <div class="dashboard-actions">
        <router-link :to="{ name: 'createProduct' }" class="btn btn-primary btn-sm">Thêm sản phẩm</router-link>
        <router-link :to="{ name: 'addBlogs' }" class="btn btn-outline-primary btn-sm">Viết bài blog</router-link>
        <router-link :to="{ name: 'billAdd' }" class="btn btn-outline-success btn-sm">Tạo đơn hàng</router-link>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-6 mb-3" v-for="item in kpiCards" :key="item.key">
        <div class="dash-card">
          <p class="dash-card-label">{{ item.label }}</p>
          <p class="dash-card-value">{{ item.value }}</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="dash-panel">
          <h4 class="dash-panel-title">Trạng thái đơn hàng</h4>
          <div class="status-grid">
            <div class="status-item">
              <span>Chưa thanh toán</span>
              <strong>{{ overview.order_status.draft }}</strong>
            </div>
            <div class="status-item">
              <span>Đã xác nhận</span>
              <strong>{{ overview.order_status.confirmed }}</strong>
            </div>
            <div class="status-item">
              <span>Đang giao hàng</span>
              <strong>{{ overview.order_status.shipping }}</strong>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="dash-panel">
          <h4 class="dash-panel-title">Doanh thu theo ngày</h4>
          <div class="revenue-chart">
            <div class="revenue-row" v-for="(item, idx) in chartData" :key="idx">
              <span class="revenue-label">{{ item.label }}</span>
              <div class="revenue-bar-wrap">
                <div class="revenue-bar" :style="{ width: barWidth(item.value) }"></div>
              </div>
              <span class="revenue-value">{{ formatCurrency(item.value) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="dash-panel">
      <h4 class="dash-panel-title">Top sản phẩm bán chạy</h4>
      <div class="table-responsive">
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th>Sản phẩm</th>
              <th class="text-right">Số lượng</th>
              <th class="text-right">Doanh thu</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!overview.top_products.length">
              <td colspan="3" class="text-center text-muted">Chưa có dữ liệu bán hàng</td>
            </tr>
            <tr v-for="(item, index) in overview.top_products" :key="index">
              <td>{{ item.name }}</td>
              <td class="text-right">{{ item.total_qty }}</td>
              <td class="text-right">{{ formatCurrency(item.total_amount) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "dashboard-index",
  data() {
    return {
      chartData: [],
      overview: {
        kpi: {
          today_revenue: 0,
          today_bill: 0,
          product_total: 0,
          blog_total: 0,
          customer_total: 0
        },
        order_status: {
          draft: 0,
          confirmed: 0,
          shipping: 0
        },
        top_products: []
      }
    };
  },
  computed: {
    kpiCards() {
      return [
        { key: "today_revenue", label: "Doanh thu hôm nay", value: this.formatCurrency(this.overview.kpi.today_revenue) },
        { key: "today_bill", label: "Đơn hàng hôm nay", value: this.overview.kpi.today_bill || 0 },
        { key: "product_total", label: "Tổng sản phẩm", value: this.overview.kpi.product_total || 0 },
        { key: "blog_total", label: "Tổng bài blog", value: this.overview.kpi.blog_total || 0 },
        { key: "customer_total", label: "Khách hàng", value: this.overview.kpi.customer_total || 0 }
      ];
    },
    maxRevenue() {
      const values = this.chartData.map(item => Number(item.value || 0));
      return Math.max(...values, 1);
    }
  },
  methods: {
    ...mapActions(["getOverviewDashboard", "getDataChart"]),
    formatCurrency(value) {
      const number = Number(value || 0);
      return `${number.toLocaleString("vi-VN")} đ`;
    },
    barWidth(value) {
      const normalized = Number(value || 0) / this.maxRevenue;
      return `${Math.max(8, Math.round(normalized * 100))}%`;
    },
    loadOverview() {
      this.getOverviewDashboard()
        .then(response => {
          this.overview = response.data || this.overview;
        })
        .catch(() => {
          this.$error("Không tải được dữ liệu dashboard");
        });
    },
    loadChart() {
      this.getDataChart({})
        .then(response => {
          this.chartData = Array.isArray(response.data) ? response.data : [];
        })
        .catch(() => {
          this.chartData = [];
        });
    }
  },
  mounted() {
    this.loadOverview();
    this.loadChart();
  }
};
</script>

<style scoped>
.dashboard-toolbar { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
.dashboard-title { margin: 0; font-size: 20px; }
.dashboard-subtitle { margin: 0; color: #6b7280; font-size: 13px; }
.dashboard-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.dash-card, .dash-panel { background: #fff; border: 1px solid #e5eaf5; border-radius: 10px; padding: 14px; height: 100%; }
.dash-card-label { margin: 0 0 6px; color: #6b7280; font-size: 12px; }
.dash-card-value { margin: 0; font-size: 19px; font-weight: 700; color: #1f2a4b; }
.dash-panel-title { margin: 0 0 12px; font-size: 16px; }
.status-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.status-item { background: #f6f8ff; border: 1px solid #e3e8f8; border-radius: 8px; padding: 10px; text-align: center; }
.status-item span { display: block; color: #617096; font-size: 12px; }
.status-item strong { font-size: 19px; color: #243158; }
.revenue-row { display: grid; grid-template-columns: 56px 1fr 110px; gap: 8px; align-items: center; margin-bottom: 8px; }
.revenue-label { font-size: 12px; color: #556286; }
.revenue-bar-wrap { background: #eff3ff; border-radius: 999px; height: 8px; overflow: hidden; }
.revenue-bar { background: linear-gradient(90deg, #4f46e5, #0ea5e9); height: 8px; border-radius: 999px; }
.revenue-value { text-align: right; font-size: 12px; color: #243158; }
</style>
