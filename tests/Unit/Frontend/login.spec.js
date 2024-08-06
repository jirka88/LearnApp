import { shallowMount } from '@vue/test-utils'
import LoginForm from '@/Frontend/Components/Authentication/LoginForm.vue'
import { mount } from '@vue/test-utils'

describe('Login', () => {
    it('Inputs check', async () => {
        const wrapper = shallowMount(LoginForm)
        const emailInput = wrapper.find('#email')
        await emailInput.setValue('pokus@seznam.cz')

        expect(emailInput.element.value).toBe('pokus@seznam.cz')
    })
})
