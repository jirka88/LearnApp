<template>
        <component :is="SubjectManagerLayout">
            <div class="create-subject d-flex justify-center align-center"
                 :class="{'w-85': $vuetify.display.smAndDown,
                            'w-95': $vuetify.display.xs}">
                <v-form class="pa-6 bg-white" @submit.prevent="createSubject" >
                    <p class="text-h1 font-weight-bold">Vytvořit {{$page.props.user.typeAccount == 'Osobní' ? 'sekci' : 'předmět'}}</p>
                    <v-text-field
                        v-model="form.name"
                        prepend-inner-icon="mdi-email"
                        variant="outlined"
                        autofocus
                        :label="$t('global.name')"
                        :rules="[rules.required, rules.minName, rules.maxName]"
                    ></v-text-field>
                    <v-select
                        variant="outlined"
                        v-model="form.icon"
                        :items="icons"
                        item-title="iconName"
                        label="Ikona">
                        <template v-slot:selection="{ item, index }">
                            <div class="d-flex justify-content-center align-items-center ga-2">
                                <v-icon :icon="item.title"></v-icon>
                                {{ item.title }}
                            </div>
                        </template>
                        <template v-slot:item="{ item, props }">
                            <v-list-item v-bind="props">
                                <template #title>
                                    <div class="d-flex justify-content-center align-items-center ga-2">
                                        <v-icon :icon="item.title"></v-icon>
                                        {{ item.title }}
                                    </div>
                                </template>
                            </v-list-item>
                        </template>
                    </v-select>
                    <v-btn
                        type="submit"
                        color="blue"
                        class="d-flex justify-center margin-center"
                        :disabled="creating"
                        :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        {{$t('global.created')}}!
                    </v-btn>
                    <p v-if="form.errors.msg" class="text-center text-red pt-2">{{form.errors.msg}}</p>
                </v-form>
            </div>
        </component>
</template>
<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import icons from "../../../itemsIcons";
import SubjectManagerLayout from "@/Frontend/layouts/SubjectManagerLayout.vue";
import {ref} from "vue";

const props = defineProps({usr: Object, url: String, errors: Object});
const creating = ref(false);
const form = useForm({
    name: '',
    icon: 'mdi-text-long'
})

const createSubject = async() =>{
    creating.value = true;
    if(props.url === undefined) {
        form.post("/dashboard/manager/subject/", {
            onSuccess: () => {
                form.reset();
            },
            onError: () => {
                creating.value = false;
            }
        });
        return;
    }
    form.post((props.url),{
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            creating.value = false;
        }
    });
}
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    minName: v => v.length > 3 || 'Předmět musí mít delší název!',
    maxName: v => v.length < 25 ||'Předmět nesmí být delší!'
 }
</script>
<style lang="scss">
.create-subject {
    width: 600px;
    p:first-child {
        font-size: 1.8em !important;
        white-space: nowrap;
        overflow: auto;
    }
    .v-form {
        border-radius: 1em;
        border: 1px solid gray;
        box-shadow: 1em 1em gray;
        width: inherit;
        .v-messages__message  {
            padding-bottom: 1.2em !important;
            transition: 0.3s;
        }
    }
}

</style>
