<template>
    <AdminLayout>
        <v-container>
            <div class="mt-10 user-info d-grid align-center justify-center">
                <v-avatar class="avatar" color="surface-variant" size="180"></v-avatar>
                <div class="info d-flex justify-center flex-column">
                    <div class="text-h4">{{ usr.firstname }}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ usr.created_at }}</div>
                </div>
                <div class="account">
                    <div class="text-h5">Informace o účtě:</div>
                    <table>
                        <tbody class="w-100">
                        <tr>
                            <td>Jméno:</td>
                            <td><v-text-field v-model="form.firstname" label="Label" variant="outlined"></v-text-field></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><v-text-field label="Label" :disabled="true" variant="outlined"></v-text-field></td>
                        </tr>
                        <tr>
                            <td>Role:</td>
                            <td> <v-select
                                v-model="select"
                                :hint="`${select.state}, ${select.abbr}`"
                                :items="items"
                                :disabled="true"
                                item-title="state"
                                item-value="abbr"
                                label="Select"
                                persistent-hint
                                return-object
                                single-line
                            ></v-select></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="password">
                <p>dd</p>
                </div>
            </div>

        </v-container>
    </AdminLayout>

</template>
<script setup>
import { markRaw} from "vue";
import AdminLayout from "./../layouts/AdminLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
defineProps({usr: Object})

const select = markRaw({ state: 'Florida', abbr: 'FL' });
const items = markRaw([
            { state: 'Florida', abbr: 'FL' },
            { state: 'Georgia', abbr: 'GA' },
            { state: 'Nebraska', abbr: 'NE' },
            { state: 'California', abbr: 'CA' },
            { state: 'New York', abbr: 'NY' },
        ]);
const form = useForm({
    firstname: '',
})
</script>
<style lang="scss">
.d-grid {
    display: grid;
    grid-template-areas: 'avatar info'
                            'account account'
                            'password password';

    .account {
        grid-area: account;
    }

    .avatar {
        grid-area: avatar;
    }

    .info {
        grid-area: info;
    }
    .password {
        grid-area: password;
    }
}

.user-info {
    gap: 8em;
}
</style>
