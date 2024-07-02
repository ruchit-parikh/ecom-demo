<template>
  <VCard class="mx-auto h100">
    <VContainer>
      <VRow
          align-content="center"
          class="fill-height"
          justify="center"
      >
        <VCol
            class="text-subtitle-1 text-center"
            cols="12"
        >
          Please wait...
        </VCol>
        <VCol cols="6">
          <VProgressLinear
              color="green"
              height="6"
              indeterminate
              rounded
          ></VProgressLinear>
        </VCol>
      </VRow>
    </VContainer>
  </VCard>
</template>

<style>
  .h100 {
    height: 100vh;
    display: flex;
    align-items: center;
  }
</style>

<script lang="ts">
import { defineComponent } from 'vue';
import { VCard, VContainer, VRow, VCol, VProgressLinear } from 'vuetify/components'

export default defineComponent({
  name: 'PageLoader',
  components: { VCard, VContainer, VRow, VCol, VProgressLinear }
})
</script>
