import {ref} from "vue";

export const isActiveToast  = ref(false);
export const statusToast = ref(true);
export const toastShow = (val) :void =>{
    isActiveToast.value = val;
}
export const toastStatus = (val) : void =>{
    statusToast.value = val;
}

