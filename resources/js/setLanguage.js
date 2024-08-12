import { onBeforeMount } from 'vue'
import { loadLanguageAsync } from 'laravel-vue-i18n'

function setLanguage() {
    onBeforeMount(() => {
        if (localStorage.getItem('lang') !== null) {
            loadLanguageAsync(localStorage.getItem('lang'))
        }
    })
}
export default setLanguage
