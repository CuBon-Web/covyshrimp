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
                  :focus-keyword="seoData.focusKeyword"
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
              <div class="row">
              <div class="form-group col-6">
                <label>Giá Sản phẩm</label>
                <vs-input
                  type="number"
                  size="default"
                  class="w-100"
                  v-model="objData.price"
                />
              </div>
              <div class="form-group col-6" v-if="variantstatus == false">
                <label>Giá bán ra</label>
                <vs-input
                  type="number"
                  size="default"
                  class="w-100"
                  v-model="objData.discount"
                />
              </div>
              </div>
              <div class="form-group variant-manager">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <label class="mb-0">Thuộc tính biến thể</label>
                  <label class="variant-toggle">
                    <input type="checkbox" v-model="variantstatus" @change="handleVariantToggle" />
                    Bật biến thể
                  </label>
                </div>
                <div v-if="variantstatus">
                  <div class="variant-library-actions mb-2">
                    <el-button size="mini" type="primary" plain @click="saveCurrentVariantsToLibrary">
                      Lưu mẫu variant
                    </el-button>
                    <el-button size="mini" @click="applyVariantLibrary" :disabled="variantLibrary.length === 0 || selectedVariantLibraryIds.length === 0">
                      Dùng mẫu đã lưu
                    </el-button>
                    <vs-select multiple class="variant-library-select" v-model="selectedVariantLibraryIds" placeholder="Chọn nhiều mẫu từ database">
                      <vs-select-item
                        v-for="item in variantLibrary"
                        :key="item.id"
                        :value="item.id"
                        :text="item.name"
                      />
                    </vs-select>
                  </div>
                  <div class="variant-option-card" v-for="(option, optionIndex) in variantOptions" :key="option.id">
                    <div class="d-flex align-items-center mb-2">
                      <vs-input
                        class="w-100"
                        placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
                        v-model="option.name"
                        @input="onVariantOptionChange"
                      />
                      <el-button
                        class="ml-2"
                        size="mini"
                        type="danger"
                        icon="el-icon-delete"
                        circle
                        @click="removeVariantOption(optionIndex)"
                      />
                    </div>
                    <div class="d-flex align-items-center mb-2">
                      <vs-input
                        class="w-100"
                        placeholder="Nhập giá trị (VD: Đỏ, XL)"
                        v-model="option.draft"
                        @keyup.enter.native="addVariantValue(optionIndex)"
                      />
                      <el-button class="ml-2" size="mini" type="primary" @click="addVariantValue(optionIndex)">
                        Thêm
                      </el-button>
                    </div>
                    <div class="variant-value-list" v-if="option.values.length > 0">
                      <span class="variant-value-tag" v-for="(value, valueIndex) in option.values" :key="value.id">
                        {{ value.label }}
                        <button type="button" @click="removeVariantValue(optionIndex, valueIndex)">x</button>
                      </span>
                    </div>
                  </div>
                  <el-button size="small" type="success" plain @click="createVariantOption">
                    + Thêm thuộc tính
                  </el-button>

                  <div class="variant-combination-table mt-3" v-if="objData.lungtung.length > 0">
                    <label class="mb-2">Danh sách biến thể tự động</label>
                    <div class="variant-bulk-actions mb-2">
                      <input
                        type="text"
                        inputmode="numeric"
                        class="variant-bulk-input variant-number-input"
                        placeholder="Giá hàng loạt"
                        :value="formatNumberDisplay(bulkVariant.price)"
                        @input="setBulkNumber('price', $event)"
                        @blur="syncBulkDisplay('price', $event)"
                        @change="syncBulkDisplay('price', $event)"
                      />
                      <input
                        type="text"
                        inputmode="numeric"
                        class="variant-bulk-input variant-number-input"
                        placeholder="Số lượng hàng loạt"
                        :value="formatNumberDisplay(bulkVariant.qty)"
                        @input="setBulkNumber('qty', $event)"
                        @blur="syncBulkDisplay('qty', $event)"
                        @change="syncBulkDisplay('qty', $event)"
                      />
                      <vs-input type="text" class="variant-bulk-input" placeholder="Tiền tố SKU (VD: SP-TSHIRT)" v-model="bulkVariant.skuPrefix" />
                      <el-button size="mini" type="primary" plain @click="applyBulkVariantChanges">Áp dụng hàng loạt</el-button>
                      <el-button size="mini" @click="autoGenerateSkus">Tạo SKU thông minh</el-button>
                    </div>
                    <p class="variant-insight mb-2">
                      Tổng: {{ variantInsights.total }} | SKU trống: {{ variantInsights.emptySku }} | Hết hàng: {{ variantInsights.zeroQty }}
                    </p>
                    <div class="table-responsive">
                      <table class="table table-bordered table-sm mb-0">
                        <thead>
                          <tr>
                            <th>Tổ hợp</th>
                            <th width="120">Giá</th>
                            <th width="120">Số lượng</th>
                            <th width="180">SKU</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in objData.lungtung" :key="item.version">
                            <td>{{ item.version }}</td>
                            <td>
                              <input
                                type="text"
                                inputmode="numeric"
                                class="w-100 variant-number-input"
                                :value="formatNumberDisplay(objData.lungtung[index].price)"
                                @input="setVariantNumber(index, 'price', $event)"
                                @blur="syncVariantDisplay(index, 'price', $event)"
                                @change="syncVariantDisplay(index, 'price', $event)"
                              />
                            </td>
                            <td>
                              <input
                                type="text"
                                inputmode="numeric"
                                class="w-100 variant-number-input"
                                :value="formatNumberDisplay(objData.lungtung[index].qty)"
                                @input="setVariantNumber(index, 'qty', $event)"
                                @blur="syncVariantDisplay(index, 'qty', $event)"
                                @change="syncVariantDisplay(index, 'qty', $event)"
                              />
                            </td>
                            <td>
                              <vs-input type="text" class="w-100" v-model="objData.lungtung[index].sku" />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <p class="mb-0 text-muted mt-2" v-else>
                    Thêm ít nhất 1 thuộc tính có giá trị để sinh biến thể tự động.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>Trạng thái</label>
                <vs-select v-model="objData.status">
                  <vs-select-item value="1" text="Còn hàng" />
                  <vs-select-item value="0" text="Hết hàng" />
                </vs-select>
              </div>
              <div class="form-group">
                <label>Số lượng</label>
                <vs-input
                  type="text"
                  size="default"
                  placeholder="Số lượng"
                  class="w-100"
                  v-model="objData.qty"
                />
              </div>
              <div class="form-group">
                <div class="compact-meta-box">
                  <div class="compact-meta-title">Thiết lập hiển thị</div>
                  <div class="compact-meta-grid">
                    <div>
                      <label class="compact-label">Danh mục sản phẩm</label>
                      <vs-select
                        class="selectExample w-100"
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
                    <div>
                      <label class="compact-label">Danh mục cấp 1</label>
                      <vs-select
                        class="selectExample w-100"
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
                    <div>
                      <label class="compact-label">Sản phẩm nổi bật</label>
                      <el-radio-group v-model="objData.discountStatus" size="mini" class="compact-radio-group">
                        <el-radio-button :label="1">Có</el-radio-button>
                        <el-radio-button :label="0">Không</el-radio-button>
                      </el-radio-group>
                    </div>
                    <div>
                      <label class="compact-label">Hiển thị trang chủ</label>
                      <el-radio-group v-model="objData.home_status" size="mini" class="compact-radio-group">
                        <el-radio-button :label="1">Có</el-radio-button>
                        <el-radio-button :label="0">Không</el-radio-button>
                      </el-radio-group>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group tag-manager">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <label class="mb-0">Thẻ tag sản phẩm</label>
                  <span class="tag-selected-count">{{ objData.tags.length }} đã chọn</span>
                </div>
                <p class="tag-manager-note mb-2">
                  Chọn nhanh tag có sẵn hoặc tạo danh mục/tag mới ngay tại đây.
                </p>
                <vs-select
                  multiple
                  class="selectExample w-100 tag-multi-select"
                  v-model="objData.tags"
                  :placeholder="isTagCategoryRequired ? '-- Vui lòng chọn danh mục sản phẩm trước --' : '-- Chọn thẻ tag --'"
                  :disabled="isTagCategoryRequired"
                >
                  <div :key="`tag-group-${item.slug}-${index}`" v-for="(item, index) in tags">
                    <vs-select-group :title="item.name" v-if="item.tags && item.tags.length > 0">
                      <vs-select-item
                        :key="`tag-item-${item.slug}-${tag.slug}-${tagIndex}`"
                        :value="buildTagValue(tag, item)"
                        :text="tag.name"
                        v-for="(tag, tagIndex) in item.tags"
                      />
                    </vs-select-group>
                  </div>
                </vs-select>
                <div v-if="isTagCategoryRequired" class="tag-category-warning mt-2">
                  <i class="el-icon-warning-outline"></i>
                  Bạn cần chọn Danh mục sản phẩm trước để hệ thống tải danh sách tag tương ứng.
                </div>
                <div class="tag-quick-create mt-2">
                  <div class="tag-create-row">
                    <vs-input
                      class="w-100"
                      v-model="tagEditor.categoryName"
                      placeholder="Tên danh mục tag mới"
                      @keyup.enter.native="createTagCategoryInline"
                    />
                    <el-button
                      size="mini"
                      type="primary"
                      plain
                      :loading="tagEditor.creatingCategory"
                      @click="createTagCategoryInline"
                    >
                      Tạo danh mục
                    </el-button>
                  </div>
                  <div class="tag-create-row">
                    <vs-select
                      class="selectExample w-100"
                      v-model="tagEditor.selectedCategorySlug"
                      placeholder="Chọn danh mục để tạo tag"
                    >
                      <vs-select-item
                        :key="`tag-cate-${item.slug}-${index}`"
                        :value="item.slug"
                        :text="item.name"
                        v-for="item, index in tags"
                      />
                    </vs-select>
                    <vs-input
                      class="w-100"
                      v-model="tagEditor.tagName"
                      placeholder="Tên tag mới"
                      @keyup.enter.native="createTagInline"
                    />
                    <el-button
                      size="mini"
                      type="success"
                      plain
                      :loading="tagEditor.creatingTag"
                      @click="createTagInline"
                    >
                      Tạo tag
                    </el-button>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="d-flex align-items-center justify-content-between mb-2">
                  <label class="mb-0">Thông số kỹ thuật</label>
                  <span class="tech-spec-count">{{ normalizeTechnicalSpecs(objData.size).length }} thông số</span>
                </div>
                <p class="tech-spec-note mb-2">Nhập nhanh, nhấn Enter ở ô thông số để thêm dòng mới.</p>
                <div class="tech-spec-grid-header">
                  <span>Tiêu đề</span>
                  <span>Chi tiết</span>
                  <span></span>
                </div>
                <div class="tech-spec-row" v-for="(item, index) in objData.size" :key="index">
                  <vs-input
                    type="text"
                    size="default"
                    :placeholder="'VD: CPU, RAM, Màn hình...'"
                    class="w-100"
                    v-model="objData.size[index].title"
                  />
                  <vs-input
                    type="text"
                    size="default"
                    :placeholder="'VD: Intel Core i5 / 16GB DDR4...'"
                    class="w-100"
                    v-model="objData.size[index].detail"
                    @keyup.enter.native="handleTechnicalSpecEnter(index)"
                  />
                  <el-button
                    size="mini"
                    type="danger"
                    icon="el-icon-delete"
                    circle
                    :disabled="objData.size.length === 1"
                    @click="removeTechnicalSpecRow(index)"
                  />
                </div>
                <el-button size="small" type="primary" plain @click="addTechnicalSpecRow">+ Thêm thông số</el-button>
              </div>
              <div class="form-group">
                <label>Slug sản phẩm</label>
                <vs-input
                  class="w-100"
                  v-model="seoData.slug"
                  placeholder="duong-dan-san-pham"
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
            <vs-button color="primary" @click="saveProducts"
              >Thêm mới</vs-button
            >
          </div>
        </div>
      </div>
    </div>
