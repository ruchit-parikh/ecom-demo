<template>
  <VAppBar :elevation="2" height="80" color="primary">
    <VContainer>
      <div class="d-flex justify-space-between">
        <div class="d-flex align-center">
          <img src="@/assets/logo.svg" alt="Logo" width="32px" class="logo" />
          <img src="@/assets/logo-text.svg" alt="Logo Text" class="logo-text" />
        </div>

        <div class="d-flex justify-space-between align-center">
          <RouterLink class="text-decoration-none mx-5 text-on-primary" to="#">Products</RouterLink>
          <RouterLink class="text-decoration-none mx-5 text-on-primary" to="#"
            >Promotions</RouterLink
          >
          <RouterLink class="text-decoration-none mx-5 text-on-primary" to="#">Blog</RouterLink>
        </div>

        <div class="d-flex justify-space-between align-center">
          <VBtn variant="outlined" class="mr-3" prepend-icon="mdi-cart" size="large">Cart</VBtn>
          <VBtn variant="outlined" v-if="!isLoggedIn" size="large" @click="showLoginModal"
            >Login</VBtn
          >
          <VBtn variant="outlined" v-if="isLoggedIn" size="large" @click="logout">Logout</VBtn>
          <VAvatar class="ml-3" v-if="user && avatarUrl" size="36px">
            <VImg v-if="avatarUrl" :alt="user.first_name" :src="avatarUrl" />
          </VAvatar>
        </div>
      </div>
    </VContainer>
  </VAppBar>
</template>

<script lang="ts">
import { computed, defineComponent, ref, watchEffect } from 'vue'
import { VAppBar, VBtn, VContainer, VAvatar, VImg } from 'vuetify/components'
import { useUserStore } from '@/stores/user'
import auth from '@/services/auth'
import eventBus from '@/plugins/eventBus'
import files from '@/services/files'
import router from '@/router'

export default defineComponent({
  name: 'Navbar',
  components: { VAppBar, VBtn, VContainer, VAvatar, VImg },
  setup() {
    const userStore = useUserStore()

    const showLoginModal = () => {
      eventBus.emit('user-unauthorized')
    }

    const logout = () => {
      return auth.logout().then((r) => {
        userStore.setUser(null)
        router.push('/')

        return r
      })
    }

    const avatar = ref<string | null>('')
    const user = computed(() => userStore.user)
    const isLoggedIn = computed(() => !!userStore.user)
    const avatarUrl = computed(() => (avatar.value ? avatar.value : null))

    watchEffect(async () => {
      if (user.value && user.value.avatar) {
        files.getUrl(user.value.avatar).then((r) => {
          avatar.value = r

          return r
        })
      } else {
        avatar.value = null
      }
    })

    return {
      showLoginModal,
      logout,
      isLoggedIn,
      user,
      avatarUrl
    }
  }
})
</script>

<style>
.text-on-primary {
  color: var(--v-theme-on-primary);
  text-transform: uppercase;
}
</style>
