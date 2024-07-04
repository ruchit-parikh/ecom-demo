<template>
  <CardDialog ref="dialog">
    <ActionForm :action="login" :inputs="{ email: email, password: password }">
      <div class="bg-primary px-2 py-3 rounded-circle w-25 mx-auto mb-5 text-center">
        <img alt="Logo" class="img-fluid" src="@/assets/logo.svg" />
        <img alt="Logo Text" class="img-fluid" src="@/assets/logo-text.svg" />
      </div>

      <h2 class="text-center font-weight-medium mb-5">Login</h2>

      <InputEmail
        class="mb-3"
        :validate="[rules.required]"
        placeholder="Email"
        name="email"
        ref="email"
      ></InputEmail>

      <InputPassword
        :validate="[rules.required]"
        placeholder="Password"
        name="password"
        ref="password"
      ></InputPassword>

      <InputCheckbox placeholder="Remember me" name="remember_me" ref="rememberMe"></InputCheckbox>

      <VBtn
        :disalbed="loading"
        width="100%"
        height="35"
        color="primary"
        :loading="loading"
        type="submit"
        class="mb-6"
        >Login</VBtn
      >

      <div v-if="error !== ''" class="text-red text-center mb-5">
        <VIcon class="mi-error"></VIcon>{{ error }}
      </div>

      <VRow>
        <VCol>
          <div class="d-flex justify-space-between">
            <RouterLink class="text-decoration-none" to="#">Forgot password?</RouterLink>
            <RouterLink class="text-decoration-none" to="#"
              >Dont have an account? Sign Up</RouterLink
            >
          </div>
        </VCol>
      </VRow>
    </ActionForm>
  </CardDialog>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import CardDialog from '@/components/CardDialog.vue'
import eventBus from '@/plugins/eventBus'
import auth from '@/services/auth'
import ActionForm from '@/components/forms/ActionForm.vue'
import InputPassword from '@/components/forms/InputPassword.vue'
import InputEmail from '@/components/forms/InputEmail.vue'
import * as rules from '@/utils/rules'
import InputCheckbox from '@/components/forms/InputCheckbox.vue'
import { RouterLink } from 'vue-router'
import { VRow, VCol, VBtn, VIcon } from 'vuetify/components'

export default defineComponent({
  name: 'LoginModal',
  components: {
    InputCheckbox,
    InputEmail,
    InputPassword,
    ActionForm,
    CardDialog,
    RouterLink,
    VRow,
    VCol,
    VBtn,
    VIcon
  },
  setup() {
    const dialog = ref<InstanceType<typeof CardDialog> | null>(null)
    const email = ref<InstanceType<typeof InputEmail> | null>(null)
    const password = ref<InstanceType<typeof InputPassword> | null>(null)
    const rememberMe = ref<InstanceType<typeof InputCheckbox> | null>(null)
    const loading = ref(false)
    const error = ref('')

    /**
     * @return {Promise<void>}
     */
    const login = (): Promise<void> => {
      if (!email.value || !password.value) {
        return (async () => {})()
      }

      loading.value = true

      return auth
        .login(email.value.value, password.value.value, rememberMe.value ? rememberMe.value.value : false)
        .then(() => {
          setTimeout(() => {
            if (dialog.value) {
              dialog.value.closeDialog()
            }
          }, 2000)
        })
        .catch((err) => {
          error.value = err.message

          return err
        })
        .finally(() => {
          loading.value = false
        })
    }

    eventBus.on('user-unauthorized', () => {
      if (dialog.value) {
        dialog.value.openDialog()
      }
    })

    return {
      loading,
      login,
      email,
      password,
      dialog,
      rules,
      error,
      rememberMe
    }
  }
})
</script>
