<template>
  <PageLoader v-if="loading" />
  <Layout v-else>
    <LoginModal />
  </Layout>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import auth from '@/services/auth'
import { useUserStore } from '@/stores/user'
import PageLoader from '@/components/PageLoader.vue'
import Layout from '@/layouts/Layout.vue'
import LoginModal from '@/components/LoginModal.vue'

export default defineComponent({
  name: 'App',
  components: { LoginModal, PageLoader, Layout },

  setup() {
    const userStore = useUserStore()
    const loading = ref(true)

    onMounted(() => {
      auth.getCurUser().then((r) => {
        userStore.setUser(r)

        loading.value = false

        return r
      })
    })

    return { loading }
  }
})
</script>
