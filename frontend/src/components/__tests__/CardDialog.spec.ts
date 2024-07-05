import { describe, it, expect, beforeEach } from 'vitest'
import CardDialog from '@/components/CardDialog.vue'
import { useVuetifyMount } from '@/components/__tests__/composables/VuetifyMount'
import type { VueWrapper } from '@vue/test-utils'

describe('CardDialog.vue', () => {
  const mountFunction = useVuetifyMount(CardDialog).mountFunction
  let wrapper: VueWrapper<any>

  beforeEach(() => {
    wrapper = mountFunction()
  })

  it('should start with dialog closed', () => {
    expect(wrapper.vm.isOpened).toBe(false)
  })

  it('should open dialog when calling openDialog', async () => {
    await wrapper.vm.openDialog()

    expect(wrapper.vm.isOpened).toBe(true)
  })

  it('should close dialog when calling closeDialog', async () => {
    wrapper.vm.isOpened = true

    await wrapper.vm.closeDialog()
    expect(wrapper.vm.isOpened).toBe(false)
  })
})
