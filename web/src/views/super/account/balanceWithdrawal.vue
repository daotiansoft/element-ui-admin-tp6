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
          <el-button @click="addVisible = true">申请提现</el-button>
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
          <el-table-column prop="order_id" label="流水号" width="180" />
          <el-table-column prop="username" label="账号" width="120" />
          <el-table-column prop="money" label="金额" width="80" />
          <el-table-column prop="pay" label="实付" width="80" />
          <el-table-column prop="rate" label="手续费" width="80" />
          <el-table-column prop="account" label="支付宝" width="150" />
          <el-table-column prop="account_name" label="姓名" width="80" />
          <el-table-column prop="desc" label="备注" />
          <el-table-column prop="create_time" width="160" label="申请时间" />
          <el-table-column prop="auth_time" width="160" label="审核时间" />
          <el-table-column
            prop="status"
            label="状态"
            width="100"
            align="center"
          >
            <template slot-scope="scope">
              <el-tag v-if="scope.row.status === 1">审核中</el-tag>
              <el-tag v-if="scope.row.status === 2" type="success">成功</el-tag>
              <el-tag v-if="scope.row.status === -2" type="info">失败</el-tag>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="100" fixed="right">
            <template slot-scope="scope">
              <el-button
                v-if="scope.row.status == 1"
                type="text"
                size="small"
                @click="auth(scope.row, 2)"
              >已打款
              </el-button>
              <el-button
                v-if="scope.row.status == 1"
                type="text"
                size="small"
                @click="auth(scope.row, -2)"
              >取消
              </el-button>
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

    <el-dialog v-loading="loading" title="提现" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form">
        <el-form-item label="账户ID" label-width="80px">
          <el-input
            v-model="add_form.user_id"
            autocomplete="off"
            placeholder="提现账号ID"
          />
        </el-form-item>
        <el-form-item label="提现金额" label-width="80px">
          <el-input
            v-model="add_form.money"
            autocomplete="off"
            placeholder="请输入提现金额"
          />
        </el-form-item>
        <el-form-item label="支付宝" label-width="80px">
          <el-input
            v-model="add_form.account"
            autocomplete="off"
            placeholder="请输入支付宝账号"
          />
        </el-form-item>
        <el-form-item label="姓名" label-width="80px">
          <el-input
            v-model="add_form.account_name"
            autocomplete="off"
            placeholder="请输入支付宝姓名"
          />
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
      add_form: {}
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
      this.$store
        .dispatch('super/balanceWithdrawal/items', this.search_form)
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
    submit_add() {
      this.loading = true
      this.$store
        .dispatch('super/balanceWithdrawal/add', this.add_form)
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
    auth(row, status) {
      const _this = this
      this.$prompt('请输入备注内容', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消'
      }).then(({ value }) => {
        const desc = value == null ? '' : value
        _this.loading = true
        _this.$store
          .dispatch('super/balanceWithdrawal/auth', {
            order_id: row.order_id,
            status: status,
            desc: desc
          })
          .then((res) => {
            _this.$message({
              message: res,
              type: 'success'
            })
            _this.loading = false
            _this.load_items()
          })
          .catch(() => {
            _this.loading = false
          })
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
