<template>
  <div>
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <h4 class="card-title">Thêm mới tags</h4>
                </div>
              </div>
              <!-- <p class="card-description">Basic form elements</p> -->
              <form class="forms-sample">
                <div class="form-group">
                  <vs-input
                    class="w-100"
                    v-model="objData.name"
                    :class="{ 'is-invalid': submitted && $v.objData.name.$error }"
                    font-size="40px"
                    label-placeholder="Tên tags"
                  />
                </div>
                <div class="form-group">
                   <label for="exampleInputName1">Danh mục tag</label>
                  <vs-select class="selectExample" v-model="objData.cate_tag_id" placeholder="Danh mục">
                  <vs-select-item
                    :value="0"
                    text="Danh mục tag"
                  />
                  
                  <vs-select-item v-if="item.cate_product != null"
                    :value="item.id"
                    :text="item.name + '(' + JSON.parse(item.cate_product.name)[0].content + ')'"
                    v-for="(item,index) in categoryList"
                    :key="'f'+index"
                  />
                  <vs-select-item v-if="item.cate_product == null"
                    :value="item.id"
                    :text="item.name + '(không thuộc danh mục nào)'"
                    v-for="(item,index) in categoryList"
                    :key="'f'+index"
                  />
                </vs-select>
                </div>
                <div class="form-group">
                  <label for="exampleInputName1">Trạng thái</label>
                  <vs-select v-model="objData.status"
                  >
                    <vs-select-item  value="1" text="Hiện" />
                    <vs-select-item  value="0" text="Ẩn" />
                  </vs-select>
                </div>
                <vs-button
                  color="success"
                  type="gradient"
                  class="mr-left-45"
                  @click="saveEdit()"
                >Lưu lại</vs-button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <!-- content-wrapper ends -->
  </div>
</template>

<script>
import { mapActions } from "vuex";
import TinyMce from "../_common/tinymce";
export default {
  data() {
    return {
      objData: {
        name: "",
        status: 1,
        cate_tag_id:"",
        cate_product_id:""
      },
      categoryList:[],
      categoryPro:[],
      lang: [],
      img: "",
      submitted: false
    };
  },
  components: {
    TinyMce,
  },
  methods: {
    ...mapActions([
      "saveTag",
      "listLanguage",
      "loadings","listTagCate","listCate"
    ]),
    nameImage(event) {
      this.objData.avatar = event;
    },
    listCatePro() {
      this.loadings(true);
      this.listCate()
      .then(response => {
          this.loadings(false);
          this.categoryPro = response.data;
        });
    },
    listCategory() {
      this.loadings(true);
      this.listTagCate()
      .then(response => {
          this.loadings(false);
          this.categoryList = response.data;
        });
    },
    saveEdit() {
      this.errors = [];
      if(this.objData.name == '') this.errors.push('Tên tag không được để trống');
      if(this.objData.cate_tag_id == '') this.errors.push('Danh mục tags không được để trống');
      if (this.errors.length > 0) {
        this.errors.forEach((value, key) => {
          this.$error(value)
        })
        return;
      } else {
        this.loadings(true);
        this.saveTag(this.objData)
          .then(response => {
            this.loadings(false);
            this.$router.push({ name: "list_tag" });
            this.$success("Thêm tags thành công");
            this.$router.push({ name: "list_tag" });
          })
          .catch(error => {
            this.loadings(false);
            this.$error("Thêm tags thất bại");
          });
      }
    },
    listLang() {
      this.listLanguage()
        .then(response => {
          this.loadings(false);
          this.lang = response.data;
        })
        .catch(error => {});
    },
  },
  mounted() {
    this.loadings(true);
    this.listLang();
    this.listCategory();
    this.listCatePro();
  }
};
</script>

<style>
</style>