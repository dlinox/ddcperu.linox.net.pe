<template>
    <Head :title="title + ' | DDC - ADMIN'" />
    <v-app app class="w-100 h-screen d-flex justify-center align-center">
        <slot />
    </v-app>

    <v-snackbar v-model="snackbarError" color="error">
        {{ snackbarMessage }}
        <template v-slot:actions>
            <v-btn
                color="dark"
                variant="text"
                @click="snackbarError = false"
                icon="mdi-close"
            ></v-btn>
        </template>
    </v-snackbar>
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

defineProps({
    title: String,
});
const snackbarError = ref(false);
const snackbarMessage = ref("");

const error = computed(() => usePage().props?.errors);

watch(
    () => error.value,
    (newValue) => {
        if (newValue.error) {
            snackbarError.value = true;
            snackbarMessage.value = newValue.error;
        } else {
            snackbarError.value = false;
        }
    }
);
</script>
