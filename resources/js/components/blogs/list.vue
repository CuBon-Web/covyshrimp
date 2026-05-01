<template>
  <div>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title" >Danh sách tin tức</h4>
              <vs-button
                type="gradient"
                style="float:right;"
                :disabled="!canCreateBlog"
                @click="goToAddBlog"
              >Thêm mới</vs-button>
              <vs-input icon="search" placeholder="Search" v-model="keyword" @keyup="searchBlog" />
              <vs-table stripe :data="list" max-items="10" pagination>
                <template slot="thead">
                  <vs-th>Tiêu đề</vs-th>
                  <vs-th>Danh mục</vs-th>
                  <vs-th>Danh mục con</vs-th>
                  <vs-th>Loại</vs-th>
                  <vs-th>Hành động</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td >{{JSON.parse(tr.title)[0].content}}</vs-td>
                    <vs-td v-if="tr.cate != null">{{JSON.parse(tr.cate.name)[0].content}}</vs-td>
                    <vs-td v-if="tr.cate == null">--Trống--</vs-td>
                    <vs-td v-if="tr.type_cate != null">{{JSON.parse(tr.type_cate.name)[0].content}}</vs-td>
                    <vs-td v-if="tr.type_cate == null">-----</vs-td>
                    <vs-td v-if="tr.type_news == 'tin-hot'">Tin Hot</vs-td>
                    <vs-td v-if="tr.type_news == 'tin-khuyen-mai'">Tin Khuyến Mãi</vs-td>
                    <vs-td v-if="tr.type_news == null">-----</vs-td>
                    <vs-td >
                      <router-link :to="{name:'editBlog',params:{id:tr.id}}">
                        <vs-button
                          vs-type="gradient"
                          size="lagre"
                          color="success"
                          icon="edit"
                        ></vs-button>
                      </router-link>
                      <vs-button vs-type="gradient" size="lagre" color="red" icon="delete_forever" @click="confirmDestroy(tr.id)"></vs-button>
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
import Swal from "sweetalert2";
import { mapActions } from "vuex";
export default {
  data() {
    return {
      list:[],
      keyword:"",
      id_item:"",
    };
  },
  components: {},
  computed: {
    permissionSlugs() {
      return this.$store.getters.permissionSlugs || [];
    },
    canCreateBlog() {
      return this.hasPermissionWithManageFallback("blog.create");
    }
  },
  watch: {},
  methods: {
    ...mapActions(['listBlog','loadings','deleteBlog']),
    hasPermissionWithManageFallback(requiredPermission) {
      if (!requiredPermission) return true;
      if (this.permissionSlugs.includes(requiredPermission)) return true;
      if (
        requiredPermission.endsWith(".view") ||
        requiredPermission.endsWith(".create") ||
        requiredPermission.endsWith(".update") ||
        requiredPermission.endsWith(".delete")
      ) {
        const prefix = requiredPermission.split(".").slice(0, -1).join(".");
        return this.permissionSlugs.includes(prefix + ".manage");
      }
      return false;
    },
    goToAddBlog() {
      if (!this.canCreateBlog) {
        this.$forbidden("Bạn không có quyền thêm mới bài viết.");
        return;
      }
      this.$router.push({ name: "addBlogs" });
    },
    listBlogs(){
      this.listBlog({ keyword: this.keyword })
      .then(response => {
          this.loadings(false);
          this.list = response.data;
      }).catch(err => {
          this.loadings(false);
          this.list = err.data;
      });
    },
    confirmDestroy(id){
      this.id_item = id
      this.$vs.dialog({
        type:'confirm',
        color: 'danger',
        title: `Bạn có chắc chắn`,
        text: 'Xóa bản tin này',
        accept:this.destroy
      })
    },
    searchBlog() {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }
      this.timer = setTimeout(() => {
          this.listBlog({ keyword: this.keyword })
          .then(response => {
              this.list = response.data;
          }).catch(err => {
              this.list = err.data;
          });
      }, 800);
    },
    destroy(){
      this.deleteBlog({id:this.id_item}).then(response => {
        this.listBlogs();
        this.loadings(false);
        this.$success('xóa thành công');
      });
    }
  },
  mounted() {
    this.listBlogs();
  }
};
</script>