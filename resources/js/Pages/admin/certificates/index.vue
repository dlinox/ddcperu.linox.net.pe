<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
          
        </HeadingPage>

        <v-card>
            <DataTable :headers="headers" :items="items" with-action :url="url">
                <template v-slot:header="{ filter }">
                    <div class="pa-3">
                        <v-row justify="end">
                            <v-col cols="6">
                                <v-text-field
                                    v-model="filter.search"
                                    label="Buscar"
                                />
                            </v-col>
                        </v-row>
                    </div>
                </template>

                <template v-slot:item.days_remaining="{ item }">
                    <v-chip
                        :color="
                            item.days_remaining < 7
                                ? 'red'
                                : item.days_remaining < 15
                                ? 'orange'
                                : 'green'
                        "
                        label
                    >
                        {{ item.days_remaining }} día(s)
                    </v-chip>
                </template>

                <template v-slot:action="{ item }">
                    <v-btn
                        color="black"
                        variant="tonal"
                        @click="router.get(url + '/' + item.id + '/agency')"
                        class="me-1"
                        prepend-icon="mdi-certificate-outline"
                    >
                        Certificados
                    </v-btn>
                </template>
            </DataTable>
        </v-card>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import BtnDialog from "@/components/BtnDialog.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";
import DataTable from "@/components/DataTable.vue";
import { router } from "@inertiajs/core";
import create from "./create.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
});

const url = '/a/certificates'

</script>
