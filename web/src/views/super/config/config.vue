<template>
  <div v-loading="loading" class="dashboard">
    <el-form ref="form" label-width="100px" style="max-width: 1000px">
      <el-form-item v-for="item in items" :label="item.name">
        <el-input v-if="item.type == 'text'" v-model="item.content" />
        <el-input
          v-if="item.type == 'textarea'"
          v-model="item.content"
          type="textarea"
        />
        <el-input v-if="item.type == 'image'" v-model="item.content">
          <SingleImageUpload
            slot="append"
            @input="
              (val) => {
                set_image(val, item.key)
              }
            "
          />
        </el-input>
        <el-select v-if="item.type == 'select'" v-model="item.content">
          <el-option
            v-for="option in item.params"
            :key="option.id"
            :label="option.name"
            :value="option.id"
          />
        </el-select>
        <div style="font-size: 12px; color: #909399">
          {{ item.placeholder }}
        </div>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import SingleImageUpload from '@/components/Upload/SingleImage'

export default {
  components: {
    SingleImageUpload
  },
  data() {
    return {
      loading: false,
      upload: {},
      items: []
    }
  },
  created() {
    this.load_items()
  },
  methods: {
    onSubmit: function() {
      this.loading = true
      this.$store
        .dispatch('super/config/save', this.items)
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
    },
    load_items: function() {
      this.loading = true
      this.$store
        .dispatch('super/config/items')
        .then((res) => {
          this.items = res
          this.loading = false
        })
        .catch(() => {})
    },
    set_image: function(url, key) {
      this.items.map((item) => {
        if (item.key == key) {
          item.content = url
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard {
  margin: 20px;
}
</style>
