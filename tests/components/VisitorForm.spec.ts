import { mount } from '@vue/test-utils'
import VisitorForm from '@/components/forms/VisitorForm.vue'
import { sampleVisitor } from '../fixtures/visitor'

test('renders visitor headphones count and allows increment/decrement', async () => {
  const wrapper = mount(VisitorForm, {
    props: {
      visitor: sampleVisitor,
      isEdit: true
    }
  })

  const label = wrapper.find('.text-h4')
  expect(label.text()).toContain('1')

  const plus = wrapper.find('button:has(.mdi-plus)')
  const minus = wrapper.find('button:has(.mdi-minus)')

  await plus.trigger('click')
  expect(wrapper.find('.text-h4').text()).toContain('2')

  await minus.trigger('click')
  await minus.trigger('click')
  expect(wrapper.find('.text-h4').text()).toContain('0')
})