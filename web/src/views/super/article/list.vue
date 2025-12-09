<template>
  <div v-loading="loading" class="dashboard-container">
    <div class="search-block">
      <el-form :inline="true" :model="search_form">
        <el-form-item>
          <el-date-picker
            v-model="search_form.time"
            type="datetimerange"
            value-format="timestamp"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
          />
        </el-form-item>
        <el-form-item>
          <el-input v-model="search_form.keyword" placeholder="关键词" />
        </el-form-item>
        <el-form-item>
          <el-select v-model="search_form.cate_id" placeholder="全部分类">
            <el-option label="全部分类" :value="0" />
            <el-option
              v-for="item in cates"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit_search">查询</el-button>
          <el-button @click="addVisible = true">新增</el-button>
          <el-button type="danger" @click="del">删除</el-button>
        </el-form-item>
      </el-form>
    </div>

    <div class="data-list">
      <template>
        <el-table
          v-loading="loading"
          :data="items"
          style="width: 100%"
          empty-text="暂无数据"
          @selection-change="handleSelectionChange"
        >
          >
          <el-table-column type="selection" width="55" />
          <el-table-column prop="id" label="ID" width="80" />
          <el-table-column
            prop="cate_name"
            label="分类"
            width="100"
          />
          <el-table-column label="标题" width="300">
            <template slot-scope="scope">
              <div class="text-line">{{ scope.row.title }}</div>
            </template>
          </el-table-column>
          <el-table-column label="描述内容">
            <template slot-scope="scope">
              <div class="text-line">{{ scope.row.desc }}</div>
            </template>
          </el-table-column>
          <el-table-column
            prop="create_time"
            label="创建时间"
            width="160"
          />
          <el-table-column
            prop="update_time"
            label="更新时间"
            width="160"
          />
          <el-table-column label="操作" width="100">
            <template slot-scope="scope">
              <el-button
                type="text"
                size="small"
                @click="bind_item_edit(scope.row)"
              >编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
      </template>
    </div>
    <div class="page-block" style="text-align: center; margin: 10px 0">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        :small="true"
        :hide-on-single-page="true"
        :page-size="search_form.pagesize"
        @current-change="current_change"
      />
    </div>
    <el-dialog title="新增" :visible.sync="addVisible" :fullscreen="false">
      <div style="overflow: auto">
        <el-form ref="add_form" :model="add_form">
          <el-form-item label="分类" label-width="80px" prop="cate_id">
            <el-select v-model="add_form.cate_id" placeholder="全部分类">
              <el-option
                v-for="item in cates"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="标题" label-width="80px" prop="title">
            <el-input
              v-model="add_form.title"
              autocomplete="off"
              placeholder="请输入标题"
            />
          </el-form-item>
          <el-form-item label="描述" label-width="80px" prop="desc">
            <el-input
              v-model="add_form.desc"
              autocomplete="off"
              placeholder="请输入描述内容"
            />
          </el-form-item>
          <el-form-item label="详细内容" label-width="80px" prop="content">
            <tinymce
              v-model="add_form.content"
              :height="200"
              :width="700"
              :upload="upload"
            />
          </el-form-item>
        </el-form>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_add">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog title="编辑" :visible.sync="editVisible">
      <div style="overflow: auto">
        <el-form ref="edit_form" :model="add_form">
          <el-form-item label="分类" label-width="80px" prop="cate_id">
            <el-select v-model="edit_form.cate_id" placeholder="全部分类">
              <el-option
                v-for="item in cates"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="标题" label-width="80px" prop="title">
            <el-input
              v-model="edit_form.title"
              autocomplete="off"
              placeholder="请输入标题"
            />
          </el-form-item>
          <el-form-item label="描述" label-width="80px" prop="desc">
            <el-input
              v-model="edit_form.desc"
              autocomplete="off"
              placeholder="请输入描述内容"
            />
          </el-form-item>
          <el-form-item label="详细内容" label-width="80px" prop="content">
            <tinymce
              ref="tinymce_edit"
              v-model="edit_form.content"
              :height="200"
              :width="700"
            />
          </el-form-item>
        </el-form>
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_edit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce'

export default {
  components: {
    Tinymce
  },
  data() {
    return {
      search_form: {
        keyword: '',
        time: '',
        page: 1,
        pagesize: 20
      },
      items: [],
      total: 0, // 总条数
      addVisible: false,
      add_form: {
        cate_id: '',
        title: '',
        desc: '',
        content: ''
      },
      editVisible: false,
      edit_form: {
        id: '',
        cate_id: '',
        title: '',
        desc: '',
        content: ''
      },
      loading: false,
      multipleSelection: [], // 选中数据
      cates: [],
      editorOption: {}
    }
  },
  created() {
    this.load_items()
    this.load_cate()
  },
  methods: {
    submit_search() {
      this.search_form.page = 1
      this.load_items()
      return false
    },
    load_items() {
      this.loading = true
      this.$store
        .dispatch('super/articleList/items', this.search_form)
        .then((res) => {
          const count = res.count
          const items = res.items

          this.items = items
          this.total = count

          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
    current_change(page) {
      this.search_form.page = page
      this.load_items()
    },
    bind_item_edit(item) {
      this.edit_form = item
      this.editVisible = true
      this.$refs['tinymce_edit'].setContent(item.content)
    },
    submit_add() {
      this.loading = true
      this.$store
        .dispatch('super/articleList/add', this.add_form)
        .then((res) => {
          this.$message({
            message: res,
            type: 'success'
          })
          this.loading = false
          this.addVisible = false
          this.load_items()
        })
        .catch(() => {
          this.loading = false
        })
    },
    submit_edit() {
      this.loading = true
      this.$store
        .dispatch('super/articleList/edit', this.edit_form)
        .then((res) => {
          this.$message({
            message: res,
            type: 'success'
          })
          this.loading = false
          this.editVisible = false
          this.load_items()
        })
        .catch(() => {
          this.loading = false
        })
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    },
    del() {
      var items = this.multipleSelection
      if (items.length <= 0) {
        this.$message({
          message: '至少选中一个',
          type: 'error'
        })
        return
      }
      this.$confirm('此操作将永久删除选中数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      })
        .then(() => {
          var ids = []
          items.forEach((item) => {
            ids.push(item.id)
          })
          this.loading = true
          this.$store
            .dispatch('super/articleList/del', { ids: ids })
            .then((res) => {
              this.$message({
                message: res,
                type: 'success'
              })
              this.loading = false
              this.load_items()
            })
            .catch(() => {
              this.loading = false
            })
        })
        .catch(() => {})
    },
    load_cate() {
      this.loading = true
      this.$store
        .dispatch('super/articleList/cates')
        .then((res) => {
          this.cates = res
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    }
  }
}
</script>

  <style lang="scss" scoped>
.dashboard {
  &-container {
    margin: 30px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
.text-line {
  overflow: hidden;
  word-break: break-all; /* break-all(允许在单词内换行。) */
  text-overflow: ellipsis; /* 超出部分省略号 */
  display: -webkit-box; /** 对象作为伸缩盒子模型显示 **/
  -webkit-box-orient: vertical; /** 设置或检索伸缩盒对象的子元素的排列方式 **/
  -webkit-line-clamp: 1; /** 显示的行数 **/
}
</style>
