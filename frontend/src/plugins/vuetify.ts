import { createVuetify } from 'vuetify'
import {
  VApp,
  VMain,
  VAppBar,
  VBtn,
  VCard,
  VContainer,
  VRow,
  VCol,
  VDialog,
  VForm,
  VProgressLinear,
  VTextField,
  VIcon,
  VAvatar,
  VImg,
  VDataTableServer,
  VToolbar,
  VToolbarTitle,
  VDivider,
  VSpacer
} from 'vuetify/components'
import { Ripple } from 'vuetify/directives'
import 'vuetify/styles'

export default createVuetify({
  theme: {
    themes: {
      light: {
        colors: {
          primary: '#4ec690',
          secondary: '#FFFFFF'
        },
        variables: {
          'theme-on-primary': '#FFFFFF',
          'btn-height': '50px'
        }
      }
    }
  },
  components: {
    VApp,
    VMain,
    VAppBar,
    VBtn,
    VCard,
    VContainer,
    VRow,
    VCol,
    VDialog,
    VForm,
    VProgressLinear,
    VTextField,
    VIcon,
    VAvatar,
    VImg,
    VDataTableServer,
    VToolbar,
    VToolbarTitle,
    VDivider,
    VSpacer
  },
  directives: {
    Ripple
  }
})
