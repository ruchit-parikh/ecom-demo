import { describe, it, expect } from 'vitest'
import { email, required } from '@/utils/rules'

describe('rules', () => {
  describe('email', () => {
    it('should return true for a valid email', () => {
      expect(email('test@example.com')).toBe(true)
      expect(email('user.name@domain.co')).toBe(true)
    })

    it('should return an error message for an invalid email', () => {
      expect(email('invalid-email')).toBe('E-mail must be valid')
      expect(email('test@.com')).toBe('E-mail must be valid')
      expect(email('@example.com')).toBe('E-mail must be valid')
      expect(email('')).toBe('E-mail must be valid')
    })
  })

  describe('required', () => {
    it('should return true if the input is not empty', () => {
      expect(required('hello')).toBe(true)
    })

    it('should return an error message if the input is empty', () => {
      expect(required('')).toBe('This field is required')
      expect(required(null)).toBe('This field is required')
      expect(required(undefined)).toBe('This field is required')
    })
  })
})
