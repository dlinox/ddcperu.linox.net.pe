<template>
    <v-toolbar color="primary" elevation="0" class="border-0">
        <!-- <v-btn icon class="hidden-xs-only" @click="router.visit(-1)">
            <v-icon>mdi-arrow-left</v-icon>
        </v-btn> -->

        <v-list lines="one" class="bg-transparent">
            <v-list-item>
                <v-list-item-subtitle> {{ subtitle }} </v-list-item-subtitle>
                <v-list-item-title>
                    <strong>
                        {{ title.toUpperCase() }}
                    </strong>
                </v-list-item-title>
            </v-list-item>
        </v-list>
        <v-spacer></v-spacer>
        <slot name="actions"> </slot>
    </v-toolbar>
</template>
<script setup>
const props = defineProps({
    title: {
        type: String,
        default: "Titulo Page",
    },
    subtitle: {
        type: String,
    },
});
</script>
