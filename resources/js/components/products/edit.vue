<template>
  <div>
      <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>Tên sản phẩm</label>
                <vs-input
                  type="text"
                  size="default"
                  placeholder="Tên sản phẩm"
                  class="w-100"
                   v-model="objData.name"
                />
              </div> 
              <div class="form-group">
                <label>Nội dung</label>
                <TinyMce
                  v-model="objData.content[0].content"
                />
                <el-button size="small" @click="showSettingLangExist('content')">Đa ngôn ngữ</el-button>
                 <div class="dropLanguage" v-if="showLang.content == true">
                    <div class="form-group" v-for="item,index in lang" :key="index">
                        <label v-if="index != 0">{{item.name}}</label>
                        <TinyMce v-if="index != 0" v-model="objData.content[index].content" />
                        
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label>Mô tả ngắn</label>
                <vs-textarea v-model="objData.description[0].content" />
                <el-button size="small" @click="showSettingLangExist('description')">Đa ngôn ngữ</el-button>
                 <div class="dropLanguage" v-if="showLang.description == true">
                    <div class="form-group" v-for="item,index in lang" :key="index">
                        <label v-if="index != 0">{{item.name}}</label>
                        <vs-textarea v-if="index != 0" v-model="objData.description[index].content" />
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label>Ảnh sản phẩm</label>
                <ImageMulti v-model="objData.images" :title="'san-pham'"/>
              </div>
              <div class="form-group">
                <label>Thêm biến thể (Bạn có thể thêm biến thể nếu sản phẩm này có nhiều lựa chọn như kích cỡ hoặc màu sắc. )</label>
                <vs-switch v-model="objData.status_variant">
                  <span slot="on">On</span>
                  <span slot="off">Off</span>
                </vs-switch>
              </div>
              <div class="variant" v-if="objData.status_variant == true " style="background: #f6f6fb; padding: 7px;">
                <div v-if="VariantSku.length > 0">
                    <div>
                      <vs-table :data="VariantSku">
                        <template slot="thead">
                          <vs-th>
                            Phiên bản
                          </vs-th>
                          <vs-th>
                            Giá
                          </vs-th>
                          <vs-th>
                            Số lượng
                          </vs-th>
                        </template>

                        <template slot-scope="{data}">
                          <vs-tr :key="indextr" v-for="(tr, indextr) in VariantSku" >
                            <vs-td>
                              {{data[indextr].version}}
                            </vs-td>

                            <vs-td>
                              <vs-input
                                type="text"
                                size="default"
                                class="w-100"
                                v-model="data[indextr].price"
                              />
                            </vs-td>
                            <vs-td >
                              <vs-input
                                type="text"
                                size="default"
                                class="w-100"
                                v-model="data[indextr].qty"
                              />
                            </vs-td>
                          </vs-tr>
                        </template>
                      </vs-table>
                    </div>
                </div>
                <div class="variant" v-if="objData.status_variant == true && VariantSku.length == 0"> 
                    <div class="" v-for="item, index in variant_value" :key="index">
                      <label for="" class="label2">{{item.name}}</label>
                      <ul class="centerx">
                        <li v-for="itemval, key in item.value" :key="key">
                          <vs-checkbox :vs-value="itemval.name" @change="getValuebuildSkua(itemval.name,item.name)" >{{itemval.name}}</vs-checkbox>
                        </li>
                      </ul>
                    </div>
                    <div>
                      <vs-table :data="objData.lungtung">
                        <template slot="thead">
                          <vs-th>
                            Phiên bản
                          </vs-th>
                          <vs-th>
                            Giá
                          </vs-th>
                          <vs-th>
                            Số lượng
                          </vs-th>
                        </template>

                        <template slot-scope="{data}">
                          <vs-tr :key="indextr" v-for="(tr, indextr) in objData.lungtung" >
                            <vs-td>
                              {{data[indextr].version}}
                            </vs-td>

                            <vs-td>
                              <vs-input
                                type="text"
                                size="default"
                                class="w-100"
                                v-model="data[indextr].price"
                              />
                            </vs-td>
                            <vs-td >
                              <vs-input
                                type="text"
                                size="default"
                                class="w-100"
                                v-model="data[indextr].qty"
                              />
                            </vs-td>
                          </vs-tr>
                        </template>
                      </vs-table>
                    </div>
              </div>
              </div>
              
              <div class="form-group">
                <label>Giá Sản phẩm</label>
                <vs-input
                  type="number"
                  size="default"
                  icon="all_inclusive"
                  class="w-100"
                  v-model="objData.price"
                />
              </div>
              <div class="form-group" v-if="objData.status_variant == false">
                <label>Giá bán ra</label>
                <vs-input
                  type="number"
                  size="default"
                  icon="all_inclusive"
                  class="w-100"
                  v-model="objData.discount"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>Trạng thái</label>
                <vs-select v-model="objData.status"
                  >
                  <vs-select-item  value="1" text="Còn hàng" />
                  <vs-select-item  value="0" text="Hết hàng" />
                </vs-select>
              </div>
              <div class="form-group">
                <label>Danh mục sản phẩm</label>
                <vs-select
                  class="selectExample"
                  v-model="objData.category"
                  placeholder="Danh mục"
                  @change="findCategoryType()"
                >
                <vs-select-item
                    value="0"
                    text="Không danh mục"
                  />
                  <vs-select-item
                    :value="item.id"
                    :text="JSON.parse(item.name)[0].content"
                    v-for="(item, index) in cate"
                    :key="'f' + index"
                  />
                </vs-select>
              </div>
              <div class="form-group">
                <label>Danh mục cấp 1</label>
                <vs-select
                  class="selectExample"
                  v-model="objData.type_cate"
                  placeholder="Loại"
                  :disabled=" type_cate.length == 0"
                  @change="findCategoryTypeTwo()"
                >
                  <vs-select-item
                    :value="item.id"
                    :text="JSON.parse(item.name)[0].content"
                    v-for="(item, index) in type_cate"
                    :key="'v' + index"
                  />
                </vs-select>
              </div>
              <div class="form-group">
                <label>Danh mục cấp 2</label>
                <vs-select
                  class="selectExample"
                  v-model="objData.type_two"
                  placeholder="Loại"
                  :disabled=" type_two.length == 0"
                >
                  <vs-select-item
                    :value="item.id"
                    :text="JSON.parse(item.name)[0].content"
                    v-for="(item, index) in type_two"
                    :key="'v' + index"
                  />
                </vs-select>
              </div>
              <div class="form-group">
                <label>Thẻ tags cho sản phẩm</label>
                <vs-select
                    multiple
                    class="selectExample"
                    v-model="objData.tags"
                    placeholder="--Chọn--"
                    >
                    <div :key="index" v-for="item,index in tags">
                      <vs-select-group :title="item.name" v-if="item.tags">
                        <vs-select-item :key="index" :value="i.slug+'-'+item.slug" :text="i.name" v-for="i,index in item.tags"/>
                      </vs-select-group>
                    </div>
                </vs-select>
              </div>
             <div class="form-group">
                <label>Thông số kỹ thuật</label>
                <div v-for="(item, index) in objData.size" :key="index">
                  <div class="row">
                    <div class="col-11">
                      <vs-input
                        type="text"
                        size="default"
                        :placeholder="'Tiêu đề ' + index"
                        class="w-100"
                        v-model="objData.size[index].title"
                      />
                      <vs-input
                        type="text"
                        size="default"
                        :placeholder="'Thông số ' + index"
                        class="w-100"
                        v-model="objData.size[index].detail"
                      />
                      <br />
                    </div>
                    <div class="col-1">
                      <a
                        href="javascript:;"
                        v-if="index != 0"
                        @click="remoteAr(index,'size')"
                      >
                        <img v-bind:src="'/media/' + joke.avatar" width="25" />
                      </a>
                    </div>
                  </div>
                </div>

                <el-button size="small" @click="addInput('size')"
                  >Thêm thông số</el-button
                >
              </div>
              <div class="form-group">
                <label>Thông tin khuyến mãi</label>
                <div v-for="(item, i) in objData.preserve" :key="i">
                  <div class="row">
                    <div class="col-11">
                      <vs-input
                        type="text"
                        size="default"
                        :placeholder="'Khuyến mãi ' + i"
                        class="w-100"
                        v-model="objData.preserve[i].detail"
                      />
                      <br />
                    </div>
                    <div class="col-1">
                      <a
                        href="javascript:;"
                        v-if="i != 0"
                        @click="remoteAr(i,'preserve')"
                      >
                        <img v-bind:src="'/media/' + joke.avatar" width="25" />
                      </a>
                    </div>
                  </div>
                </div>

                <el-button size="small" @click="addInput('preserve')"
                  >Thêm khuyến mãi</el-button
                >
              </div>
              <div class="form-group">
                <label>Slug sản phẩm</label>
                <vs-input
                  class="w-100"
                  :value="seoData.slug"
                  placeholder="duong-dan-san-pham"
                  @input="onSlugInput"
                />
              </div>
              <seo-assistant
                :model="seoData"
                :article-title="objData.name"
                :article-content="objData.content[0].content"
                :article-description="objData.description[0].content"
                preview-path="san-pham"
                @update:model="seoData = $event"
                @auto-optimize="autoOptimizeSeo"
                @insert-keyword="insertKeywordToContent"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row fixxed">
        <div class="col-12">
          <div class="saveButton">
            <vs-button color="primary" @click="saveProducts">Cập nhật</vs-button>
          </div>
        </div>
      </div>
    <!-- content-wrapper ends -->
  </div>
