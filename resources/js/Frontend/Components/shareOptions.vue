
<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">Nastavení sdílení:</legend>
        <v-form @submit.prevent="changeShare">
            <div class="text-body-1 pb-8">Umožnit přijímat od jiných uživatelů nasdílení jejich sekcí. Pokud nechcete přijímat od žádného uživatele sekci zaškrtněntě možnost<strong> NE.</strong></div>
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
            <p class="text-center text-red">{{errors.share}}</p>
            <v-btn type="submit"
                   color="blue"
                   class="btn d-flex"
                   :class="{'w-100': $vuetify.display.smAndDown}"
            >
                Upravit!
            </v-btn>
            <p v-if="$page.props.flash.messageShare" class="text-center text-green pt-4">
                {{ $page.props.flash.messageShare}}</p>
        </v-form>
    </fieldset>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
const props = defineProps({usr: Object, errors: Object});
const items = [{
    state: 'ANO', id: 1
}, {state: 'NE', id: 0}]
const form = useForm({
    share: props.usr.canShare == 1 ? {state: 'ANO', id: 1} : {state: 'NE', id: 0},
});

const changeShare = () => {
    form.post(route('user.share'), {onSuccess: () => {
    }
    });
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 0 auto;
}
</style>
