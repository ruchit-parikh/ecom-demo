<template>
  <VContainer>
    <VRow class="text-center text-primary">
      <VCol>
        <div v-if="user">
          <h1 class="mb-5">
            Hey {{ user.first_name + ' ' + user.last_name }}! welcome to my Awesome Ecom Store!!!
          </h1>
          <VBtn @click="router.push('orders')" color="primary">Check your orders</VBtn>
        </div>
        <div v-else>
          <h1 class="mb-5">Welcome to my Awesome Ecom Store!!!</h1>
          <VBtn @click="login" color="primary">Login</VBtn>
        </div>
      </VCol>
    </VRow>
  </VContainer>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'
import { VCol, VContainer, VRow } from 'vuetify/components'
import { useUserStore } from '@/stores/user'
import router from '@/router'
import eventBus from '@/plugins/eventBus'

export default defineComponent({
  name: 'Home',
  components: {
    VContainer,
    VRow,
    VCol
  },
  setup() {
    const userStore = useUserStore()
    const user = computed(() => userStore.user)

    const login = () => eventBus.emit('user-unauthorized')

    return { user, router, login }
  }
})
</script>
