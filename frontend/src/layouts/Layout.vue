<template>
  <VApp>
    <Navbar />

    <VMain>
      <RouterView />
    </VMain>
  </VApp>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Navbar from '@/components/Navbar.vue'
import { VApp, VMain } from 'vuetify/components'

export default defineComponent({
  name: 'Layout',
  components: { Navbar, VApp, VMain }
});
</script>
