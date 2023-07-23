<template>
        <SubjectManagerLayout>
            <div class="create-subject d-flex justify-center align-center"
                 :class="{'w-85': $vuetify.display.smAndDown,
                            'w-95': $vuetify.display.xs}">
                <v-form class="pa-6 bg-white" @submit.prevent="createSubject" >
                    <p class="text-h1 font-weight-bold">Vytvořit předmět</p>
                    <v-text-field
                        v-model="form.name"
                        prepend-inner-icon="mdi-email"
                        variant="outlined"
                        label="Název"
                        :rules="[rules.required, rules.minName, rules.maxName]"
                    ></v-text-field>
                    <v-select
                        variant="outlined"
                        v-model="form.icon"
                        :items="icons"
                        item-title="iconName"
                        label="Ikona">
                    </v-select>

                    <v-btn
                        type="submit"
                        color="blue"
                        class="d-flex justify-center margin-center"
                        :disabled="creating"
                        :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        vytvořit!
                    </v-btn>
                </v-form>
            </div>
        </SubjectManagerLayout>
</template>
<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import icons from "../../../itemsIcons";
import SubjectManagerLayout from "@/Frontend/layouts/SubjectManagerLayout.vue";
import {ref} from "vue";

const props = defineProps({usr: Object, url: String});
const creating = ref(false);
const form = useForm({
    name: '',
    icon: {iconName: 'mdi-text-long'}
})

const createSubject = async() =>{
    creating.value= true;
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
    p {
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
