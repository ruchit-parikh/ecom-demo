<template>
  <VContainer class="mt-5">
    <VRow>
      <VCol>
        <VDataTableServer
          v-model:expanded="expanded"
          :headers="headers"
          :items="orders"
          v-model:items-per-page="itemsPerPage"
          :items-length="total"
          :loading="isLoading"
          loading-text="Loading... Please wait"
          item-value="uuid"
          show-expand
          @update:options="refreshOrders"
        >
          <template v-slot:top>
            <VToolbar flat>
              <VToolbarTitle>Orders</VToolbarTitle>
              <VDivider class="mx-4" inset vertical></VDivider>
            </VToolbar>
          </template>

          <template v-slot:[`item.created_at`]="{ item }">
            {{ formatDate(item.created_at) }}
          </template>

          <template v-slot:[`item.shipped_at`]="{ item }">
            {{ item.shipped_at ? formatDate(item.shipped_at) : 'N/A' }}
          </template>

          <template v-slot:[`item.amount`]="{ item }">
            {{ item.amount }}
          </template>

          <template v-slot:[`item.delivery_fee`]="{ item }">
            {{ item.delivery_fee }}
          </template>

          <template v-slot:[`item.payment`]="{ item }">
            {{ item.payment ? item.payment.type.toUpperCase().replace('_', ' ') : 'N/A' }}
          </template>

          <template v-slot:[`item.order_status`]="{ item }">
            {{ item.order_status[0].title.toUpperCase().replace('_', ' ') }}
          </template>

          <template v-slot:expanded-row="{ item }">
            <tr>
              <td colspan="7">
                <strong>Products:</strong>
              </td>
            </tr>
            <tr>
              <th colspan="2">Product</th>
              <th>Qty</th>
              <th colspan="2">Unit Price</th>
              <th colspan="2">Total</th>
            </tr>
            <tr v-for="orderItem in item.products" v-bind:key="orderItem.uuid">
              <td colspan="2">{{ orderItem.product }}</td>
              <td>{{ orderItem.quantity }}</td>
              <td colspan="2">{{ formatCurrency(orderItem.price) }}</td>
              <td colspan="2">{{ formatCurrency(orderItem.quantity * orderItem.price) }}</td>
            </tr>
          </template>
        </VDataTableServer>
      </VCol>
    </VRow>
  </VContainer>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import type { Ref, UnwrapRef } from 'vue'
import {
  VCol,
  VContainer,
  VDataTableServer,
  VRow,
  VToolbar,
  VToolbarTitle,
  VDivider
} from 'vuetify/components'
import ordersService from '@/services/orders'

export default defineComponent({
  name: 'Orders',
  components: {
    VDataTableServer,
    VContainer,
    VRow,
    VCol,
    VToolbar,
    VToolbarTitle,
    VDivider
  },
  setup() {
    const total: Ref<UnwrapRef<number>> = ref(0)
    const itemsPerPage: Ref<UnwrapRef<number>> = ref(10)
    const isLoading: Ref<UnwrapRef<any>> = ref(true)
    const orders: Ref<UnwrapRef<Array<Record<string, any>>>> = ref([])
    const expanded = ref<string[]>([])
    const headers = [
      { title: 'Order Amount', value: 'amount', sortable: true },
      { title: 'Delivery Fee', value: 'delivery_fee', sortable: true },
      { title: 'Created At', value: 'placed_at', sortable: true },
      { title: 'Shipped At', value: 'shipped_at', sortable: true },
      { title: 'Payment', value: 'payment' },
      { title: 'Status', value: 'status', sortable: true }
    ]

    /**
     * @param {number} amount
     *
     * @return {string}
     */
    const formatCurrency = (amount: number): string => `$ ${amount.toFixed(2)}`

    /**
     * @param {string} date
     *
     * @return {string}
     */
    const formatDate = (date: string): string => {
      const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
      }

      return new Date(date).toLocaleDateString(undefined, options)
    }

    /**
     * @param {Record<string, any>} filters
     *
     * @return {Promise<any>}
     */
    const refreshOrders = (filters: Record<string, any>) => {
      isLoading.value = true

      const page = filters && filters.page ? filters.page : 1
      const limit = filters && filters.itemsPerPage ? filters.itemsPerPage : 10
      const sortBy = filters && filters.sortBy.length ? filters.sortBy[0]['key'] : 'created_at'
      const desc = filters && filters.sortBy.length ? filters.sortBy[0]['order'] === 'desc' : false

      ordersService
        .getUserOrders(page, limit, sortBy, desc)
        .then((r) => {
          total.value = r.meta.total
          orders.value = r.data

          return r
        })
        .finally(() => (isLoading.value = false))
    }

    onMounted(() => refreshOrders)

    return {
      total,
      headers,
      itemsPerPage,
      expanded,
      orders,
      isLoading,
      refreshOrders,
      formatDate,
      formatCurrency
    }
  }
})
</script>

<style>
th {
  font-weight: 700 !important;
}
</style>
