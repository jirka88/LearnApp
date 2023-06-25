<template>
    <AdminLayout>
        <v-container>
            <div class="mt-10 user-info d-grid align-center justify-center"
                 :class="{'gp-4 mobile-variant': $vuetify.display.smAndDown}">
                <v-avatar class="avatar" :class="{'margin-center': $vuetify.display.smAndDown}"  size="180">
                    <v-img
                    height="100%"
                    cover
                    src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
                /></v-avatar>
                <div class="info d-flex justify-center flex-column" :class="{'min-width-vw-65 align-center': $vuetify.display.smAndDown,
                                                                            'min-width-vw-85':$vuetify.display.xs } ">
                    <div class="text-h4">{{ usr.firstname }}</div>
                    <div class="text-subtitle-1">{{ usr.email }}</div>
                    <div class="text-subtitle-2">{{ this.$page.props.user.typeAccount }} účet</div>
                </div>
                <fieldset class="account pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
                    <legend align="center" class="text-h5">Informace o účtě:</legend>
                    <table class="w-100">
                        <tbody>
                        <tr>
                            <td class="w-50">Jméno:</td>
                            <td class="w-50">
                                <v-text-field v-model="form.firstname" variant="outlined"></v-text-field>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50">Email:</td>
                            <td class="w-50">
                                <v-text-field v-model="form.email" :disabled="true"
                                              variant="outlined"></v-text-field>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50">Role:</td>
                            <td class="w-50">
                                <v-select
                                    v-model="form.role"
                                    :items="items"
                                    :disabled="usr.role_id !== 4 ? false : true"
                                    item-title="state"
                                    item-value="id"
                                    label="Select"
                                    persistent-hint
                                    return-object
                                    single-line
                                    variant="outlined"
                                ></v-select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <v-btn type="submit"
                           color="blue"
                           class="btn d-flex"
                           :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        Upravit!
                    </v-btn>
                </fieldset>
                <fieldset class="password pa-8"  :class="{'w-100': $vuetify.display.smAndDown}">
                    <legend align="center" class="text-h5">Resetování hesla:</legend>
                    <v-form ref="formResetPassword" @submit.prevent="changePassword">
                        <v-text-field v-model="formPassword.oldPassword" label="Staré heslo" :rules="[rules.required]"
                                      variant="outlined"></v-text-field>
                        <v-text-field v-model="formPassword.newPassword" label="Nové heslo"
                                      :rules="[rules.required, rules.password]" variant="outlined"
                                      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                      :type="show1 ? 'text' : 'password'"
                                      @click:append="show1 = !show1"
                        >

                        </v-text-field>
                        <v-text-field v-model="formPassword.againNewPassword" label="Nové heslo znova"
                                      :rules="[rules.required, rules.passwordConfirm]"
                                      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                      :type="show2 ? 'text' : 'password'"
                                      @click:append="show2 = !show2"
                                      variant="outlined"></v-text-field>
                        <p class="text-center text-red">{{ props.errors.msg }}</p>
                        <p v-if="$page.props.flash.messagePasswordReset" class="text-center text-green">
                            {{ $page.props.flash.messagePasswordReset }}</p>
                        <v-btn type="submit"
                               color="blue"
                               class="btn d-flex"
                               :class="{'w-100': $vuetify.display.smAndDown}"
                        >
                            Změnit heslo!
                        </v-btn>
                    </v-form>
                </fieldset>
            </div>
        </v-container>
    </AdminLayout>

</template>
<script>
</script>
<script setup>
import {markRaw, ref} from "vue";
import AdminLayout from "../layouts/DashboardLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps({'usr': Object, 'roles': Array, errors: Object});
const show1 = ref('');
const show2 = ref('');
const items = markRaw(
    props.roles.map(role => ({
        state: role.role, id: role.id
    })));

const form = useForm({
    firstname: props.usr.firstname,
    email: props.usr.email,
    role: {state: props.usr.roles.role, id: props.usr.roles.id},
})

const formPassword = useForm({
    oldPassword: '',
    newPassword: '',
    againNewPassword: '',
})

const changePassword = async (e) => {
    e.preventDefault();
    formPassword.post('/dashboard/user/changePassword', {
        onSuccess: () => {
            formPassword.reset();
        }
    });
}

const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    password: v => {
        const missingElements = [];
        if (v.length < 8) {
            missingElements.push('více než 8 znaků');
        }
        if (!/(?=.*\d)/.test(v)) {
            missingElements.push('číslici');
        }
        if (!/[!@#$%^&*]/.test(v)) {
            missingElements.push('speciální znak');
        }
        if (!/(?=.*[a-z])/.test(v)) {
            missingElements.push('malé písmeno');
        }
        if (!/(?=.*[A-Z])/.test(v)) {
            missingElements.push('velké písmeno');
        }
        if (missingElements.length > 0) {
            return `Heslo musí obsahovat ${missingElements.join(', ')}!`;
        }
    },
    passwordConfirm: v => v === formPassword.newPassword || "Hesla se neshodují!"
}
</script>
<style scoped lang="scss">
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
        max-width: 35em;
    }

    .password {
        grid-area: password;
        width: 35em;
    }
}

.user-info {
    gap: 4em;
    min-width: 320px;
}

.gp-4 {
    gap: 2em;
}

.mobile-variant {
    grid-template-areas: 'avatar'
                        'info'
                            'account'
                            'password' !important;
}


fieldset {
    border-radius: 1em;
    box-shadow: 1em 1em gray;
}

.v-btn {
    margin: 1em auto;
}

:deep(.v-messages__message) {
    padding-bottom: 1.2em !important;
    transition: 0.3s;
}
</style>
