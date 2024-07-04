<template>
  <VApp>
    <Navbar />

    <VMain>
      <RouterView />

      <slot></slot>
    </VMain>
  </VApp>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Navbar from '@/layouts/components/Navbar.vue'
import { VApp, VMain } from 'vuetify/components'
import { RouterView } from 'vue-router'

export default defineComponent({
  name: 'Layout',
  components: { Navbar, VApp, VMain, RouterView }
})
</script>
