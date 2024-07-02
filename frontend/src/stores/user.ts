import { defineStore } from 'pinia'

interface User {
  uuid: string
  first_name: string
  last_name: string
  email: string
  address: string
  phone_number: string
  is_marketing: boolean
  avatar: Record<string, string>
  last_login_at: string
}

export const useUserStore = defineStore('user', {
  state: (): { user: User | null } => ({
    user: null
  }),

  getters: {
    isLoggedIn: (state) => !!state.user
  },

  actions: {
    setUser(newUser: User | null) {
      this.user = newUser
    }
  }
})
