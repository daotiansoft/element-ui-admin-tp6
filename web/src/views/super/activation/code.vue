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
            <el-option
              label="全部分类"
              :value="0"
            />
            <el-option
              v-for="item in cate_list"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit_search">查询</el-button>
          <el-button @click="addVisible = true">生成</el-button>
          <el-button @click="export_data">导出</el-button>
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
          <el-table-column prop="cate_name" label="分类" width="100" />
          <el-table-column prop="code" label="激活码" width="300" />
          <el-table-column prop="time" label="时长" width="80" />
          <el-table-column prop="amount" label="次数" width="80" />
          <el-table-column prop="desc" label="备注" />
          <el-table-column prop="create_time" label="生成时间" width="160" />
          <el-table-column prop="active_time" label="激活时间" width="160" />
          <el-table-column prop="username" label="激活会员" width="100" />
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
    <el-dialog title="生成" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form" :rules="roles_add">
        <el-form-item label="分类" label-width="80px" prop="cate_id">
          <el-select v-model="add_form.cate_id" placeholder="全部分类">
            <el-option
              v-for="item in cate_list"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="数量" label-width="80px" prop="count">
          <el-input v-model="add_form.count" autocomplete="off" placeholder="请输入生成数量" />
        </el-form-item>
        <el-form-item label="时长" label-width="80px" prop="time">
          <el-input v-model="add_form.time" autocomplete="off" placeholder="请输入时长(小时)" />
        </el-form-item>
        <el-form-item label="次数" label-width="80px" prop="amount">
          <el-input v-model="add_form.amount" autocomplete="off" placeholder="请输入使用次数" />
        </el-form-item>
        <el-form-item label="备注" label-width="80px" prop="desc">
          <el-input v-model="add_form.desc" autocomplete="off" placeholder="请输入备注内容" />
        </el-form-item>

      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_add">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
export default {
  data() {
    const validateCount = (rule, value, callback) => {
      if (parseInt(value) <= 0) {
        callback(new Error('请输入正确的数量'))
      } else {
        callback()
      }
    }
    const validateCateId = (rule, value, callback) => {
      if (parseInt(value) <= 0) {
        callback(new Error('请选择分类'))
      } else {
        callback()
      }
    }
    return {
      search_form: {
        keyword: '',
        time: '',
        page: 1,
        pagesize: 20
      },
      items: [

      ],
      total: 0, // 总条数
      addVisible: false,
      add_form: {
        cate_id: '',
        count: '',
        time: '',
        amount: '',
        desc: ''
      },
      roles_add: {
        count: [{ required: true, trigger: 'blur', validator: validateCount }],
        cate_id: [{ required: true, trigger: 'blur', validator: validateCateId }]
      },
      loading: false,
      multipleSelection: [], // 选中数据
      cate_list: []
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
      this.$store.dispatch('super/activationCode/items', this.search_form).then((res) => {
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
          this.loading = true
          this.$store.dispatch('super/activationCode/add', this.add_form).then((res) => {
            this.$message({
              message: res,
              type: 'success'
            })
            this.loading = false
            this.addVisible = false
            this.load_items()
          }).catch(() => {
            this.loading = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    export_data() {
      this.$confirm('可根据搜索条件筛选数据导出, 是否继续?', '提示', {
        confirmButtonText: '继续导出',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.loading = true
        this.$store.dispatch('super/activationCode/exportData', this.search_form).then((res) => {
          if (res.url && res.url != '') {
            window.open(res.url)
          }
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
      }).catch(() => {

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
      }).then(() => {
        var ids = []
        items.forEach(item => {
          ids.push(item.id)
        })
        this.loading = true
        this.$store.dispatch('super/activationCode/del', { ids: ids }).then((res) => {
          this.$message({
            message: res,
            type: 'success'
          })
          this.loading = false
          this.load_items()
        }).catch(() => {
          this.loading = false
        })
      }).catch(() => {
      })
    },
    load_cate() {
      this.loading = true
      this.$store.dispatch('super/activationCate/getList').then((res) => {
        this.cate_list = res
        this.loading = false
      }).catch(() => {
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
  </style>
