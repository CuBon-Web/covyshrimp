<template>
  <div>
    <!-- 多图片上传 -->
    <el-upload
      v-if="multiple"
      ref="uploader"
      action="'/upload'"
      name="img"
      list-type="picture-card"
      :on-preview="handlePreview"
      :auto-upload="true"
      :on-remove="handleRemove"
      :http-request="request"
      :file-list="uploadList"
      :multiple="true"
      :show-file-list="true"
      :before-upload="beforeUpload"
    >
      <template slot="file" slot-scope="{ file }">
        <div class="upload-item" :data-uid="String(file.uid)">
          <img class="el-upload-list__item-thumbnail" :src="file.url" alt />
          <span class="el-upload-list__item-actions upload-item-actions">
            <span class="el-upload-list__item-preview" @click="handlePreview(file)">
              <i class="el-icon-zoom-in"></i>
            </span>
            <span class="el-upload-list__item-delete" @click="moveUp(file)">
              <i class="el-icon-top"></i>
            </span>
            <span class="el-upload-list__item-delete" @click="moveDown(file)">
              <i class="el-icon-bottom"></i>
            </span>
            <span class="el-upload-list__item-delete" @click="triggerReupload(file)">
              <i class="el-icon-refresh"></i>
            </span>
            <span class="el-upload-list__item-delete" @click="handleRemoveFromActions(file)">
              <i class="el-icon-delete"></i>
            </span>
          </span>
        </div>
      </template>
      <i class="el-icon-plus"></i>
    </el-upload>

    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt />
    </el-dialog>
    <input
      ref="singleReuploadInput"
      class="hidden-reupload-input"
      type="file"
      accept="image/*"
      @change="onReuploadFileSelected"
    />
  </div>
