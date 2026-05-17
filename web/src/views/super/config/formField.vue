<template>
  <div v-loading="loading" class="dashboard-container">
    <div class="search-block">
      <el-form :inline="true" :model="search_form">
        <el-form-item>
          <el-date-picker v-model="search_form.time" type="datetimerange" value-format="timestamp" range-separator="至"
            start-placeholder="开始日期" end-placeholder="结束日期" />
        </el-form-item>
        <el-form-item>
          <el-input v-model="search_form.name" placeholder="搜索名称" />
        </el-form-item>
        <el-form-item>
          <el-input v-model="search_form.key" placeholder="搜索唯一标识" />
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
        <el-table v-loading="loading" :data="items" style="width: 100%" empty-text="暂无数据"
          @selection-change="handleSelectionChange">
          <el-table-column type="selection" width="55" />
          <el-table-column prop="id" label="ID" width="80" />
          <el-table-column prop="name" label="名称" />
          <el-table-column prop="key" label="唯一标识" />
          <el-table-column label="创建时间" width="160" align="center">
            <template slot-scope="scope">
              <div>{{ scope.row.create_time * 1000 | formatDate }}</div>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="120" fixed="right">
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="bind_item_edit(scope.row)">编辑</el-button>
              <el-button type="text" size="small" @click="bind_item_field(scope.row)">字段</el-button>
            </template>
          </el-table-column>
        </el-table>
      </template>
    </div>
    <div class="page-block" style="text-align: center;margin: 10px 0;">
      <el-pagination background layout="prev, pager, next" :total="total" :small="true" :hide-on-single-page="true"
        :page-size="search_form.pagesize" @current-change="current_change" />
    </div>
    <el-dialog v-loading="loading" title="添加" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form">
        <el-form-item label="唯一标识" label-width="80px">
          <el-input v-model="add_form.key" autocomplete="off" placeholder="请输入唯一标识" />
        </el-form-item>
        <el-form-item label="名称" label-width="80px">
          <el-input v-model="add_form.name" autocomplete="off" placeholder="请输入名称" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_add">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog v-loading="loading" title="编辑" :visible.sync="editVisible">
      <el-form ref="edit_form" :model="edit_form">
        <el-form-item label="唯一标识" label-width="80px">
          <el-input v-model="edit_form.key" autocomplete="off" placeholder="请输入唯一标识" />
        </el-form-item>
        <el-form-item label="名称" label-width="80px">
          <el-input v-model="edit_form.name" autocomplete="off" placeholder="请输入名称" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_edit">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog v-loading="loading" title="字段" :visible.sync="fieldVisible">
      <el-form ref="edit_form" :model="edit_form">
        <el-form-item label="唯一标识" label-width="80px">
          <el-input v-model="field_form.key" autocomplete="off" placeholder="请输入唯一标识" />
        </el-form-item>
        <el-form-item label="名称" label-width="80px">
          <el-input v-model="field_form.name" autocomplete="off" placeholder="请输入名称" />
        </el-form-item>


        <el-row :gutter="20">
          <el-col :span="6">
            <el-form-item label="类型" label-width="80px">
              <el-select v-model="field_template.stypetatus" placeholder="请选择">
              <el-option label="文本型" value="text" />
              <el-option label="下拉选项" value="select" />
            </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="字段" label-width="80px">
              <el-input v-model="field_template.key" autocomplete="off" placeholder="仅支持字母" />
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="标题" label-width="80px">
              <el-input v-model="field_template.title" autocomplete="off" placeholder="前端展示" />
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="描述" label-width="80px">
              <el-input v-model="field_template.placeholder" autocomplete="off" placeholder="提示语，可空" />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="fieldVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_field">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import setting from '@/settings.js'
export default {
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
      loading: false,
      addVisible: false,
      add_form: {
        name: '',
        key: '',
      },
      editVisible: false,
      edit_form: {
        id: '',
        name: '',
        key: '',
      },
      fieldVisible: false,
      field_form: {
        key: '',
        field_json: {},
      },
      multipleSelection: [], // 选中数据
      roles: {},
      field_template:{
        type:'text',
        key:'',
        name:'',
        placeholder:''
      }
    }
  },
  created() {
    this.load_items()
    this.roles = setting.roles
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
        .dispatch('super/formField/items', this.search_form)
        .then(res => {
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
    submit_add() {
      this.loading = true
      this.$store
        .dispatch('super/formField/add', this.add_form)
        .then(res => {
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
    bind_item_edit(item) {
      this.edit_form = item
      this.editVisible = true
    },
    submit_edit() {
      this.loading = true
      this.$store
        .dispatch('super/formField/edit', this.edit_form)
        .then(res => {
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
          items.forEach(item => {
            ids.push(item.id)
          })
          this.loading = true
          this.$store
            .dispatch('super/formField/del', { ids: ids })
            .then(res => {
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
        .catch(() => { })
    },
    bind_item_field(item){
      this.field_form = item
      this.fieldVisible = true
    },
    submit_field(){
      this.fieldVisible = false
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
</style>
