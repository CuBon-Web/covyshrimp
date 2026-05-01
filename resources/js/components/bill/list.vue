<template>
  <div>
    <h3 class="page-title">Quản lý đơn hàng</h3>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="bill-toolbar bill-toolbar-compact mb-3">
              <vs-input
                class="bill-search"
                icon="search"
                placeholder="Tìm mã đơn, tên khách, SĐT..."
                v-model="keyword"
                @input="onKeywordInput"
              />
              <vs-select class="bill-status-select" v-model="statusFilter" @change="fetchBills">
                <vs-select-item :value="null" text="Trạng thái" />
                <vs-select-item :value="0" text="Đợi kiểm tra" />
                <vs-select-item :value="1" text="Đã thanh toán" />
                <vs-select-item :value="2" text="Chưa thanh toán" />
                <vs-select-item :value="3" text="Hoàn tất" />
                <vs-select-item :value="4" text="Đã hủy" />
              </vs-select>
              <vs-select class="bill-payment-select" v-model="paymentMethodFilter" @change="fetchBills">
                <vs-select-item value="" text="Thanh toán" />
                <vs-select-item value="cod" text="COD" />
                <vs-select-item value="online" text="Pay online" />
              </vs-select>
              <vs-select class="bill-date-select" v-model="dateRangeFilter" @change="onDateRangeChange">
                <vs-select-item value="" text="Thời gian" />
                <vs-select-item value="today" text="Hôm nay" />
                <vs-select-item value="yesterday" text="Hôm qua" />
                <vs-select-item value="last_7_days" text="7 ngày qua" />
                <vs-select-item value="last_30_days" text="30 ngày qua" />
                <vs-select-item value="custom" text="Tùy chọn ngày" />
              </vs-select>
              <input
                v-if="dateRangeFilter === 'custom'"
                type="date"
                class="bill-date-input"
                v-model="startDate"
                @change="fetchBills"
              />
              <input
                v-if="dateRangeFilter === 'custom'"
                type="date"
                class="bill-date-input"
                v-model="endDate"
                @change="fetchBills"
              />
              <vs-button
                type="filled"
                color="success"
                icon="file_download"
                :disabled="list.length === 0"
                @click="exportExcelByFilter"
              >
              </vs-button>
              <vs-button type="border" color="primary" icon="refresh" @click="resetFilters"></vs-button>
            </div>

            <div class="bill-stat-block mb-3" v-if="list.length > 0">
              <div class="bill-stat-inline">
                <span>Tổng: <strong>{{ list.length }}</strong></span>
                <span>Đã TT: <strong>{{ statusCount(1) }}</strong></span>
                <span>Đợi KT: <strong>{{ statusCount(0) }}</strong></span>
                <span>Chưa TT: <strong>{{ statusCount(2) }}</strong></span>
              </div>
            </div>

            <div class="row" v-if="list.length === 0">
              <div class="col-md-2"></div>
              <div class="col-md-8 text-center">
                <img width="260" src="https://bizweb.dktcdn.net/assets/admin/images/empty-states/abandon_checkout.svg" alt="empty" />
                <h5 class="mt-3">Không có đơn hàng phù hợp</h5>
                <p class="mb-0 text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
              </div>
              <div class="col-md-2"></div>
            </div>

            <vs-table max-items="10" pagination :data="list" v-if="list.length > 0">
              <template slot="thead">
                <vs-th>Mã đơn</vs-th>
                <vs-th>Khách hàng</vs-th>
                <vs-th>Liên hệ</vs-th>
                <vs-th>Tổng tiền</vs-th>
                <vs-th>Ngày tạo</vs-th>
                <vs-th>Thanh toán</vs-th>
                <vs-th>Trạng thái</vs-th>
                <vs-th>Thao tác</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr
                  :key="indextr"
                  v-for="(tr, indextr) in data"
                  :class="{
                    'bill-row-attention': Number(tr.statu) === 0,
                    'bill-row-online': Number(tr.statu) === 0 && (tr.payment_method || '').toLowerCase() === 'online'
                  }"
                >
                  <vs-td>#{{ tr.code_bill }}</vs-td>
                  <vs-td>{{ tr.cus_name || 'Khách lẻ' }}</vs-td>
                  <vs-td>
                    <div>{{ tr.cus_phone || '-' }}</div>
                    <small class="text-muted">{{ tr.cus_email || '-' }}</small>
                  </vs-td>
                  <vs-td>{{ formatNumber(tr.total_money || 0) }}đ</vs-td>
                  <vs-td>{{ tr.created_at }}</vs-td>
                  <vs-td>
                    <vs-chip :color="paymentMethodMeta(tr.payment_method).color">
                      {{ paymentMethodMeta(tr.payment_method).label }}
                    </vs-chip>
                    <span v-if="Number(tr.statu) === 0" class="bill-priority-tag ml-1">Ưu tiên xử lý</span>
                  </vs-td>
                  <vs-td>
                    <vs-chip :color="statusMeta(tr.statu).color">{{ statusMeta(tr.statu).label }}</vs-chip>
                  </vs-td>
                  <vs-td>
                    <div class="bill-action-group">
                      <router-link :to="{name:'billDetail',params:{code_bill:tr.code_bill}}">
                        <vs-button size="small" color="success" icon="visibility" type="border"></vs-button>
                      </router-link>
                      <vs-select
                        class="bill-quick-status"
                        :value="tr.statu"
                        @input="changeOrderStatus(tr, $event)"
                        @change="changeOrderStatus(tr, $event)"
                      >
                        <vs-select-item :value="0" text="Đợi kiểm tra" />
                        <vs-select-item :value="1" text="Đã thanh toán" />
                        <vs-select-item :value="2" text="Chưa thanh toán" />
                        <vs-select-item :value="3" text="Hoàn tất" />
                        <vs-select-item :value="4" text="Đã hủy" />
                      </vs-select>
                    </div>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "billList",
  data() {
    return {
      keyword: "",
      statusFilter: null,
      paymentMethodFilter: "",
      dateRangeFilter: "",
      startDate: "",
      endDate: "",
      list: [],
      timer: null,
    };
  },
  methods: {
    ...mapActions(["draftBill", "detailBill", "changeStatus", "loadings"]),
    statusMeta(status) {
      const map = {
        0: { label: "Đợi kiểm tra", color: "primary" },
        1: { label: "Đã thanh toán", color: "success" },
        2: { label: "Chưa thanh toán", color: "danger" },
        3: { label: "Hoàn tất", color: "success" },
        4: { label: "Đã hủy", color: "warning" },
      };
      return map[Number(status)] || { label: "Không xác định", color: "dark" };
    },
    statusCount(status) {
      return this.list.filter((item) => Number(item.statu) === Number(status)).length;
    },
    paymentMethodMeta(method) {
      const value = (method || "").toLowerCase();
      if (value === "online") return { label: "Pay online", color: "success" };
      return { label: "COD", color: "warning" };
    },
    paymentMethodLabel(method) {
      const value = (method || "").toLowerCase();
      return value === "online" ? "Pay online" : "COD";
    },
    formatNumber(num) {
      return Number(num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    buildFilterPayload() {
      return {
        keyword: this.keyword || "",
        status: this.statusFilter === null ? "" : this.statusFilter,
        payment_method: this.paymentMethodFilter || "",
        date_range: this.dateRangeFilter || "",
        start_date: this.dateRangeFilter === "custom" ? this.startDate : "",
        end_date: this.dateRangeFilter === "custom" ? this.endDate : "",
      };
    },
    fetchBills() {
      this.loadings(true);
      this.draftBill(this.buildFilterPayload())
        .then((response) => {
          this.list = Array.isArray(response.data) ? response.data : [];
        })
        .finally(() => {
          this.loadings(false);
        });
    },
    onKeywordInput() {
      if (this.timer) clearTimeout(this.timer);
      this.timer = setTimeout(() => {
        this.fetchBills();
      }, 350);
    },
    resetFilters() {
      this.keyword = "";
      this.statusFilter = null;
      this.paymentMethodFilter = "";
      this.dateRangeFilter = "";
      this.startDate = "";
      this.endDate = "";
      this.fetchBills();
    },
    onDateRangeChange() {
      if (this.dateRangeFilter !== "custom") {
        this.startDate = "";
        this.endDate = "";
      }
      this.fetchBills();
    },
    exportExcelByFilter() {
      if (!this.list.length) {
        this.$error("Không có dữ liệu để xuất");
        return;
      }
      this.exportExcelWithDetails();
    },
    async exportExcelWithDetails() {
      this.loadings(true);
      try {
        const detailResponses = await Promise.all(
          this.list.map((item) => this.detailBill(item.code_bill).catch(() => ({ data: null })))
        );

        const headers = [
          "Ma don",
          "Khach hang",
          "So dien thoai",
          "Email",
          "Tong tien",
          "Ngay tao",
          "Thanh toan",
          "Trang thai",
          "Chi tiet san pham",
        ];

        const rows = this.list.map((item, index) => {
          const detailData = detailResponses[index] && detailResponses[index].data ? detailResponses[index].data : {};
          const details = Array.isArray(detailData.bill_detail) ? detailData.bill_detail : [];
          const detailText = details.length
            ? details.map((d) => {
                const lineTotal = Number(d.price || 0) * Number(d.qty || 0);
                return `${d.name || ""}${d.variant ? ` (${d.variant})` : ""} | SL:${d.qty || 0} | DG:${d.price || 0} | TT:${lineTotal}`;
              }).join(" || ")
            : "";

          return [
            `="${item.code_bill || ""}"`,
            item.cus_name || "Khach le",
            item.cus_phone || "",
            item.cus_email || "",
            item.total_money || 0,
            item.created_at || "",
            this.paymentMethodLabel(item.payment_method),
            this.statusMeta(item.statu).label,
            detailText,
          ];
        });

        const toCsvCell = (value) => {
          const text = String(value === null || value === undefined ? "" : value);
          return `"${text.replace(/"/g, '""')}"`;
        };

        const csvContent = [headers, ...rows]
          .map((row) => row.map(toCsvCell).join(","))
          .join("\n");

        const bom = "\uFEFF"; // Excel UTF-8
        const blob = new Blob([bom + csvContent], { type: "text/csv;charset=utf-8;" });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, "0");
        const d = String(now.getDate()).padStart(2, "0");
        link.href = url;
        link.setAttribute("download", `don-hang-chi-tiet-${y}${m}${d}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        this.$success("Đã xuất file Excel (CSV) kèm chi tiết đơn hàng");
      } catch (e) {
        this.$error("Xuất file thất bại, vui lòng thử lại");
      } finally {
        this.loadings(false);
      }
    },
    getSelectValue(payload) {
      if (payload && payload.target) return payload.target.value;
      return payload;
    },
    changeOrderStatus(row, payload) {
      const next = Number(this.getSelectValue(payload));
      if (Number.isNaN(next)) return;
      if (Number(row.statu) === next) return;
      this.changeStatus({ status: next, code_bill: row.code_bill })
        .then(() => {
          row.statu = next;
          this.$success("Cập nhật trạng thái thành công");
        })
        .catch(() => {
          this.$error("Cập nhật trạng thái thất bại");
        });
    },
  },
  mounted() {
    this.fetchBills();
  },
};
</script>

<style scoped>
.bill-toolbar {
  display: flex;
  gap: 10px;
  align-items: center;
  flex-wrap: wrap;
}
.bill-toolbar-compact {
  flex-wrap: nowrap;
  gap: 8px;
  overflow-x: auto;
  padding-bottom: 4px;
}
.bill-search {
  min-width: 280px;
  flex: 1 1 320px;
}
.bill-status-select {
  min-width: 100px;
}
.bill-date-select {
  min-width: 100px;
}
.bill-payment-select {
  min-width: 130px;
}
.bill-date-input {
  height: 34px;
  border: 1px solid #dfe3ea;
  border-radius: 6px;
  padding: 0 8px;
  min-width: 140px;
}
.bill-stat-inline {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 10px;
  height: 34px;
  border: 1px solid #e6ebf2;
  border-radius: 6px;
  background: #f9fbff;
  white-space: nowrap;
  font-size: 12px;
  color: #4b5563;
}
.bill-stat-block {
  display: flex;
}
.bill-action-group {
  display: flex;
  align-items: center;
  gap: 8px;
}
.bill-quick-status {
  min-width: 150px;
}
:deep(.bill-row-attention td) {
  background: #fff8e6 !important;
}
:deep(.bill-row-online td) {
  border-top: 1px solid #f8d56b;
  border-bottom: 1px solid #f8d56b;
}
.bill-priority-tag {
  display: inline-flex;
  align-items: center;
  height: 22px;
  padding: 0 8px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 600;
  color: #7c5a00;
  background: #ffe9a8;
}
</style>
