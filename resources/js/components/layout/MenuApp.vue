<template>
    <v-list v-model:opened="menuOpen" nav density="compact" color="primary">
        <v-list-subheader> Menu </v-list-subheader>

        <template v-for="(menu, index) in menu_" :key="index">
            <template v-if="menu.group == null">
                <v-list-item
                    :prepend-icon="menu.icon"
                    :title="menu.title"
                    :class="
                        router.page.url == menu.to
                            ? 'v-list-item--active text-primary rounded-lg'
                            : ''
                    "
                    @click="router.get(`${menu.to}`)"
                />
            </template>
            <template v-else>
                <v-list-group :value="menu.value">
                    <template v-slot:activator="{ props }">
                        <v-list-item
                            v-bind="props"
                            :prepend-icon="menu.icon"
                            :title="menu.title"
                            color="black"
                        ></v-list-item>
                    </template>
                    <template v-for="submenu in menu.group">
                        <v-list-item
                            :title="submenu.title"
                            :value="submenu.value"
                            :class="
                                router.page.url == submenu.to
                                    ? 'v-list-item--active text-primary rounded-lg'
                                    : ''
                            "
                            @click="
                                menuOpen = submenu.value;
                                router.get(`${submenu.to}`);
                            "
                        >
                            <!-- <template #prepend>
                                    <v-icon class="ms-0 me-2" size="x-large">
                                        mdi-circle-small
                                    </v-icon>
                                </template> -->
                        </v-list-item>
                    </template>
                </v-list-group>
            </template>
        </template>
    </v-list>

</template>

<script setup>
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
const props = defineProps({
    userRole: String,
    userArea: [String, Number],
});

const menuOpen = ref([router.page.url.split("/")[2]]);


const menu_ = computed(() => usePage().props?.menu);


</script>

