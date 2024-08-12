import { ref } from 'vue'

export const isActiveToast = ref(false)
export const statusToast = ref(true)
export const toastShow = (val: boolean): void => {
    isActiveToast.value = val
}
export const toastStatus = (val: boolean): void => {
    statusToast.value = val
}
