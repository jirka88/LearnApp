<script setup>
defineProps({ subject: Object })
const model = defineModel('dialog')
</script>

<template>
    <v-dialog v-model="model" persistent width="auto">
        <v-card>
            <v-card-title class="text-h5 text-center">
                Opravdu si přejete smazat předmět
                <strong>{{ subject.subjectName }}</strong>
            </v-card-title>
            <v-card-text class="text-center"
                >Tato akce je nenávratná. S mazáním předmětu dojde k smázání i
                všech kapitol patřící pod předmět!</v-card-text
            >
            <v-card-actions class="margin-center">
                <v-spacer></v-spacer>
                <v-btn
                    class="bg-white"
                    @click="model = false"
                    size="x-large"
                    variant="outlined"
                >
                    {{ $t('global.close') }}
                </v-btn>
                <slot> </slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped lang="scss"></style>
