<template>
  <VAppBar :elevation="2" color="primary">
    <VContainer>
      <div class="d-flex justify-space-between">
        <div class="d-flex align-center">
          <img src="@/assets/logo.svg" alt="Logo" width="32px" class="logo" />
          <img src="@/assets/logo-text.svg" alt="Logo Text" class="logo-text" />
        </div>

        <div class="d-flex justify-space-between align-center">
          <VBtn variant="outlined" color="#FFFFFF" class="mr-3" prepend-icon="mdi-cart" size="large"
            >Cart</VBtn
          >
          <VBtn
            variant="outlined"
            color="#FFFFFF"
            v-if="!isLoggedIn"
            size="large"
            @click="showLoginModal"
            >Login</VBtn
          >
          <VBtn variant="outlined" color="#FFFFFF" v-if="isLoggedIn" size="large" @click="logout"
            >Logout</VBtn
          >
        </div>
      </div>
    </VContainer>
  </VAppBar>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'
import { VAppBar, VBtn, VContainer } from 'vuetify/components'
import { useUserStore } from '@/stores/user'
import auth from '@/services/auth'
import eventBus from '@/plugins/eventBus'

export default defineComponent({
  name: 'Navbar',
  components: { VAppBar, VBtn, VContainer },
  setup() {
    const userStore = useUserStore()

    const showLoginModal = () => {
      eventBus.emit('user-unauthorized')
    }

    const logout = () => {
      return auth.logout().then((r) => {
        userStore.setUser(null)

        return r
      })
    }

    const isLoggedIn = computed(() => !!userStore.user)

    return {
      showLoginModal,
      logout,
      isLoggedIn
    }
  }
})
</script>
