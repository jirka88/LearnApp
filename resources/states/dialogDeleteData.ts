import { defineStore } from 'pinia'

interface DialogDeleteInterface {
    dialog: boolean
    object: Object
    url: string
    urlParams: Object
    link: boolean
}

export const useDialogDeleteStore = defineStore('dialogDeleteStore', {
    state: (): DialogDeleteInterface => {
        return { dialog: false, object: {}, url: '', urlParams: {}, link: true }
    },
    actions: {
        setDialog(
            dialog: boolean,
            object: Object,
            url: string,
            link: boolean = true
        ): void {
            this.dialog = dialog
            this.object = object
            this.url = url
            this.link = link
        },
        setDialogWithUrlParams(
            dialog: boolean,
            url: string,
            urlParams: object,
            link: boolean = true
        ): void {
            this.dialog = dialog
            this.url = url
            this.urlParams = urlParams
            this.link = link
        },
        setOnlyDialog(dialog: boolean): void {
            this.dialog = dialog
        },
        setDefault(): void {
            this.dialog = false
            this.object = {}
            this.url = ''
            this.urlParams = {}
            this.link = true
        }
    }
})
