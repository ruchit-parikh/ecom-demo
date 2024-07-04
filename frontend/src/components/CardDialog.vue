<template>
  <VDialog class="pa-3" max-width="600px" v-model="isOpened" @closed="closeDialog">
    <VCard class="py-4 rounded-0">
      <VContainer>
        <VRow justify="center">
          <VCol cols="9">
            <slot></slot>
          </VCol>
        </VRow>
      </VContainer>
    </VCard>
  </VDialog>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { VCard, VCol, VContainer, VDialog, VRow } from 'vuetify/components'

export default defineComponent({
  name: 'CardDialog',
  components: {
    VDialog,
    VCard,
    VContainer,
    VRow,
    VCol
  },
  setup() {
    const isOpened = ref(false)

    const openDialog = () => {
      isOpened.value = true
    }

    const closeDialog = () => {
      isOpened.value = false
    }

    return {
      isOpened,
      openDialog,
      closeDialog
    }
  }
})
</script>
