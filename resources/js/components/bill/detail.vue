<template>
  <div class="bill-detail-page">
    <div class="bill-detail-head">
      <div>
        <h3 class="page-title mb-1">Chi tiết đơn hàng #{{ bill.code_bill }}</h3>
        <p class="text-muted mb-0">Theo dõi thông tin đơn hàng, trạng thái và khách hàng tại một nơi.</p>
      </div>
      <div class="bill-detail-head-actions">
        <vs-chip :color="statusMeta(bill.statu).color">{{ statusMeta(bill.statu).label }}</vs-chip>
        <vs-button color="success" type="filled" @click="changePaymented()" v-if="bill.statu == 0">Xác nhận đã thanh toán</vs-button>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3">
        <div class="bill-kpi-card">
          <p>Tổng sản phẩm</p>
          <strong>{{ totalQty }}</strong>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bill-kpi-card">
          <p>Tạm tính</p>
          <strong>{{ formatNumber(total_product) }}đ</strong>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bill-kpi-card">
          <p>Phí vận chuyển</p>
          <strong>{{ formatNumber(bill.transport_price || 0) }}đ</strong>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bill-kpi-card bill-kpi-highlight">
          <p>Tổng thanh toán</p>
          <strong>{{ formatNumber(total_bill) }}đ</strong>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Sản phẩm trong đơn</h5>
            <vs-table stripe :data="bill.bill_detail">
              <template slot="thead">
                <vs-th width="80">Ảnh</vs-th>
                <vs-th>Tên sản phẩm</vs-th>
                <vs-th>Thuộc tính</vs-th>
                <vs-th>Giá bán</vs-th>
                <vs-th>Số lượng</vs-th>
                <vs-th>Thành tiền</vs-th>
              </template>
              <template>
                <vs-tr :key="indextr" v-for="(tr, indextr) in bill.bill_detail">
                  <vs-td><vs-avatar size="large" :src="tr.images" /></vs-td>
                  <vs-td>{{ tr.name }}</vs-td>
                  <vs-td>{{ tr.variant || "-" }}</vs-td>
                  <vs-td>{{ formatNumber(tr.price) }}đ</vs-td>
                  <vs-td>{{ tr.qty }}</vs-td>
                  <vs-td><strong>{{ formatNumber((tr.price || 0) * (tr.qty || 0)) }}đ</strong></vs-td>
                </vs-tr>
              </template>
            </vs-table>

            <div class="row mt-3">
              <div class="col-md-12">
                <label class="mb-1">Ghi chú đơn hàng</label>
                <div class="bill-note-box">{{ bill.note || "Không có ghi chú" }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Thông tin khách hàng</h5>
            <div class="form-group">
              <label>Tài khoản đặt hàng</label>
              <vs-select
                placeholder="Search and select"
                class="selectExample"
                label-placeholder="Autocomplete"
                v-model="bill.code_customer"
              >
                <vs-select-item key="0" value="0" text="Khách hàng chưa có tài khoản" />
                <vs-select-item :key="index" :value="item.id" :text="item.name" v-for="item,index in customer" />
              </vs-select>
            </div>

            <table class="table bill-info-table mb-0">
              <tbody>
                <tr>
                  <td>Họ tên</td>
                  <td>{{ bill.cus_name || "-" }}</td>
                </tr>
                <tr>
                  <td>SĐT</td>
                  <td>{{ bill.cus_phone || "-" }}</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>{{ bill.cus_email || "-" }}</td>
                </tr>
                <tr>
                  <td>Địa chỉ</td>
                  <td>{{ bill.cus_address || "-" }}</td>
                </tr>
                <tr>
                  <td>Thanh toán</td>
                  <td>
                    <vs-chip :color="paymentMethodMeta(bill.payment_method).color">
                      {{ paymentMethodMeta(bill.payment_method).label }}
                    </vs-chip>
                  </td>
                </tr>
                <tr>
                  <td>Trạng thái</td>
                  <td>
                    <vs-chip :color="statusMeta(bill.statu).color">{{ statusMeta(bill.statu).label }}</vs-chip>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { mapActions } from "vuex";
import Multiselect from 'vue-multiselect'
import Payments from '../layouts/modal/bill/payments.vue'
export default {
  name: "billsDetail",
  data() {
    return {
      popupActivo:false,
      customer:[],
      submitted: false,
      products:[],
      qty:"",
      bill:{
          code_bill:this.$route.params.code_bill,
          code_customer:'',
          total_money:0,
          statu:0,
          note:"",
          promotion:'',
          transport:'',
          transport_price:0,
          bill_detail:[]
        }
    };
  },
  validations: {
    
  },
  components: {
    Multiselect,Payments
  },
  computed: {
    totalQty() {
      if (!Array.isArray(this.bill.bill_detail)) return 0;
      return this.bill.bill_detail.reduce((sum, item) => sum + Number(item.qty || 0), 0);
    },
    total_product(){
      if(this.bill.bill_detail.length == 0){
        return 0;
      }else{
        var total = 0;
        let vm = this;

        for ( let i=0; i<vm.bill.bill_detail.length ; i++ ) {
            total += (vm.bill.bill_detail[i]['price']-(vm.bill.bill_detail[i]['price']*(vm.bill.bill_detail[i]['discount']/100))) * vm.bill.bill_detail[i]['qty']
        }
        return total
      }
    },
    total_bill (){
      return this.total_product + this.bill.transport_price;
    }
  },
  watch: {},
  methods: {
    ...mapActions(["loadings","listProduct","listCustomer","addBill","detailBill","changeStatus"]),
    statusMeta(status) {
      const map = {
        0: { label: "Đợi kiểm tra", color: "primary" },
        1: { label: "Đã thanh toán", color: "success" },
        2: { label: "Chưa thanh toán", color: "warning" },
        3: { label: "Hoàn tất", color: "success" },
        4: { label: "Đã hủy", color: "danger" },
      };
      return map[Number(status)] || { label: "Không xác định", color: "dark" };
    },
    paymentMethodMeta(method) {
      const value = (method || "").toLowerCase();
      if (value === "online") return { label: "Pay online", color: "success" };
      return { label: "COD", color: "warning" };
    },
    closePop(event) {
      this.popupActivo = event;
    },
    changeUnPayment(){
      this.loadings(true);
      this.changeStatus({'status':2,'code_bill':this.$route.params.code_bill}).then(response => {
        this.loadings(false);
        this.detailBills();
        this.$success('Đổi trạng thái đơn hàng thành công');
          this.$router.push({ name: "billList" });
      })
    },
    changePaymented(){
      this.loadings(true);
      this.changeStatus({'status':1,'code_bill':this.$route.params.code_bill}).then(response => {
        this.loadings(false);
        this.detailBills();
        this.$success('Đổi trạng thái thành công');
          this.$router.push({ name: "billList" });
      })
    },
    detailBills(){
      this.detailBill(this.bill.code_bill).then(response => {
        this.bill = response.data;
        
      })
    },
    payments(event){
      if(event.payments == 2){
        this.bill.transport_price = event.price;
        this.bill.transport = event.name;
      }else if(event.payments == 40000){
        this.bill.transport_price = 40000;
        this.bill.transport = "Giao hàng tận nơi";
      }else{
        this.bill.transport_price = 0;
        this.bill.transport = "Miễn phí vận chuyển";
      }
    },
    customLabel ({ name }) {
      return `${name}`
    },
    formatNumber(num) {
       return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    addBills(){
      this.loadings(true);
      this.addBill(this.bill).then(response => {
        this.loadings(false);
        this.$success('Thêm đơn hàng thành công');
        this.$router.push({ name: "billList" });
      }).catch(error => {
        this.loadings(false);
        this.$error('Thêm đơn hàng thất bại');
      })
    },
    listProducts(){
      this.loadings(true);
      this.listProduct()
      .then(response => {
          this.loadings(false);
          this.products = response.data;
      }).catch(err => {
          this.loadings(false);
          this.products = err.data;
      });
    },
    listCustomers() {
      this.loadings(true);
      this.listCustomer()
      .then(response => {
          this.loadings(false);
          this.customer = response.data;
        });
    },
    saveBill(){
      this.bill.total_money = this.total_bill;
      console.log(this.bill);
    }
  },
  mounted() {
      this.listProducts();
      this.listCustomers();
      this.detailBills();
  }
};
</script>
<style scoped>
.el-select-dropdown__item{
    height: auto!important;
}
.bill-detail-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 14px;
}
.bill-detail-head-actions {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-wrap: wrap;
}
.bill-kpi-card {
  border: 1px solid #e7edf5;
  border-radius: 10px;
  padding: 12px;
  background: #f9fbff;
}
.bill-kpi-card p {
  margin: 0;
  color: #738198;
  font-size: 12px;
}
.bill-kpi-card strong {
  font-size: 22px;
  line-height: 1.2;
}
.bill-kpi-highlight {
  background: #eef6ff;
  border-color: #cfe4ff;
}
.bill-note-box {
  border: 1px solid #e5eaf2;
  border-radius: 8px;
  background: #f8fafc;
  padding: 10px 12px;
  min-height: 52px;
}
.bill-info-table td:first-child {
  width: 95px;
  color: #6b7280;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>