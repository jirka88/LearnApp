import { defineStore } from 'pinia'

interface DialogDeleteInterface {
    dialog: boolean
    object: Object
    url: string
}

export const useDialogDeleteStore = defineStore('dialogDeleteStore', {
    state: (): DialogDeleteInterface => {
        return { dialog: false, object: {}, url: '' }
    },
    actions: {
        setDialog(dialog: boolean, object: Object, url: String): void {
            this.dialog = dialog
            this.object = object
            this.url = url
        },
        setOnlyDialog(dialog: boolean): void {
            this.dialog = dialog
        },
        setDefault(): void {
            this.dialog = false
            this.object = {}
            this.url = ''
        }
    }
})
