import { mount } from '@vue/test-utils'
import VisitorSearch from '@/components/search/VisitorSearch.vue'

test('shows search button', () => {
    const wrapper = mount(VisitorSearch)
    expect(wrapper.text()).toContain('Buscar')
})
