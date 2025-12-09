<template>
  <div v-loading="loading" class="dashboard">
    <el-tabs v-model="activeName" type="card">
      <el-tab-pane v-for="item in items" :label="item.name" :name="item.key">
        <tinymce v-model="item.content" :height="500" />
      </el-tab-pane>
    </el-tabs>
    <el-button
      type="primary"
      style="margin: 20px 0"
      @click="save"
    >保存</el-button>
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
      loading: false,
      items: [],
      activeName: '',
      editorOption: {}
    }
  },
  created() {
    this.load_editor()
  },
  methods: {
    save: function() {
      this.loading = true
      this.$store
        .dispatch('super/editor/save', this.items)
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
    load_editor: function() {
      this.loading = true
      this.$store
        .dispatch('super/editor/items')
        .then((res) => {
          this.items = res
          if (res[0]) {
            this.activeName = res[0].key
          }
          this.loading = false
        })
        .catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
.dashboard {
  margin: 20px;
}
</style>