</template>
<script>
import imageCompression from "browser-image-compression";
import Sortable from "sortablejs";
export default {
  name: "uploader",
  props: {
    targetUrl: {
      // 上传地址
      type: String,
      default: "api/upload-image"
    },
    multiple: {
      // 多图开关
      type: Boolean,
      default: true
    },
    value: {
      // 初始图片链接
      default: ""
    },
    title:""
  },
  data() {
    return {
      dialogImageUrl: "",
      uploadList: [],
      dialogVisible: false,
      loading: false,
      pendingReuploadUid: null,
      sortableInstance: null
    };
  },
  watch: {
    value: function(val) {
      this.uploadList = this.normalizeToUploadList(val);
    }
  },
  mounted() {
    this.uploadList = this.normalizeToUploadList(this.value);
    this.$nextTick(() => {
      this.initSortable();
    });
  },
  beforeDestroy() {
    this.destroySortable();
  },
  methods: {
    getslugname(text) {
      var slug = "";
      // Change to lower case
      var titleLower = (text || "").toLowerCase();
      // Letter "e"
      slug = titleLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, "e");
      // Letter "a"
      slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, "a");
      // Letter "o"
      slug = slug.replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, "o");
      // Letter "u"
      slug = slug.replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, "u");
      // Letter "d"
      slug = slug.replace(/đ/gi, "d");
      // Trim the last whitespace
      slug = slug.replace(/\s*$/g, "");
      // Change whitespace to "-"
      slug = slug.replace(/\s+/g, "-");
      return slug;
    },
    beforeUpload() {
      this.loading = true;
      return true;
    },
    handlePreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleRemove(file, fileList) {
      this.uploadList = fileList;
      this.emitInput();
    },
    handleRemoveFromActions(file) {
      const index = this.findFileIndex(file);
      if (index < 0) {
        return;
      }
      this.uploadList.splice(index, 1);
      this.emitInput();
    },
    moveUp(file) {
      const index = this.findFileIndex(file);
      if (index <= 0) {
        return;
      }
      this.swapFiles(index, index - 1);
      this.emitInput();
    },
    moveDown(file) {
      const index = this.findFileIndex(file);
      if (index < 0 || index >= this.uploadList.length - 1) {
        return;
      }
      this.swapFiles(index, index + 1);
      this.emitInput();
    },
    swapFiles(fromIndex, toIndex) {
      const next = [...this.uploadList];
      const temp = next[fromIndex];
      next.splice(fromIndex, 1);
      next.splice(toIndex, 0, temp);
      this.uploadList = next;
    },
    initSortable() {
      this.destroySortable();
      const uploadRef = this.$refs.uploader;
      const rootEl = uploadRef && uploadRef.$el;
      if (!rootEl) {
        return;
      }
      const listEl = rootEl.querySelector(".el-upload-list--picture-card");
      if (!listEl) {
        return;
      }
      this.sortableInstance = Sortable.create(listEl, {
        animation: 180,
        ghostClass: "upload-sortable-ghost",
        draggable: ".el-upload-list__item",
        onEnd: () => {
          // Wait one Vue tick so el-upload and slot DOM are fully settled.
          this.$nextTick(() => {
            this.syncOrderFromDom();
          });
        }
      });
    },
    syncOrderFromDom() {
      const uploadRef = this.$refs.uploader;
      const rootEl = uploadRef && uploadRef.$el;
      if (!rootEl) {
        return;
      }
      const uidOrder = Array.from(rootEl.querySelectorAll(".upload-item[data-uid]")).map(node =>
        node.getAttribute("data-uid")
      );
      if (!uidOrder.length) {
        return;
      }
      const indexMap = {};
      this.uploadList.forEach(item => {
        indexMap[String(item.uid)] = item;
      });
      const nextList = uidOrder.map(uid => indexMap[uid]).filter(Boolean);
      if (nextList.length !== this.uploadList.length) {
        return;
      }
      this.uploadList = nextList;
      this.emitInput();
    },
    destroySortable() {
      if (this.sortableInstance) {
        this.sortableInstance.destroy();
        this.sortableInstance = null;
      }
    },
    findFileIndex(file) {
      return this.uploadList.findIndex(item => item.uid === file.uid || item.url === file.url);
    },
    triggerReupload(file) {
      this.pendingReuploadUid = file.uid;
      const input = this.$refs.singleReuploadInput;
      if (input) {
        input.value = "";
        input.click();
      }
    },
    onReuploadFileSelected(event) {
      const inputFile = event.target.files && event.target.files[0];
      if (!inputFile || !this.pendingReuploadUid) {
        this.pendingReuploadUid = null;
        return;
      }
      const fileWrapper = {
        file: inputFile,
        onError: () => {
          this.pendingReuploadUid = null;
        }
      };
      this.uploadAndResolve(fileWrapper)
        .then(uploadedPath => {
          const index = this.uploadList.findIndex(item => item.uid === this.pendingReuploadUid);
          if (index >= 0) {
            this.$set(this.uploadList, index, {
              ...this.uploadList[index],
              name: inputFile.name,
              url: uploadedPath
            });
            this.emitInput();
          }
        })
        .finally(() => {
          this.pendingReuploadUid = null;
        });
    },
    request(req) {
      this.uploadAndResolve(req)
        .then(uploadedPath => {
          this.uploadList.push({
            name: req.file.name,
            url: uploadedPath,
            uid: `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`
          });
          this.emitInput();
        })
        .catch(() => {
          if (req && req.onError) {
            req.onError();
          }
        });
    },
    uploadAndResolve(req) {
      const options = {
        maxSizeMB: 3,
        maxWidthOrHeight: 10000,
        useWebWorker: true,
        maxIteration: 10
      };
      return imageCompression(req.file, options).then(res => {
        return new Promise((resolve, reject) => {
          var xhr, formData;
          xhr = new XMLHttpRequest();
          xhr.withCredentials = false;
          xhr.open("POST", __ENV__.link + "api/upload-image");
          xhr.onload = () => {
            var json;
            if (xhr.status !== 200) {
              this.$notify.error({
                message: "HTTP Error: " + xhr.status
              });
              this.loading = false;
              reject(new Error("HTTP Error: " + xhr.status));
              return;
            }

            json = JSON.parse(xhr.responseText);
            this.loading = false;
            resolve(json.path.replace(__ENV__.link, "/"));
          };
          xhr.onerror = () => {
            this.$notify.error({
              message: "Upload failed"
            });
            this.loading = false;
            reject(new Error("Upload failed"));
          };
          formData = new FormData();
          formData.append("img", res, req.file.name);
          formData.append("title_post", this.getslugname(this.title));
          xhr.send(formData);
        });
      });
    },
    normalizeToUploadList(value) {
      if (!value) {
        return [];
      }
      const arr = Array.isArray(value) ? value : [value];
      return arr.map((item, index) => {
        if (typeof item === "string") {
          return {
            url: item,
            uid: `index${index}`
          };
        }
        return {
          ...item,
          uid: item.uid || `index${index}`,
          url: item.url
        };
      });
    },
    formatImgArr(arr) {
      return arr.map(item => item.url);
    },
    emitInput() {
      this.$emit("input", this.formatImgArr(this.uploadList));
    }
  },
  components: {}
};
</script>
<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  color: #8c939d;
  text-align: center;
}
.avatar {
  display: block;
}
.reupload {
  border-radius: 50%;
  position: absolute;
  color: #fff;
  background-color: #000000;
  opacity: 0.6;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: none;
}
#uploadIcon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: none;
}
.upload-item {
  width: 100%;
  height: 100%;
}
.upload-item-actions span {
  margin: 0 4px;
}
.hidden-reupload-input {
  display: none;
}
.upload-sortable-ghost {
  opacity: 0.4;
}
</style>