</template>


<script>
import { mapActions } from "vuex";
import TinyMce from "../_common/tinymce";
import ImageMulti from "../_common/upload_image_multi";
import SeoAssistant from "../_common/seo_assistant";
// import InputTag from 'vue-input-tag'
export default {
  name: "product",
  data() {
    return {
      showLang:{
        title:false,
        content:false,
        description:false
      },
      tags: [],
      joke: {
        avatar: "delete-sign--v2.png",
      },
      errors:[],
      cate: [],
      VariantSku:[],
      variant_value:[],
      variant:[],
      variant_item:{},
      objData: {
        id:0,
        lang: "",
        variant:[],
        name: "",
        size: [
          {
            title: "",
            detail: ""
          },
        ],
        tags:[],
        price: 0,
        discount: 0,
        preserve:[
          {
            detail: ""
        }
        ],
        ingredient:'',
        images: [],
        qty: "",
        description: [
          {
            lang_code: "vi",
            content: "",
          },
        ],
        content: [
          {
            lang_code: "vi",
            content: "",
          },
        ],
        category: 0,
        status: 1,
        discountStatus:0,
        type_cate: 0,
        type_two:0,
        origin: "",
        thickness: "",
        hang_muc: "",
        service_id:0,
        lungtung:[],
        status_variant: 0
      },
      seoData: {
        focusKeyword: "",
        seoTitle: "",
        metaDescription: "",
        slug: ""
      },
      syncPaused: false,
      lang:[],
      type_cate: [],
      type_two:[],
      
      
    };
  },
  components: {
    TinyMce,
    ImageMulti,
    SeoAssistant
  },
  computed: {},
  watch: {
    "objData.name"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.seoData.seoTitle = value || "";
        if (!this.seoData.slug) {
          this.seoData.slug = this.slugify(value || "");
        }
      });
    },
    "objData.description.0.content"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.seoData.metaDescription = (value || "").slice(0, 160);
      });
    },
    "seoData.seoTitle"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.objData.name = value || "";
        if (!this.seoData.slug) {
          this.seoData.slug = this.slugify(value || "");
        }
      });
    },
    "seoData.metaDescription"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.objData.description[0].content = value || "";
      });
    }
  },
  methods: {
    ...mapActions(["editId", "saveProduct", "listCate","listLanguage", "loadings","findTypeCate","findTypeCateTwo","findTags","listVariantSku"]),
    showSettingLang(value){
      if(value == "title"){
        this.showLang.title = !this.showLang.title
      }
      
    },
    showSettingLangExist(value,name = "content"){
      if(value == "content"){
        this.showLang.content = !this.showLang.content
          this.lang.forEach((value, index) => {
              if(!this.objData.content[index]){
                  var oj = {};
                  oj.lang_code = value.code;
                  oj.content = ''
                  this.objData.content.push(oj)
              }
          });
      }
      if(value == "description"){
        this.showLang.description = !this.showLang.description
          this.lang.forEach((value, index) => {
              if(!this.objData.description[index]){
                  var oj = {};
                  oj.lang_code = value.code;
                  oj.content = ''
                  this.objData.description.push(oj)
              }
          });
      }
      
    },
    saveProducts() {
      this.errors = [];
      if(this.objData.name == '') this.errors.push('Tên không được để trống');
      if(this.objData.content[0].content == '') this.errors.push('Nội dung không được để trống');
      if(this.objData.description[0].content == '') this.errors.push('Mô tả không được để trống');
      if(this.objData.images.length == 0) this.errors.push('Vui lòng chọn ảnh');
      if(this.objData.category == 0) this.errors.push('Chọn danh mục sản phẩm');
      if (this.errors.length > 0) {
        this.errors.forEach((value, key) => {
          this.$error(value)
        })
        return;
      }else {
        this.loadings(true);
        this.objData.lungtung = this.VariantSku;
        const payload = {
          ...this.objData,
          seo_title: this.seoData.seoTitle,
          meta_description: this.seoData.metaDescription,
          focus_keyword: this.seoData.focusKeyword,
          slug: this.seoData.slug || this.slugify(this.objData.name)
        };
        this.saveProduct(payload)
          .then(response => {
            this.loadings(false);
            this.$router.push({name:'listProduct'});
            this.$success('Sửa sản phẩm thành công');
            // this.$route.push({name:'listProduct'});
          })
          .catch(error => {
            this.loadings(false);
            this.$error('Sửa sản phẩm thất bại');
          });
      }
    },
    listVariantSkuValue(){
      this.listVariantSku({id:this.$route.params.id}).then(response => {
        this.VariantSku = response.data;
      }).catch(error => {
        console.log(12);
      });
    },
    editById() {
      this.loadings(true);
      this.editId({id:this.$route.params.id}).then(response => {
        this.loadings(false);
          this.withSyncPause(() => {
            this.objData = response.data;
            this.objData.images = JSON.parse(response.data.images);
            this.objData.content = JSON.parse(response.data.content);
            this.objData.description = JSON.parse(response.data.description);
            this.objData.tags = JSON.parse(response.data.tags);
            if(response.data.size == ""){
              this.objData.size = [{title: "",detail: ""}]
            }else{
              this.objData.size = JSON.parse(response.data.size);
            }
            if(response.data.preserve == null){
              this.objData.preserve = [{detail: ""}]
            }else{
              this.objData.preserve = JSON.parse(response.data.preserve);
            }
            this.seoData.seoTitle = response.data.seo_title || response.data.name || "";
            this.seoData.metaDescription = response.data.meta_description || this.objData.description[0].content || "";
            this.seoData.focusKeyword = response.data.focus_keyword || "";
            this.seoData.slug = response.data.slug || this.slugify(response.data.name || "");
          });
      }).catch(error => {
        console.log(12);
      });
    },
    listLang(){
      this.listLanguage().then(response => {
        this.loadings(false);
        this.lang  = response.data
      }).catch(error => {
      })
    },
    changeLanguage(data){
      this.editById();
    },
    findCategoryType(){
      this.findTypeCate(this.objData.category).then(response => {
          this.type_cate = response.data;
        });
        this.findTags(this.objData.category).then((response) => {
        this.tags = response.data;
      });
    },
    removeVariant(key){
      this.VariantSku.splice(key,1);
    },
    remoteAr(index,key) {
      if(key == 'size'){
        this.objData.size.splice(index, 1);
      }
      if(key == 'preserve'){
        this.objData.preserve.splice(index, 1);
      }
        
    },
    addInput(key) {
        var oj = {};
        if(key =='size'){
          oj.title = "";
          oj.detail = "";
          this.objData.size.push(oj);
        }
        if(key =='preserve'){
          oj.detail = "";
          this.objData.preserve.push(oj);
        }
        
    },
    findCategoryTypeTwo() {
      this.findTypeCateTwo(this.objData.type_cate).then((response) => {
        this.type_two = response.data;
        if(this.type_two.length == 0){
          this.objData.type_two == 0;
        }
      });
    },
    slugify(value) {
      return (value || "")
        .toString()
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d")
        .replace(/[^a-z0-9\s-]/g, "")
        .trim()
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-");
    },
    onSlugInput(value) {
      this.seoData.slug = this.slugify(value);
    },
    autoOptimizeSeo() {
      const title = (this.objData.name || "").trim();
      const shortDesc = (this.objData.description[0].content || "").trim();
      this.withSyncPause(() => {
        if (!this.seoData.focusKeyword) {
          this.seoData.focusKeyword = this.slugify(title).replace(/-/g, " ").split(" ").slice(0, 4).join(" ");
        }
        this.seoData.seoTitle = title ? `${title} | CuBon` : this.seoData.seoTitle;
        this.seoData.metaDescription = (shortDesc || "").slice(0, 160);
        this.seoData.slug = this.slugify(title);
      });
    },
    insertKeywordToContent({ target, keyword }) {
      if (!keyword) return;
      this.withSyncPause(() => {
        if (target === "title") {
          if (!this.objData.name.toLowerCase().includes(keyword.toLowerCase())) {
            this.objData.name = `${this.objData.name} ${keyword}`.trim();
          }
        } else if (target === "description") {
          if (!this.objData.description[0].content.toLowerCase().includes(keyword.toLowerCase())) {
            this.objData.description[0].content = `${this.objData.description[0].content} ${keyword}`.trim();
          }
        }
      });
    },
    withSyncPause(callback) {
      this.syncPaused = true;
      callback();
      this.$nextTick(() => {
        this.syncPaused = false;
      });
    }
  },
  mounted() {
    this.editById();
    this.listCate().then(response => {
      this.cate = response.data;
    });
    this.listLang();
    this.listVariantSkuValue();
  }
};
</script>
<style>
  .dropLanguage{
        padding: 11px;
        border: solid 1px #f2edf3;
        border-radius: 11px;
        background: #f2edf3;
        margin-top: 5px
  }
</style>