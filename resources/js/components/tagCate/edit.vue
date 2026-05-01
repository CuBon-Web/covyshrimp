<template>
  <div>
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <h4 class="card-title">Sửa danh mục tag</h4>
                </div>
              </div>

              <!-- <p class="card-description">Basic form elements</p> -->
              <form class="forms-sample">
                <div class="form-group">
                <label for="exampleInputName1">Tên danh mục</label>
                  <vs-input
                    class="w-100"
                    v-model="objData.name"
                    :class="{ 'is-invalid': submitted && $v.objData.name.$error }"
                    font-size="40px"
                  />
                </div>
                <div class="form-group">
                <label for="exampleInputName1">Danh mục cha</label>
                  <vs-select class="selectExample" v-model="objData.cate_product_id" placeholder="Danh mục">
                  <vs-select-item
                    :value="0"
                    text="Danh mục cha"
                  />
                  <vs-select-item
                    :value="item.id"
                    :text="JSON.parse(item.name)[0].content"
                    v-for="(item,index) in categoryList"
                    :key="'f'+index"
                  />
                </vs-select>
                </div>
                <div class="form-group">
                  <label for="exampleInputName1">Trạng thái hiển thị menu</label>
                  <vs-select v-model="objData.status"
                  >
                    <vs-select-item  value="1" text="Hiện" />
                    <vs-select-item  value="0" text="Ẩn" />
                  </vs-select>
                </div>
                <div class="form-group">
                  <label for="exampleInputName1">Trạng thái hiển thị trong bộ lọc</label>
                  <vs-select v-model="objData.status_filter"
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
import { required } from "vuelidate/lib/validators";
import TinyMce from "../_common/tinymce";
export default {
  data() {
    return {
      objData: {
        language: this.$route.params.language,
        id:this.$route.params.id,
        name: "",
        status: 1,
        cate_product_id:"",
      },
      categoryList:[],
      lang: [],
      img: "",
      submitted: false
    };
  },
  components: {
    TinyMce,
  },
  validations: {
    objData: {
      name: { required },
      cate_id: { required }
    }
  },
  methods: {
    ...mapActions([
      "getInfoTagCate",
      "saveTagCate",
      "listLanguage",
      "loadings","listCate"
    ]),
    nameImage(event) {
      this.objData.avatar = event;
    },
    listCategory() {
      this.loadings(true);
      this.listCate()
      .then(response => {
          this.loadings(false);
          this.categoryList = response.data;
        });
    },
    saveEdit() {
      this.errors = [];
      if(this.objData.name == '') this.errors.push('Tên danh mục không được để trống');
      if(this.objData.cate_product_id == '') this.errors.push('Danh mục cha không được để trống');
      if (this.errors.length > 0) {
        this.errors.forEach((value, key) => {
          this.$error(value)
        })
        return;
      } else {
        this.loadings(true);
        this.saveTagCate(this.objData)
          .then(response => {
            this.$router.push({ name: "list_tag_cate" });
            this.$success("Sửa danh mục tag thành công");
            this.$router.push({ name: "list_tag_cate" });
          })
          .catch(error => {
            this.loadings(false);
            this.$error("Sửa danh mục thất bại");
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
    getInfoCates() {
      this.loadings(true);
      this.getInfoTagCate(this.objData)
        .then(response => {
          this.loadings(false);
          if (response.data == null) {
            this.objData = {
              id: this.$route.params.id,
              name: "",
            status: 1,
            cate_product_id:"",
            };
          } else {
            this.objData = response.data;
          }
        })
        .catch(error => {
          console.log(12);
        });
    },
    changeLanguage(data) {
      this.getInfoCates();
    }
  },
  mounted() {
    this.loadings(true);
    this.getInfoCates();
    this.listLang();
    this.listCategory();
  }
};
</script>

<style>
</style>