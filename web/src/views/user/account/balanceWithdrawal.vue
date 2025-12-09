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
        <el-table v-loading="loading" :data="items" style="width: 100%" empty-text="暂无数据">
          <el-table-column prop="order_id" label="流水号" width="180" />
          <el-table-column prop="money" label="金额" width="80" />
          <el-table-column prop="pay" label="实付" width="80" />
          <el-table-column prop="rate" label="手续费" width="80" />
          <el-table-column prop="account" label="支付宝" width="150" />
          <el-table-column prop="account_name" label="姓名" width="80" />
          <el-table-column prop="desc" label="备注" />
          <el-table-column prop="create_time" width="160" label="申请时间" />
          <el-table-column prop="auth_time" width="160" label="审核时间" />
          <el-table-column prop="status" label="状态" width="150">
            <template slot-scope="scope">
              <el-tag v-if="scope.row.status === 1">审核中</el-tag>
              <el-tag v-if="scope.row.status === 2" type="success">成功</el-tag>
              <el-tag v-if="scope.row.status === -2" type="info">失败</el-tag>
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

    <el-dialog v-loading="loading" title="提现" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form">
        <el-form-item label="账户余额" label-width="80px">
          <el-input v-model="userinfo.balance" autocomplete="off" placeholder="当前账号余额" :disabled="true" />
        </el-form-item>
        <el-form-item label="提现金额" label-width="80px">
          <el-input v-model="add_form.money" autocomplete="off" placeholder="请输入提现金额" />
        </el-form-item>
        <el-form-item label="支付宝" label-width="80px">
          <el-input v-model="add_form.account" autocomplete="off" placeholder="请输入支付宝账号" />
        </el-form-item>
        <el-form-item label="姓名" label-width="80px">
          <el-input v-model="add_form.account_name" autocomplete="off" placeholder="请输入支付宝姓名" />
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
import { mapGetters } from 'vuex'
export default {
  computed: {
    ...mapGetters(['userinfo'])
  },
  data() {
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
      this.$store.dispatch('user/balanceWithdrawal/items', this.search_form).then((res) => {
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
      this.loading = true
      this.$store.dispatch('user/balanceWithdrawal/add', this.add_form).then((res) => {
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
