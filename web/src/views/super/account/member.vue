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
          <el-table-column type="selection" width="55" />
          <el-table-column prop="id" label="ID" width="80" />
          <el-table-column label="角色" width="80" align="center">
            <template slot-scope="scope">{{ roles[scope.row.type] }}</template>
          </el-table-column>
          <el-table-column prop="username" label="账号" />
          <el-table-column prop="balance" label="余额" width="100" />
          <el-table-column prop="pusername" label="上级" />
          <el-table-column prop="create_time" label="注册时间" width="160" align="center" />
          <el-table-column prop="vip_time" label="会员到期" width="160" align="center" />
          <el-table-column prop="rate_balance_withdrawal" label="提现费率" width="80" align="center" />
          <el-table-column label="状态" width="80" align="center">
            <template slot-scope="scope">
              <el-tag v-if="scope.row.status === 1" disable-transitions>正常</el-tag>
              <el-tag v-if="scope.row.status === -1" disable-transitions type="info">禁用</el-tag>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="150">
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="bind_balance_in(scope.row)">充值</el-button>
              <el-button type="text" size="small" @click="bind_balance_out(scope.row)">扣费</el-button>
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
    <el-dialog v-loading="loading" title="添加" :visible.sync="addVisible">
      <el-form ref="add_form" :model="add_form">
        <el-form-item label="角色" label-width="80px">
          <el-select v-model="add_form.type" placeholder="选择角色">
            <el-option v-for="(val,key,i) in roles" :key="key" :label="val" :value="key" />
          </el-select>
        </el-form-item>
        <el-form-item label="账号名称" label-width="80px" prop="username">
          <el-input v-model="add_form.username" autocomplete="off" placeholder="请输入账号名称" />
        </el-form-item>
        <el-form-item label="账号密码" label-width="80px" prop="password">
          <el-input v-model="add_form.password" autocomplete="off" placeholder="密码长度不可小于6个字符" />
        </el-form-item>
        <el-form-item label="上级ID" label-width="80px" prop="pid">
          <el-input v-model="add_form.pid" autocomplete="off" placeholder="请输入上级ID" />
        </el-form-item>
        <el-form-item label="提现费率" label-width="80px">
          <el-input
            v-model="add_form.rate_balance_withdrawal"
            autocomplete="off"
            placeholder="请输入提现费率"
          />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_add">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog v-loading="loading" title="编辑" :visible.sync="editVisible">
      <el-form ref="edit_form" :model="edit_form">
        <el-form-item label="角色" label-width="80px">
          <el-select v-model="edit_form.type" placeholder="选择角色">
            <el-option v-for="(val,key,i) in roles" :key="key" :label="val" :value="key" />
          </el-select>
        </el-form-item>
        <el-form-item label="账号名称" label-width="80px" prop="username">
          <el-input
            v-model="edit_form.username"
            autocomplete="off"
            :disabled="true"
            placeholder="请输入账号名称"
          />
        </el-form-item>
        <el-form-item label="账号密码" label-width="80px" prop="password">
          <el-input
            v-model="edit_form.password"
            autocomplete="off"
            placeholder="密码长度不可小于6个字符,留空不修改"
          />
        </el-form-item>
        <el-form-item label="上级ID" label-width="80px" prop="pid">
          <el-input v-model="edit_form.pid" autocomplete="off" placeholder="请输入上级ID" />
        </el-form-item>
        <el-form-item label="提现费率" label-width="80px">
          <el-input
            v-model="edit_form.rate_balance_withdrawal"
            autocomplete="off"
            placeholder="请输入提现费率"
          />
        </el-form-item>
        <el-form-item label="状态" label-width="80px" prop="status">
          <el-select v-model="edit_form.status" placeholder="请选择">
            <el-option label="启用" :value="1" />
            <el-option label="停用" :value="-1" />
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_edit">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog v-loading="loading" title="充值" :visible.sync="balanceInVisible">
      <el-form ref="formBalanceInItem" :model="formBalanceInItem">
        <el-form-item label="账号名称" label-width="80px" prop="username">
          <el-input
            v-model="formBalanceInItem.username"
            autocomplete="off"
            :disabled="true"
            placeholder="账号名称"
          />
        </el-form-item>
        <el-form-item label="余额" label-width="80px" prop="balance">
          <el-input
            v-model="formBalanceInItem.balance"
            autocomplete="off"
            :disabled="true"
            placeholder="账号余额"
          />
        </el-form-item>
        <el-form-item label="充值金额" label-width="80px" prop="money">
          <el-input v-model="formBalanceInItem.money" autocomplete="off" placeholder="请输入充值金额" />
        </el-form-item>
        <el-form-item label="备注" label-width="80px" prop="desc">
          <el-input v-model="formBalanceInItem.desc" autocomplete="off" placeholder="请输入备注" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="balanceInVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_balance_in">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog v-loading="loading" title="扣费" :visible.sync="balanceOutVisible">
      <el-form ref="formBalanceOutItem" :model="formBalanceOutItem">
        <el-form-item label="账号名称" label-width="80px" prop="username">
          <el-input
            v-model="formBalanceOutItem.username"
            autocomplete="off"
            :disabled="true"
            placeholder="账号名称"
          />
        </el-form-item>
        <el-form-item label="余额" label-width="80px" prop="balance">
          <el-input
            v-model="formBalanceOutItem.balance"
            autocomplete="off"
            :disabled="true"
            placeholder="账号余额"
          />
        </el-form-item>
        <el-form-item label="扣除金额" label-width="80px" prop="money">
          <el-input v-model="formBalanceOutItem.money" autocomplete="off" placeholder="请输入扣除金额" />
        </el-form-item>
        <el-form-item label="备注" label-width="80px" prop="desc">
          <el-input v-model="formBalanceOutItem.desc" autocomplete="off" placeholder="请输入备注" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="balanceOutVisible = false">取 消</el-button>
        <el-button type="primary" @click="submit_balance_out">确 定</el-button>
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
        type: 'user',
        username: '',
        password: '',
        pid: ''
      },
      editVisible: false,
      edit_form: {
        id: '',
        type: '',
        username: '',
        password: '',
        pid: ''
      },
      formBalanceInItem: {
        id: '',
        username: '',
        balance: '',
        money: '',
        desc: ''
      },
      balanceInVisible: false,
      formBalanceOutItem: {
        id: '',
        username: '',
        balance: '',
        money: '',
        desc: ''
      },
      balanceOutVisible: false,
      multipleSelection: [], // 选中数据
      roles: {}
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
        .dispatch('super/member/items', this.search_form)
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
        .dispatch('super/member/add', this.add_form)
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
        .dispatch('super/member/edit', this.edit_form)
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
    bind_balance_in(item) {
      this.formBalanceInItem = item
      this.balanceInVisible = true
    },
    submit_balance_in() {
      this.loading = true
      this.$store
        .dispatch('super/member/balanceIn', this.formBalanceInItem)
        .then(res => {
          this.$message({
            message: res,
            type: 'success'
          })
          this.loading = false
          this.balanceInVisible = false
          this.load_items()
        })
        .catch(() => {
          this.loading = false
        })
    },
    bind_balance_out(item) {
      this.formBalanceOutItem = item
      this.balanceOutVisible = true
    },
    submit_balance_out() {
      this.loading = true
      this.$store
        .dispatch('super/member/balanceOut', this.formBalanceOutItem)
        .then(res => {
          this.$message({
            message: res,
            type: 'success'
          })
          this.loading = false
          this.balanceOutVisible = false
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
            .dispatch('super/member/del', { ids: ids })
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
        .catch(() => {})
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
