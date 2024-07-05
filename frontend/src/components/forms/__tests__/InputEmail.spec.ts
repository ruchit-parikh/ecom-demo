import { describe, it, expect } from 'vitest'
import InputEmail from '@/components/forms/InputEmail.vue'
import { VTextField } from 'vuetify/components'
import { useVuetifyMount } from '@/components/__tests__/composables/VuetifyMount'

describe('InputEmail.vue', () => {
  const mountFunction = useVuetifyMount(InputEmail).mountFunction

  describe('email validation', () => {
    it('should initialize without error', () => {
      const wrapper = mountFunction({
        props: { name: 'email' }
      })

      const textField = wrapper.findComponent(VTextField)

      expect(textField.html()).not.toContain('E-mail must be valid')
    })

    it('should show error when entered email is invalid', async () => {
      const wrapper = mountFunction({
        props: { name: 'email' }
      })

      const textField = wrapper.findComponent(VTextField)
      await textField.setValue('invalid-email')
      await wrapper.vm.$nextTick()

      expect(textField.html()).toContain('E-mail must be valid')
    })

    it('should show no errors when email is valid', async () => {
      const wrapper = mountFunction({
        props: { name: 'email' }
      })

      const textField = wrapper.findComponent(VTextField)
      await textField.setValue('test@example.com')
      await wrapper.vm.$nextTick()

      expect(textField.html()).not.toContain('E-mail must be valid')
    })
  })

  describe('custom validation rule', () => {
    const customRule = (v: string) => v.length >= 15 || 'Too short'

    it('should initialize without error', () => {
      const wrapper = mountFunction({
        props: {
          name: 'email',
          validate: [customRule]
        }
      })

      const textField = wrapper.findComponent(VTextField)

      expect(textField.html()).not.toContain('Too short')
    })

    it('should show custom validation error message when value is incorrect', async () => {
      const wrapper = mountFunction({
        props: {
          name: 'email',
          validate: [customRule]
        }
      })

      const textField = wrapper.findComponent(VTextField)
      await textField.setValue('abc@gm.com')
      await wrapper.vm.$nextTick()

      expect(textField.html()).toContain('Too short')
    })

    it('should show no errors when custom validation and value are correct', async () => {
      const wrapper = mountFunction({
        props: {
          name: 'email',
          validate: [customRule]
        }
      })

      const textField = wrapper.findComponent(VTextField)
      await textField.setValue('abcdefghit@gmail.comaaaa')
      await wrapper.vm.$nextTick()

      expect(textField.html()).not.toContain('Too short')
    })
  })
})
