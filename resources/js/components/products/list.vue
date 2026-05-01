<template>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <crm-page-header
              title="Danh sách sản phẩm"
              description="Thêm mới hoặc cập nhật sản phẩm"
            >
              <vs-button
                type="gradient"
                :disabled="!$hasPermission('product.create')"
                @click="$goIfAllowed('product.create', { name: 'createProduct' }, 'Bạn không có quyền thêm mới sản phẩm.')"
              >Thêm mới</vs-button>
            </crm-page-header>

            <crm-filter-bar class="crm-list-filter-bar">
              <div class="row w-100 crm-list-filter-row">
                <div class="col-12 col-md-2 mb-2">
                  <vs-input class="crm-list-filter-control crm-list-filter-keyword" placeholder="Từ khóa" v-model="filters.keyword" @keyup="searchProduct()"/>
                </div>
                <div class="col-6 col-md-2 mb-2">
                  <select class="form-control crm-list-filter-control" v-model="filters.category" @change="onCategoryChange">
                    <option :value="0">Danh mục</option>
                    <option v-for="item in categories" :key="'cate-' + item.id" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                </div>
                <div class="col-6 col-md-2 mb-2">
                  <select class="form-control crm-list-filter-control" v-model="filters.type_cate" @change="onTypeCateChange">
                    <option :value="0">Danh mục cấp 1</option>
                    <option v-for="item in typeCates" :key="'type1-' + item.id" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                </div>
                <div class="col-6 col-md-2 mb-2">
                  <select class="form-control crm-list-filter-control" v-model="filters.type_two" @change="listProducts">
                    <option :value="0">Danh mục cấp 2</option>
                    <option v-for="item in typeTwos" :key="'type2-' + item.id" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                </div>
                <div class="col-6 col-md-2 mb-2">
                  <select class="form-control crm-list-filter-control" v-model="filters.price_sort" @change="listProducts">
                    <option value="">Giá</option>
                    <option value="asc">Thấp đến cao</option>
                    <option value="desc">Cao đến thấp</option>
                  </select>
                </div>
                <div class="col-6 col-md-2 mb-2">
                  <select class="form-control crm-list-filter-control" v-model="filters.created_sort" @change="listProducts">
                    <option value="">Thời gian đăng</option>
                    <option value="desc">Mới nhất</option>
                    <option value="asc">Cũ nhất</option>
                  </select>
                </div>
                <div class="col-12 col-md-1 mb-2 d-flex align-items-center">
                  <div class="crm-list-filter-actions">
                    <vs-button class="crm-list-filter-btn" color="primary" type="filled" @click="listProducts">Lọc</vs-button>
                    <vs-button class="crm-list-filter-btn" color="dark" type="border" @click="resetFilters">Làm mới</vs-button>
                  </div>
                </div>
              </div>
            </crm-filter-bar>
            <!-- <div class="bulk-check-all-wrap" v-if="list.length > 0">
              <label class="bulk-check-all">
                <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll">
                <span>Chọn tất cả</span>
              </label>
            </div> -->
            <div class="crm-list-bulk-toolbar" v-if="selectedIds.length > 0">
              <span class="crm-list-bulk-count">Đã chọn {{ selectedIds.length }}</span>
              <div class="crm-list-bulk-actions">
                <select class="form-control crm-list-bulk-status-select" v-model="bulkStatusValue">
                  <option value="">Đổi trạng thái</option>
                  <option :value="1">Hiển thị</option>
                  <option :value="0">Ẩn</option>
                </select>
                <vs-button color="warning" type="filled" :disabled="selectedIds.length === 0" @click="confirmBulkDuplicate">
                  Nhân bản ({{ selectedIds.length }})
                </vs-button>
                <vs-button color="success" type="filled" :disabled="!canApplyBulkStatus" @click="applyBulkStatus">
                  Áp dụng
                </vs-button>
                <vs-button color="danger" type="border" :disabled="selectedIds.length === 0" @click="confirmBulkDelete">
                  Xóa đã chọn ({{ selectedIds.length }})
                </vs-button>
              </div>
            </div>
            <vs-table stripe :data="list" max-items="30" pagination>
              <template slot="thead">
                <vs-th>
                  <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll">
                </vs-th>
                <vs-th>Ảnh sản phẩm</vs-th>
                <vs-th>Tên sản phẩm</vs-th>
                <vs-th>Kho</vs-th>
                <vs-th>Số lượng</vs-th>
                <vs-th>Trạng thái</vs-th>
                <vs-th>Hành động</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td>
                    <input
                      type="checkbox"
                      :checked="selectedIds.includes(tr.id)"
                      @change="toggleSelection(tr.id)"
                    >
                  </vs-td>
                  <vs-td ><vs-avatar size="large" :src="JSON.parse(tr.images)[0]"/></vs-td>
                  <vs-td>{{ tr.name }}</vs-td>
                  <vs-td v-if="tr.cate != null">{{JSON.parse(tr.cate)[0].content}}</vs-td>
                  <vs-td v-if="tr.cate == null">--Trống---</vs-td>
                  <vs-td >{{tr.qty}}</vs-td>
                  <vs-td>
                    <span :class="tr.status == 1 ? 'crm-list-status-chip crm-list-status-on' : 'crm-list-status-chip crm-list-status-off'">
                      {{ tr.status == 1 ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                  </vs-td>
                  <vs-td >
                    <router-link :to="{name:'edit_product',params:{id:tr.id}}">
                      <vs-button
                        vs-type="gradient"
                        size="lagre"
                        color="success"
                        icon="edit"
                      ></vs-button>
                    </router-link>
                    <vs-button
                      vs-type="gradient"
                      size="lagre"
                      color="warning"
                      icon="content_copy"
                      @click="confirmDuplicate(tr.id)"
                    ></vs-button>
                    <vs-button vs-type="gradient" size="lagre" color="red" icon="delete_forever" @click="confirmDestroy(tr.id)"></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </div>
    </div>
</template>


<script>
import Swal from "sweetalert2";
import { mapActions } from "vuex";
export default {
  data() {
    return {
      list:[],
      categories: [],
      typeCates: [],
      typeTwos: [],
      filters: {
        keyword: "",
        category: 0,
        type_cate: 0,
        type_two: 0,
        price_sort: "",
        created_sort: ""
      },
      selectedIds: [],
      bulkStatusValue: "",
      objDel:{
        id_item:"",
        slug:"",
      }
      
    };
  },
  components: {},
  computed: {
    isAllSelected() {
      return this.list.length > 0 && this.selectedIds.length === this.list.length;
    },
    canApplyBulkStatus() {
      return this.selectedIds.length > 0 && this.bulkStatusValue !== "";
    }
  },
  watch: {},
  methods: {
    ...mapActions(['listProduct','deleteId','duplicateId','bulkDeleteProducts','bulkUpdateProductStatus','bulkDuplicateProducts','loadings','listCate','findTypeCate','findTypeCateTwo']),
    getFilterPayload() {
      return {
        keyword: this.filters.keyword,
        category: this.filters.category,
        type_cate: this.filters.type_cate,
        type_two: this.filters.type_two,
        price_sort: this.filters.price_sort,
        created_sort: this.filters.created_sort
      };
    },
    fetchCategories(){
      this.listCate({}).then((response) => {
        this.categories = response.data || [];
      });
    },
    onCategoryChange(){
      this.filters.type_cate = 0;
      this.filters.type_two = 0;
      this.typeTwos = [];
      if(this.filters.category != 0){
        this.findTypeCate(this.filters.category).then((response) => {
          this.typeCates = response.data || [];
          this.listProducts();
        });
      }else{
        this.typeCates = [];
        this.listProducts();
      }
    },
    onTypeCateChange(){
      this.filters.type_two = 0;
      if(this.filters.type_cate != 0){
        this.findTypeCateTwo(this.filters.type_cate).then((response) => {
          this.typeTwos = response.data || [];
          this.listProducts();
        });
      }else{
        this.typeTwos = [];
        this.listProducts();
      }
    },
    listProducts(){
      this.loadings(true);
      this.listProduct(this.getFilterPayload())
      .then(response => {
          this.loadings(false);
          this.list = response.data;
          this.selectedIds = [];
      }).catch(err => {
          this.loadings(false);
          this.list = err.data;
          this.selectedIds = [];
      });
    },
    searchProduct() {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }
      this.timer = setTimeout(() => {
          this.listProduct(this.getFilterPayload())
          .then(response => {
            this.list = response.data;
            this.selectedIds = [];
          });
      }, 800);
    },
    toggleSelection(id) {
      if (this.selectedIds.includes(id)) {
        this.selectedIds = this.selectedIds.filter(item => item !== id);
      } else {
        this.selectedIds.push(id);
      }
    },
    toggleSelectAll() {
      if (this.isAllSelected) {
        this.selectedIds = [];
        return;
      }
      this.selectedIds = this.list.map(item => item.id);
    },
    applyBulkStatus() {
      if (!this.canApplyBulkStatus) return;
      this.bulkUpdateProductStatus({
        ids: this.selectedIds,
        status: this.bulkStatusValue
      }).then(() => {
        this.$success('Cập nhật trạng thái thành công');
        this.bulkStatusValue = "";
        this.listProducts();
      });
    },
    confirmBulkDelete() {
      if (this.selectedIds.length === 0) return;
      this.$vs.dialog({
        type:'confirm',
        color: 'danger',
        title: `Bạn có chắc chắn`,
        text: `Xóa ${this.selectedIds.length} sản phẩm đã chọn`,
        accept:this.bulkDelete
      });
    },
    confirmBulkDuplicate() {
      if (this.selectedIds.length === 0) return;
      this.$vs.dialog({
        type:'confirm',
        color: 'warning',
        title: `Bạn có chắc chắn`,
        text: `Nhân bản ${this.selectedIds.length} sản phẩm đã chọn`,
        accept:this.bulkDuplicate
      });
    },
    bulkDelete() {
      this.bulkDeleteProducts({
        ids: this.selectedIds
      }).then(() => {
        this.$success('Xóa nhiều sản phẩm thành công');
        this.listProducts();
      });
    },
    bulkDuplicate() {
      this.bulkDuplicateProducts({
        ids: this.selectedIds
      }).then(() => {
        this.$success('Nhân bản nhiều sản phẩm thành công');
        this.listProducts();
      });
    },
    resetFilters(){
      this.filters = {
        keyword: "",
        category: 0,
        type_cate: 0,
        type_two: 0,
        price_sort: "",
        created_sort: ""
      };
      this.typeCates = [];
      this.typeTwos = [];
      this.bulkStatusValue = "";
      this.selectedIds = [];
      this.listProducts();
    },
    destroy(){
      this.deleteId(this.objDel).then(response => {
        this.listProducts();
        this.$success('xóa thành công');
      });
    },
    duplicate(){
      this.duplicateId(this.objDel).then(() => {
        this.listProducts();
        this.$success('Nhân bản sản phẩm thành công');
      });
    },
    confirmDestroy(id,slug){
      this.objDel.id_item = id;
      this.$vs.dialog({
        type:'confirm',
        color: 'danger',
        title: `Bạn có chắc chắn`,
        text: 'Xóa sản phẩm này',
        accept:this.destroy
      })
    },
    confirmDuplicate(id){
      this.objDel.id_item = id;
      this.$vs.dialog({
        type:'confirm',
        color: 'warning',
        title: `Bạn có chắc chắn`,
        text: 'Nhân bản sản phẩm này',
        accept:this.duplicate
      })
    }
  },
  mounted() {
    this.fetchCategories();
    this.listProducts();
  }
};
</script>