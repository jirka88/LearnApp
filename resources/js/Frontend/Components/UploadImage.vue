<script setup>
import {ref} from "vue";
import { CircleStencil, Cropper, Preview} from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

defineProps({isActive: Boolean, errors: Object})
const emit = defineEmits(['close']);
const image = ref(null);
const preview = ref("");
const uploading = ref(false);
const resultImage = ref({
    coordinates: null,
    image: null
});
const form = useForm( {
    savedImage: null,
})
const closed = () => {
    emit('close');
}
const onFileChange = (file) => {
    if (!file) {
        return;
    }
    createImage(file);
}
const createImage = (file) => {
    const filev = file.target.files[0];
    if(!filev) {
        preview.value = null;
        return;
    }
    const reader = new FileReader();
    reader.readAsDataURL(filev);
    reader.onload = e => {
        preview.value = e.target.result;
    };
}
const change = ({coordinates, canvas, image}) => {
    resultImage.value.coordinates = coordinates;
    resultImage.value.image = image;
    form.savedImage = canvas.toDataURL();
}
const uploadImage = () => {
    uploading.value = true;
        form.post(route('user.profilePicture'), {
            onSuccess: () => {
                resetInput();
                uploading.value = false;
                emit('close');
            },
            });
}
const resetInput = () => {
    preview.value = null;
    image.value = null;
    resultImage.value.image = null;
    resultImage.value.coordinates = null;
}
const deleteImage = (id) => {
    Inertia.delete(route('user.deleteProfilePicture', {user: id}), {
        onSuccess: () => {
            closed();
        },
    });
}

</script>
<template>
    <v-dialog
        transition="dialog-bottom-transition"
    >
        <v-card
            class="d-flex ga-2 rounded-xl py-2">
            <v-card-actions class="justify-end">
                <v-btn
                    @click="closed"
                    variant="plain"
                    icon="mdi-window-close"
                >
                </v-btn>
            </v-card-actions>
            <v-toolbar
                color="primary"
                class="text-center"
                :title="$t('userAccount.upload_profile_image')"
            ></v-toolbar>
            <v-form @submit.prevent="uploadImage" class="d-flex ga-2 flex-column" enctype="multipart/form-data">
            <v-file-input v-model="image" :show-size="1000" @change="onFileChange" variant="underlined"
                          @click:clear="resetInput"></v-file-input>
            <cropper
                class="cropper"
                v-if="preview"
                :src="preview"
                :stencil-component="CircleStencil"
                :debounce="false"
                :max-canvas-size="2048*2048"
                :stencil-props="{
                handlers: {},
                resizable: false,
                }"
                        :stencil-size="{
                width: 280,
                height: 280
                }"
                @change="change"/>
            <div class="d-flex justify-center align-center">
            <Preview
                class="preview"
                v-if="resultImage.image"
                :width="120"
                :height="120"
                :image="resultImage.image"
                :coordinates="resultImage.coordinates"
            />
                {{errors}}
            </div>
            <v-btn
                color="primary"
                type="submit"
                :disabled="!preview || uploading"
            >{{$t('userAccount.upload_image')}}
            </v-btn>
                <v-btn
                    v-if="$page.props.user.image"
                color="red"
                @click="deleteImage($page.props.user.id)">
                    {{$t('global.delete')}}!
                </v-btn>
            </v-form>
        </v-card>
    </v-dialog>

</template>

<style scoped lang="scss">
.cropper {
    min-height: 4em !important;
    width: 100%;
    max-height: 25em !important;
}
.preview {
    border-radius: 50%;
}
.v-card {
    padding: 0.6em 1em !important;
}
</style>
