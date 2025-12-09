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
          <el-button type="primary" @click="submit_search">查询</el-button>
          <el-button @click="addVisible = true">新增</el-button>
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
        >
          <el-table-column prop="id" label="ID" />
          <el-table-column prop="name" label="名称" />
          <el-table-column prop="desc" label="备注" />
          <el-table-column prop="create_time" label="创建时间" />
          <el-table-column prop="update_time" label="更新到期" />
          <el-table-column
            label="操作"
            width="100"
          >
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="bind_item_edit(scope.row)">编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
      </template>
    </div>
    <div class="page-block" style="text-align: center;margin: 10px 0;">
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
    <el-dialog title="添加" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form" :rules="roles_add" :loading="form_loading">
        <el-form-item label="名称" label-width="80px" prop="name">
          <el-input v-model="add_form.name" autocomplete="off" placeholder="请输入名称" />
        </el-form-item>
        <el-form-item label="备注" label-width="80px" prop="desc">
          <el-input v-model="add_form.desc" autocomplete="off" placeholder="请输入备注" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_add">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog title="编辑" :visible.sync="editVisible">
      <el-form ref="edit_form" :model="edit_form" :rules="roles_edit" :loading="form_loading">
        <el-form-item label="名称" label-width="80px" prop="name">
          <el-input v-model="edit_form.name" autocomplete="off" placeholder="请输入名称" />
        </el-form-item>
        <el-form-item label="备注" label-width="80px" prop="desc">
          <el-input v-model="edit_form.desc" autocomplete="off" placeholder="请输入备注" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_edit">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
export default {
  data() {
    const validateName = (rule, value, callback) => {
      if (value && value.length === 0) {
        callback(new Error('请输入名称'))
      } else {
        callback()
      }
    }
    return {
      search_form: {
        keyword: '',
        time: '',
        page: 1,
        pagesize: 20,
        cate_id: 0
      },
      items: [

      ],
      total: 0, // 总条数
      loading: false,
      form_loading: false,
      addVisible: false,
      add_form: {
        name: '',
        desc: ''
      },
      roles_add: {
        name: [{ required: true, trigger: 'blur', validator: validateName }]
      },
      editVisible: false,
      edit_form: {
        id: '',
        name: '',
        desc: ''
      },
      roles_edit: {
        name: [{ required: true, trigger: 'blur', validator: validateName }]
      }
    }
  },
  created() {
    this.load_items()
  },
  methods: {
    submit_search() {
      this.search_form.page = 1
      this.load_items()
      return false
    },
    load_items() {
      this.loading = true
      this.$store.dispatch('super/activationCate/items', this.search_form).then((res) => {
        const count = res.count
        const items = res.items

        this.items = items
        this.total = count

        this.loading = false
      }).catch(() => {
        this.loading = false
      })
    },
    current_change(page) {
      this.search_form.page = page
      this.load_items()
    },
    submit_add() {
      this.$refs.add_form.validate(valid => {
        if (valid) {
          this.form_loading = true
          this.$store.dispatch('super/activationCate/add', this.add_form).then((res) => {
            this.$message({
              message: res,
              type: 'success'
            })
            this.form_loading = false
            this.addVisible = false
            this.load_items()
          }).catch(() => {
            this.form_loading = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    bind_item_edit(item) {
      this.edit_form = item
      this.editVisible = true
    },
    submit_edit() {
      this.$refs.edit_form.validate(valid => {
        if (valid) {
          this.form_loading = true
          this.$store.dispatch('super/activationCate/edit', this.edit_form).then((res) => {
            this.$message({
              message: res,
              type: 'success'
            })
            this.form_loading = false
            this.editVisible = false
            this.load_items()
          }).catch(() => {
            this.form_loading = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
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
  </style>
