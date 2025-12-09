<template>
  <div class="app-container">
    <el-form
      ref="form"
      :model="form"
      label-width="120px"
      :rules="passwordRules"
    >
      <el-form-item label="原密码" prop="password_old">
        <el-input
          v-model="form.password_old"
          placeholder="请输入原密码，留空不修改"
        />
      </el-form-item>
      <el-form-item label="新密码" prop="password_new">
        <el-input
          v-model="form.password_new"
          placeholder="请输入不少于6位新密码，留空不修改"
        />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">提交</el-button>
        <el-button @click="onCancel">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
export default {
  data() {
    const validatePasswordOld = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('请输入至少6位的密码'))
      } else {
        callback()
      }
    }
    const validatePasswordNew = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('请输入至少6位的密码'))
      } else {
        callback()
      }
    }
    return {
      form: {
        password_old: '',
        password_new: ''
      },
      passwordRules: {
        password_old: [
          { required: true, trigger: 'blur', validator: validatePasswordOld }
        ],
        password_new: [
          { required: true, trigger: 'blur', validator: validatePasswordNew }
        ]
      }
    }
  },
  methods: {
    onSubmit() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$store
            .dispatch('user/password', this.form)
            .then((res) => {
              this.$message({
                message: res,
                type: 'success'
              })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    onCancel() {
      this.$router.go(-1)
    }
  }
}
</script>
<style scoped></style>
