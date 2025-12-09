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
          <el-table-column prop="id" label="ID" width="80" />
          <el-table-column prop="type" label="类型" width="100" align="center">
            <template slot-scope="scope">
              <el-tag v-if="scope.row.type === 'in'">收入</el-tag>
              <el-tag
                v-if="scope.row.type === 'out'"
                type="danger"
              >支出</el-tag>

              <el-tag v-if="scope.row.type === 'recharge'">充值</el-tag>

              <el-tag
                v-if="scope.row.type === 'withdrawal'"
                type="danger"
              >提现</el-tag>
              <el-tag
                v-if="scope.row.type === 'withdrawal_fail'"
              >提现失败</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="money" label="金额" width="100" />
          <el-table-column prop="balance" label="余额" width="100" />
          <el-table-column prop="desc" label="备注" />
          <el-table-column prop="create_time" width="160" label="时间" />
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
      loading: false
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
        .dispatch('user/balanceLog/items', this.search_form)
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