</template>


<script>
import { mapActions } from "vuex";
import TinyMce from "../_common/tinymce";
import ImageMulti from "../_common/upload_image_multi";
import SeoAssistant from "../_common/seo_assistant";
import "tinymce/icons/default/icons.min.js";
import InputTag from "vue-input-tag";
import InputColorPicker from "vue-native-color-picker";
export default {
  name: "product",
  data() {
    return {
      cate: [],
      joke: {
        avatar: "delete-sign--v2.png",
      },
      type_cate: [],
      tags: [],
      useGlobalTags: true,
      tagEditor: {
        categoryName: "",
        tagName: "",
        selectedCategorySlug: "",
        creatingCategory: false,
        creatingTag: false,
      },
      checkBox1:{
        roleid:[]
      },
      type_two:[],
      showLang: {
        title: false,
        content: false,
        description: false,
      },
      variantstatus:false,
      variantOptions: [],
      variantSeed: 0,
      variantLibrary: [],
      selectedVariantLibraryIds: [],
      bulkVariant: {
        price: null,
        qty: null,
        skuPrefix: "",
      },
      variantInsights: {
        total: 0,
        emptySku: 0,
        zeroQty: 0,
      },
      lang: [],
      errors: [],
      cateservice:[],
      cate_build_pc:[
        {
          name: 'Vi xử lý',
          value:'cpu'
        },
        {
          name: 'Bo mạch chủ',
          value:'mainboard'
        },
        {
          name: 'Ram',
          value:'ram'
        },
        {
          name: 'Ổ Cứng',
          value:'o-cung'
        },
        {
          name: 'VGA',
          value:'vga'
        },
        {
          name: 'Nguồn',
          value:'nguon'
        },
        {
          name: 'Vỏ Case',
          value:'case'
        },
        {
          name: 'Tản nhiệt',
          value:'tan-nhiet'
        },
        {
          name: 'Màn hình',
          value:'man-hinh'
        },
        {
          name: 'Bàn phím',
          value:'ban-phim'
        },
        {
          name: 'Chuột',
          value:'chuot'
        },
        {
          name: 'Tai nghe',
          value:'tai-nghe'
        },
        {
          name: 'Loa máy tính',
          value:'loa-may-tinh'
        }
      ],
      objData: {
        lang: "",
        variant:[],
        name: "",
        size: [
          {
            title: "",
            detail: ""
          },
        ],
        cate_build_pc:"",
        tags:[],
        price: 0,
        discount: 0,
        preserve:[],
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
        qty:  100,
        discountStatus:0,
        type_cate: 0,
        type_two:0,
        species:[
          {
            detail: ""
        }
        ],
        origin: "",
        thickness: "",
        hang_muc: "",
        service_id:0,
        lungtung:[],
        status_variant: 0,
        home_status: 0
      },
      seoData: {
        focusKeyword: "",
        seoTitle: "",
        metaDescription: "",
        slug: ""
      },
      syncPaused: false
    };
  },
  components: {
    TinyMce,
    ImageMulti,
    SeoAssistant,
    InputTag,
    "v-input-colorpicker": InputColorPicker
  },
  computed: {
    isTagCategoryRequired() {
      return !this.useGlobalTags && Number(this.objData.category) === 0;
    },
  },
  watch: {
    "objData.name"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.seoData.seoTitle = value || "";
        this.seoData.slug = this.slugifySeo(value || "");
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
        this.seoData.slug = this.slugifySeo(value || "");
      });
    },
    "seoData.metaDescription"(value) {
      if (this.syncPaused) return;
      this.withSyncPause(() => {
        this.objData.description[0].content = value || "";
      });
    },
    "seoData.slug"(value) {
      if (this.syncPaused) return;
      const normalized = this.slugifySeo(value || "");
      if (normalized === value) return;
      this.withSyncPause(() => {
        this.seoData.slug = normalized;
      });
    }
  },
  methods: {
    ...mapActions([
      "editId",
      "saveProduct",
      "listCate",
      "loadings",
      "listLanguage",
      "findTypeCate",
      "findTypeCateTwo",
      "listCateService",
      "findTags",
      "getSetting",
      "saveTagCate",
      "saveTag",
      "listVariantLibrary",
      "saveVariantLibrary",
      "deleteVariantLibrary"
    ]),
    buildTagValue(tag, category) {
      if (!tag || !category) return "";
      return `${tag.slug}-${category.slug}`;
    },
    syncTagSelections() {
      const validTagValues = new Set();
      this.tags.forEach((category) => {
        (category.tags || []).forEach((tag) => {
          validTagValues.add(this.buildTagValue(tag, category));
        });
      });
      this.objData.tags = (this.objData.tags || []).filter((value) => validTagValues.has(value));
    },
    refreshTagsByCategory() {
      const requestCategory = this.useGlobalTags ? 0 : Number(this.objData.category || 0);
      if (!this.useGlobalTags && requestCategory === 0) {
        this.tags = [];
        this.objData.tags = [];
        this.tagEditor.selectedCategorySlug = "";
        return Promise.resolve();
      }
      return this.findTags(requestCategory)
        .then((response) => {
          this.tags = Array.isArray(response.data) ? response.data : [];
          if (!this.tagEditor.selectedCategorySlug && this.tags.length > 0) {
            this.tagEditor.selectedCategorySlug = this.tags[0].slug || "";
          }
          if (
            this.tagEditor.selectedCategorySlug &&
            !this.tags.some((item) => item.slug === this.tagEditor.selectedCategorySlug)
          ) {
            this.tagEditor.selectedCategorySlug = this.tags.length > 0 ? (this.tags[0].slug || "") : "";
          }
          this.syncTagSelections();
        })
        .catch(() => {
          this.tags = [];
          this.objData.tags = [];
          this.tagEditor.selectedCategorySlug = "";
        });
    },
    createTagCategoryInline() {
      const categoryName = (this.tagEditor.categoryName || "").trim();
      if (!categoryName) {
        this.$error("Nhập tên danh mục tag mới");
        return;
      }
      if (!this.useGlobalTags && (!this.objData.category || Number(this.objData.category) === 0)) {
        this.$error("Hãy chọn danh mục sản phẩm trước khi tạo danh mục tag");
        return;
      }
      this.tagEditor.creatingCategory = true;
      this.saveTagCate({
        name: categoryName,
        status: 1,
        status_filter: 1,
        cate_product_id: this.useGlobalTags ? 0 : this.objData.category,
      })
        .then(() => {
          this.$success("Đã tạo danh mục tag");
          this.tagEditor.categoryName = "";
          return this.refreshTagsByCategory();
        })
        .then(() => {
          const createdCategory = this.tags.find(
            (item) => (item.name || "").trim().toLowerCase() === categoryName.toLowerCase()
          );
          if (createdCategory) {
            this.tagEditor.selectedCategorySlug = createdCategory.slug || "";
          }
        })
        .catch(() => {
          this.$error("Không thể tạo danh mục tag");
        })
        .finally(() => {
          this.tagEditor.creatingCategory = false;
        });
    },
    createTagInline() {
      const tagName = (this.tagEditor.tagName || "").trim();
      if (!tagName) {
        this.$error("Nhập tên tag mới");
        return;
      }
      const selectedCategory = this.tags.find(
        (item) => item.slug === this.tagEditor.selectedCategorySlug
      );
      if (!selectedCategory || !selectedCategory.id) {
        this.$error("Chọn danh mục tag trước khi tạo tag");
        return;
      }
      this.tagEditor.creatingTag = true;
      this.saveTag({
        name: tagName,
        status: 1,
        cate_tag_id: selectedCategory.id,
        cate_product_id: this.useGlobalTags ? 0 : this.objData.category,
      })
        .then(() => {
          this.$success("Đã tạo tag mới");
          this.tagEditor.tagName = "";
          return this.refreshTagsByCategory();
        })
        .then(() => {
          const updatedCategory = this.tags.find((item) => item.slug === selectedCategory.slug);
          const createdTag = (updatedCategory && updatedCategory.tags || []).find(
            (item) => (item.name || "").trim().toLowerCase() === tagName.toLowerCase()
          );
          if (createdTag) {
            const tagValue = this.buildTagValue(createdTag, updatedCategory);
            if (!this.objData.tags.includes(tagValue)) {
              this.objData.tags.push(tagValue);
            }
          }
        })
        .catch(() => {
          this.$error("Không thể tạo tag");
        })
        .finally(() => {
          this.tagEditor.creatingTag = false;
        });
    },
    generateVariantId() {
      this.variantSeed += 1;
      return Date.now() + this.variantSeed;
    },
    loadVariantLibraryFromDb() {
      this.listVariantLibrary({ keyword: "" })
        .then((response) => {
          this.variantLibrary = Array.isArray(response.data) ? response.data : [];
          this.selectedVariantLibraryIds = this.selectedVariantLibraryIds.filter((id) =>
            this.variantLibrary.some((item) => Number(item.id) === Number(id))
          );
        })
        .catch(() => {
          this.variantLibrary = [];
          this.selectedVariantLibraryIds = [];
        });
    },
    getUsableVariantOptions() {
      return this.variantOptions
        .map((option) => ({
          ...option,
          name: (option.name || "").trim(),
          values: option.values.filter((value) => (value.label || "").trim() !== ""),
        }))
        .filter((option) => option.name && option.values.length > 0);
    },
    saveCurrentVariantsToLibrary() {
      const usableOptions = this.getUsableVariantOptions();
      if (usableOptions.length === 0) {
        this.$error("Chưa có thuộc tính hợp lệ để lưu mẫu");
        return;
      }
      const requests = usableOptions.map((option) => {
        const existed = this.variantLibrary.find(
          (item) => (item.name || "").trim().toLowerCase() === option.name.toLowerCase()
        );
        const oldValues = existed && Array.isArray(existed.variant_value)
          ? existed.variant_value.map((item) => item.name)
          : [];
        const mergedValues = [...oldValues];
        option.values.forEach((value) => {
          if (!mergedValues.some((item) => (item || "").trim().toLowerCase() === value.label.toLowerCase())) {
            mergedValues.push(value.label);
          }
        });
        return this.saveVariantLibrary({
          id: existed ? existed.id : "",
          name: option.name,
          variant_value: mergedValues,
        });
      });
      Promise.all(requests)
        .then(() => {
          this.$success("Đã lưu mẫu variant vào database");
          this.loadVariantLibraryFromDb();
        })
        .catch(() => {
          this.$error("Không thể lưu mẫu variant vào database");
        });
    },
    applyVariantLibrary() {
      if (this.selectedVariantLibraryIds.length === 0) {
        this.$error("Chưa có mẫu variant đã lưu");
        return;
      }
      const selectedItems = this.variantLibrary.filter((item) =>
        this.selectedVariantLibraryIds.some((id) => Number(id) === Number(item.id))
      );
      if (selectedItems.length === 0) {
        this.$error("Mẫu variant không tồn tại");
        return;
      }
      const preview = this.buildMergePreview(selectedItems);
      this.$confirm(
        `Sẽ thêm ${preview.addedOptions} thuộc tính mới và ${preview.addedValues} giá trị mới. Bỏ qua ${preview.skippedValues} giá trị trùng. Tiếp tục?`,
        "Xem trước merge variant",
        { confirmButtonText: "Áp dụng", cancelButtonText: "Hủy", type: "info" }
      )
        .then(() => {
          this.variantstatus = true;
          this.variantOptions = preview.nextOptions;
          this.rebuildVariantCombinations();
          this.$success("Đã áp dụng mẫu variant (tự bỏ trùng)");
        })
        .catch(() => {});
    },
    buildMergePreview(selectedItems) {
      const nextOptions = JSON.parse(JSON.stringify(this.variantOptions || []));
      let addedOptions = 0;
      let addedValues = 0;
      let skippedValues = 0;
      selectedItems.forEach((selected) => {
        const optionName = (selected.name || "").trim();
        if (!optionName) return;
        const valueLabels = (selected.variant_value || [])
          .map((value) => (value.name || "").trim())
          .filter((label) => label !== "");
        if (valueLabels.length === 0) return;
        const existedIndex = nextOptions.findIndex(
          (option) => (option.name || "").trim().toLowerCase() === optionName.toLowerCase()
        );
        if (existedIndex === -1) {
          addedOptions += 1;
          addedValues += valueLabels.length;
          nextOptions.push({
            id: this.generateVariantId(),
            name: optionName,
            draft: "",
            values: valueLabels.map((label) => ({ id: this.generateVariantId(), label })),
          });
          return;
        }
        const existedValues = nextOptions[existedIndex].values || [];
        valueLabels.forEach((label) => {
          if (!existedValues.some((item) => (item.label || "").trim().toLowerCase() === label.toLowerCase())) {
            existedValues.push({ id: this.generateVariantId(), label });
            addedValues += 1;
          } else {
            skippedValues += 1;
          }
        });
      });
      return { nextOptions, addedOptions, addedValues, skippedValues };
    },
    getInputValue(payload) {
      if (payload && payload.target) {
        return payload.target.value;
      }
      return payload;
    },
    normalizeDigits(value) {
      if (value === null || value === undefined) return "";
      return String(value).replace(/\D/g, "");
    },
    formatNumberDisplay(value) {
      const normalized = this.normalizeDigits(value);
      if (!normalized) return "";
      return new Intl.NumberFormat("vi-VN").format(Number(normalized));
    },
    setBulkNumber(field, payload) {
      const inputValue = this.getInputValue(payload);
      const normalized = this.normalizeDigits(inputValue);
      this.bulkVariant[field] = normalized === "" ? null : Number(normalized);
    },
    setVariantNumber(index, field, payload) {
      if (!this.objData.lungtung[index]) return;
      const inputValue = this.getInputValue(payload);
      const normalized = this.normalizeDigits(inputValue);
      this.$set(this.objData.lungtung[index], field, normalized === "" ? "" : Number(normalized));
      this.updateVariantInsights();
    },
    syncBulkDisplay(field, payload) {
      const target = payload && payload.target ? payload.target : null;
      if (!target) return;
      requestAnimationFrame(() => {
        target.value = this.formatNumberDisplay(this.bulkVariant[field]);
      });
    },
    syncVariantDisplay(index, field, payload) {
      const target = payload && payload.target ? payload.target : null;
      if (!target || !this.objData.lungtung[index]) return;
      requestAnimationFrame(() => {
        target.value = this.formatNumberDisplay(this.objData.lungtung[index][field]);
      });
    },
    applyBulkVariantChanges() {
      if (!this.objData.lungtung.length) return;
      this.objData.lungtung = this.objData.lungtung.map((item, index) => ({
        ...item,
        price: this.bulkVariant.price !== null && this.bulkVariant.price !== "" ? Number(this.bulkVariant.price) : item.price,
        qty: this.bulkVariant.qty !== null && this.bulkVariant.qty !== "" ? Number(this.bulkVariant.qty) : item.qty,
        sku: this.bulkVariant.skuPrefix
          ? `${this.slugify(this.bulkVariant.skuPrefix)}-${index + 1}`
          : item.sku,
      }));
      this.updateVariantInsights();
      this.$success("Đã cập nhật hàng loạt");
    },
    autoGenerateSkus() {
      if (!this.objData.lungtung.length) return;
      const base = this.slugify(this.objData.name || "product");
      this.objData.lungtung = this.objData.lungtung.map((item, index) => {
        const variantPart = this.slugify(item.version || `variant-${index + 1}`);
        return {
          ...item,
          sku: `${base}-${variantPart}-${index + 1}`,
        };
      });
      this.updateVariantInsights();
      this.$success("Đã tạo SKU thông minh");
    },
    slugify(value) {
      return (value || "")
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");
    },
    slugifySeo(value) {
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
      this.seoData.slug = this.slugifySeo(value);
    },
    autoOptimizeSeo() {
      const title = (this.objData.name || "").trim();
      const shortDesc = (this.objData.description[0].content || "").trim();
      this.withSyncPause(() => {
        if (!this.seoData.focusKeyword) {
          this.seoData.focusKeyword = this.slugifySeo(title).replace(/-/g, " ").split(" ").slice(0, 4).join(" ");
        }
        this.seoData.seoTitle = title ? `${title} | CuBon` : this.seoData.seoTitle;
        this.seoData.metaDescription = (shortDesc || "").slice(0, 160);
        this.seoData.slug = this.slugifySeo(title);
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
    },
    updateVariantInsights() {
      const rows = Array.isArray(this.objData.lungtung) ? this.objData.lungtung : [];
      this.variantInsights = {
        total: rows.length,
        emptySku: rows.filter((item) => !item.sku).length,
        zeroQty: rows.filter((item) => Number(item.qty || 0) <= 0).length,
      };
    },
    deleteSelectedVariantLibrary() {
      if (this.selectedVariantLibraryIds.length !== 1) {
        this.$error("Vui lòng chọn đúng 1 mẫu variant để xóa");
        return;
      }
      const selectedId = this.selectedVariantLibraryIds[0];
      const selected = this.variantLibrary.find((item) => Number(item.id) === Number(selectedId));
      if (!selected) {
        this.$error("Mẫu variant không tồn tại");
        return;
      }
      this.$confirm(`Xóa mẫu variant "${selected.name}"?`, "Xác nhận", {
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
        type: "warning",
      })
        .then(() => this.deleteVariantLibrary({ id: selected.id }))
        .then(() => {
          this.$success("Đã xóa mẫu variant");
          this.selectedVariantLibraryIds = [];
          this.loadVariantLibraryFromDb();
        })
        .catch(() => {});
    },
    handleVariantToggle() {
      if (this.variantstatus && this.variantOptions.length === 0) {
        this.createVariantOption();
      }
      if (!this.variantstatus) {
        this.objData.status_variant = 0;
        this.objData.variant = [];
        this.objData.lungtung = [];
        this.updateVariantInsights();
      } else {
        this.rebuildVariantCombinations();
      }
    },
    createVariantOption() {
      this.variantOptions.push({
        id: this.generateVariantId(),
        name: "",
        values: [],
        draft: "",
      });
    },
    removeVariantOption(optionIndex) {
      this.variantOptions.splice(optionIndex, 1);
      this.rebuildVariantCombinations();
    },
    onVariantOptionChange() {
      this.rebuildVariantCombinations();
    },
    addVariantValue(optionIndex) {
      const option = this.variantOptions[optionIndex];
      if (!option) return;
      const value = (option.draft || "").trim();
      if (!value) return;
      if (option.values.some((item) => item.label.toLowerCase() === value.toLowerCase())) {
        option.draft = "";
        return;
      }
      option.values.push({
        id: this.generateVariantId(),
        label: value,
      });
      option.draft = "";
      this.rebuildVariantCombinations();
    },
    removeVariantValue(optionIndex, valueIndex) {
      const option = this.variantOptions[optionIndex];
      if (!option) return;
      option.values.splice(valueIndex, 1);
      this.rebuildVariantCombinations();
    },
    buildCartesianGroups(options) {
      let rows = [[]];
      options.forEach((option) => {
        const group = [];
        option.values.forEach((value) => {
          rows.forEach((row) => {
            group.push([
              ...row,
              {
                optionId: option.id,
                valueId: value.id,
                optionName: option.name,
                valueLabel: value.label,
              },
            ]);
          });
        });
        rows = group;
      });
      return rows;
    },
    rebuildVariantCombinations() {
      const usableOptions = this.getUsableVariantOptions();

      this.objData.variant = usableOptions.map((option) => ({
        _id: option.id,
        display_name: option.name,
        option_values: option.values.map((value) => ({
          _id: value.id,
          label: value.label,
        })),
      }));

      if (!this.variantstatus || usableOptions.length === 0) {
        this.objData.status_variant = 0;
        this.objData.lungtung = [];
        this.updateVariantInsights();
        return;
      }

      const previousMap = {};
      this.objData.lungtung.forEach((item) => {
        previousMap[item.version] = item;
      });

      const groups = this.buildCartesianGroups(usableOptions);
      this.objData.status_variant = groups.length > 0 ? 1 : 0;
      this.objData.lungtung = groups.map((group) => {
        const version = group.map((part) => part.valueLabel).join(" - ");
        const oldRow = previousMap[version] || {};
        return {
          price: Number(oldRow.price || 0),
          qty: Number(oldRow.qty || 0),
          sku: oldRow.sku || "",
          version,
          option_values: group.map((part) => ({
            option_id: part.valueId,
            id: part.optionId,
          })),
        };
      });
      this.updateVariantInsights();
    },
    normalizeTechnicalSpecs(sizeData) {
      const specs = Array.isArray(sizeData) ? sizeData : [];
      const normalized = specs
        .map((item) => ({
          title: ((item && item.title) || "").trim(),
          detail: ((item && item.detail) || "").trim(),
        }))
        .filter((item) => item.title !== "" || item.detail !== "");

      return normalized.length > 0 ? normalized : [{ title: "", detail: "" }];
    },
    addTechnicalSpecRow() {
      this.objData.size.push({ title: "", detail: "" });
    },
    removeTechnicalSpecRow(index) {
      if (this.objData.size.length <= 1) {
        this.objData.size = [{ title: "", detail: "" }];
        return;
      }
      this.objData.size.splice(index, 1);
    },
    handleTechnicalSpecEnter(index) {
      const current = this.objData.size[index] || {};
      if (!((current.title || "").trim() || (current.detail || "").trim())) return;
      if (index === this.objData.size.length - 1) {
        this.addTechnicalSpecRow();
      }
    },
    saveProducts() {
      this.errors = [];
     if(this.objData.name == '') this.errors.push('Tên không được để trống');
      if(this.objData.content[0].content == '') this.errors.push('Nội dung không được để trống');
      if(this.objData.description[0].content == '') this.errors.push('Mô tả không được để trống');
      if(this.objData.images.length == 0) this.errors.push('Vui lòng chọn ảnh');
      if(this.objData.category == 0) this.errors.push('Chọn danh mục sản phẩm');
      if(this.objData.service_id == null) this.errors.push('Chọn danh mục dịch vụ');
      if(this.variantstatus && this.objData.lungtung.length == 0) this.errors.push('Biến thể chưa hợp lệ, vui lòng thêm thuộc tính và giá trị');
      if (this.errors.length > 0) {
        this.errors.forEach((value, key) => {
          this.$error(value);
        });
        return;
      } else {
        this.loadings(true);
        if (this.variantstatus) {
          this.saveCurrentVariantsToLibrary();
        }

        const payload = {
          ...this.objData,
          size: this.normalizeTechnicalSpecs(this.objData.size),
          seo_title: this.seoData.seoTitle,
          meta_description: this.seoData.metaDescription,
          focus_keyword: this.seoData.focusKeyword,
          slug: this.seoData.slug || this.slugifySeo(this.objData.name)
        };
        this.saveProduct(payload)
          .then((response) => {
            this.loadings(false);
            this.$router.push({ name: "listProduct" });
            this.$success("Thêm sản phẩm thành công");
            this.$route.push({ name: "listProduct" });
          })
          .catch((error) => {
            this.loadings(false);
            // this.$vs.notify({
            //   title: "Thất bại",
            //   text: "Thất bại",
            //   color: "danger",
            //   position: "top-right"
            // });
          });
      }
    },
    
    findCategoryType() {
      this.findTypeCate(this.objData.category).then((response) => {
        this.type_cate = response.data;
      });
      this.refreshTagsByCategory();
    },
    loadTagModeSetting() {
      return this.getSetting()
        .then((response) => {
          const settingData = response && response.data ? response.data : {};
          this.useGlobalTags = settingData.use_global_tags !== undefined
            ? Number(settingData.use_global_tags) === 1
            : true;
        })
        .catch(() => {
          this.useGlobalTags = true;
        })
        .then(() => this.refreshTagsByCategory());
    },
    findCategoryTypeTwo() {
      this.findTypeCateTwo(this.objData.type_cate).then((response) => {
        this.type_two = response.data;
      });
    },
    remoteAr(index,key) {
      if(key == 'size'){
        this.removeTechnicalSpecRow(index);
      }

        if(key == 'species'){
        this.objData.species.splice(index, 1);
      }
    },
    addInput(key) {
        var oj = {};
        if(key =='size'){
          this.addTechnicalSpecRow();
        }
        if(key =='species'){
          oj.detail = "";
          this.objData.species.push(oj);
        }
    },
    showSettingLangExist(value, name = "content") {
      if (value == "content") {
        this.showLang.content = !this.showLang.content;
        this.lang.forEach((value, index) => {
          if (
            !this.objData.content[index] &&
            value.code != this.objData.content[0].lang_code
          ) {
            var oj = {};
            oj.lang_code = value.code;
            oj.content = "";
            this.objData.content.push(oj);
          }
        });
      }
      if (value == "description") {
        this.showLang.description = !this.showLang.description;
        this.lang.forEach((value, index) => {
          if (
            !this.objData.description[index] &&
            value.code != this.objData.description[0].lang_code
          ) {
            var oj = {};
            oj.lang_code = value.code;
            oj.content = "";
            this.objData.description.push(oj);
          }
        });
      }
    },
    listLang() {
      this.listLanguage()
        .then((response) => {
          this.loadings(false);
          this.lang = response.data;
        })
        .catch((error) => {});
    },
  },
  mounted() {
    this.loadings(true);
    this.listCate().then((response) => {
      this.loadings(false);
      this.cate = response.data;
    });
     this.listCateService().then((response) => {
      this.loadings(false);
      this.cateservice = response.data;
    });
    this.listLang();
    this.loadVariantLibraryFromDb();
    this.loadTagModeSetting();
  },
};
</script>
<style scoped>
.centerx li {
    list-style: none!important;
}
.centerx, .con-notifications, .con-notifications-position {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.variant-manager {
  border-top: 1px solid #edf1f7;
  padding-top: 16px;
  margin-top: 8px;
}
.tag-manager {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 12px;
  background: #fcfcfd;
}
.tag-manager-note {
  color: #6b7280;
  font-size: 12px;
}
.tag-multi-select {
  border-radius: 10px;
}
.tag-multi-select::v-deep .vs-con-select {
  min-height: 42px;
  border: 1px solid #dbe2ea;
  border-radius: 10px;
  background: #fff;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.tag-multi-select::v-deep .vs-con-select:hover {
  border-color: #c3ceda;
}
.tag-multi-select::v-deep .vs-con-select.active {
  border-color: #409eff;
  box-shadow: 0 0 0 3px rgba(64, 158, 255, 0.15);
}
.tag-multi-select::v-deep .vs-con-select .vs-selected {
  margin: 4px 4px 0 0;
  border-radius: 999px;
  background: #eef6ff;
  color: #2563eb;
  border: 1px solid #bfdbfe;
  font-size: 12px;
  padding: 2px 8px;
}
.tag-multi-select::v-deep .vs-con-select .vs-selected .con-icon {
  font-size: 11px;
}
.tag-multi-select::v-deep .vs-con-select input {
  min-height: 32px;
  padding-left: 10px;
}
.tag-multi-select::v-deep .vs-con-select.isDisabled,
.tag-multi-select::v-deep .vs-con-select.is-disabled {
  background: #f3f4f6;
  border-color: #e5e7eb;
  opacity: 0.9;
}
.tag-selected-count {
  font-size: 12px;
  color: #4b5563;
  background: #eff6ff;
  border-radius: 999px;
  padding: 2px 10px;
}
.tag-category-warning {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #92400e;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 8px;
  padding: 8px 10px;
}
.tag-quick-create {
  border-top: 1px dashed #d1d5db;
  padding-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.tag-create-row {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 8px;
  align-items: center;
}
.tag-create-row:nth-child(2) {
  grid-template-columns: 1fr 1fr auto;
}
.variant-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
  font-size: 13px;
}
.variant-option-card {
  border: 1px solid #dfe6f1;
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 10px;
}
.variant-library-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}
.variant-library-note {
  color: #8895a7;
  font-size: 12px;
}
.variant-library-select {
  min-width: 220px;
}
.variant-bulk-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}
.variant-bulk-input {
  min-width: 160px;
}
.variant-number-input {
  width: 100%;
  height: 38px;
  border: 1px solid #dcdfe6;
  border-radius: 6px;
  padding: 0 12px;
  outline: none;
}
.variant-number-input:focus {
  border-color: #409eff;
}
.variant-insight {
  font-size: 12px;
  color: #6b7280;
}
.variant-value-list {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.variant-value-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: 14px;
  background: #f3f6fb;
  border: 1px solid #d9e2ef;
  font-size: 12px;
}
.variant-value-tag button {
  border: none;
  background: transparent;
  color: #ff4d4f;
  font-size: 12px;
  cursor: pointer;
  padding: 0;
}
.tech-spec-note {
  color: #6b7280;
  font-size: 12px;
}
.tech-spec-count {
  font-size: 12px;
  color: #4b5563;
  background: #f3f4f6;
  border-radius: 999px;
  padding: 2px 10px;
}
.tech-spec-grid-header {
  display: grid;
  grid-template-columns: 1fr 1fr 36px;
  gap: 8px;
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 6px;
}
.tech-spec-row {
  display: grid;
  grid-template-columns: 1fr 1fr 36px;
  gap: 8px;
  margin-bottom: 8px;
  align-items: center;
}
.compact-meta-box {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 10px;
  background: #fafafa;
}
.compact-meta-title {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 8px;
}
.compact-meta-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px 12px;
}
.compact-label {
  display: block;
  margin-bottom: 4px;
  font-size: 12px;
  color: #4b5563;
}
.compact-radio-group {
  display: inline-flex;
}
</style>