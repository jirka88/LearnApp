<template>
    <fieldset
        class="menus pa-8"
        :class="{ 'w-100': $vuetify.display.smAndDown }"
    >
        <legend align="center" class="text-h5">Nastavení sdílení:</legend>
        <v-form @submit.prevent="changeShare">
            <div class="text-body-1 pb-8">
                {{ $t('userAccount.shareInfo') }}
                <span class="font-weight-bold">{{ $t('global.no') }}.</span>
            </div>
            <v-select
                v-model="form.share"
                :items="items"
                item-title="state"
                item-value="id"
                hint="Umožnit přijímat nasdílení."
                persistent-hint
                return-object
                single-line
                variant="outlined"
            ></v-select>
            <v-btn
                type="submit"
                color="blue"
                class="btn d-flex"
                :class="{ 'w-100': $vuetify.display.smAndDown }"
                :disabled="disabledBtn == form.share.id"
            >
                {{ $t('global.edit') }}
            </v-btn>
        </v-form>
    </fieldset>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'

const props = defineProps({ usr: Object, errors: Object })
const disabledBtn = ref(props.usr.canShare === true ? 1 : 0)
const items = [
    {
        state: 'ANO',
        id: 1
    },
    { state: 'NE', id: 0 }
]
const form = useForm({
    share:
        props.usr.canShare == 1
            ? { state: 'ANO', id: 1 }
            : { state: 'NE', id: 0 }
})

const changeShare = () => {
    form.put(route('user.share'), {
        onSuccess: () => {
            disabledBtn.value = form.share.id
        }
    })
}
</script>

<style scoped lang="scss">
.v-btn {
    padding: 1.6em;
    margin: 0 auto;
}
</style>
