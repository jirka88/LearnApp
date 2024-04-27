import {ref} from "vue";

export const isActiveToast = ref(false);
export const statusToast = ref(true);
export const toastShow = (val) =>{
    isActiveToast.value = val;
}
export const toastStatus = (val) =>{
    statusToast.value = val;
}

