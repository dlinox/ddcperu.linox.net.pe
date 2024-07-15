<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
            <template #actions>
                <BtnDialog title="Asignar certificados" width="500px">
                    <template v-slot:activator="{ dialog }">
                        <v-btn
                            @click="dialog"
                            prepend-icon="mdi-plus"
                            variant="flat"
                        >
                            Asignar
                        </v-btn>
                    </template>
                    <template v-slot:content="{ dialog }">
                        <create
                            :form-structure="formStructure"
                            @on-cancel="dialog"
                            :url="url"
                        />
                    </template>
                </BtnDialog>
            </template>
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
                        icon="mdi-eye"
                        variant="outlined"
                        density="comfortable"
                        class="me-1"
                        color="black"
                        @click="router.get(url + '/' + item.id + '/details')"
                    >
                    </v-btn>

                    <BtnDialog title="Editar" width="500px">
                        <template v-slot:activator="{ dialog }">
                            <v-btn
                                color="info"
                                icon
                                variant="tonal"
                                density="comfortable"
                                @click="dialog"
                            >
                                <v-icon size="18" icon="mdi-pencil"></v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:content="{ dialog }">
                            <create
                                :form-structure="
                                    formStructure.filter(
                                        (field) => field.key !== 'password'
                                    )
                                "
                                @on-cancel="dialog"
                                :form-data="item"
                                :edit="true"
                                :url="url + '/' + item[`${primaryKey}`]"
                            />
                        </template>
                    </BtnDialog>

                    <v-btn
                        icon
                        variant="tonal"
                        density="comfortable"
                        class="ml-1"
                        color="red"
                    >
                        <DialogConfirm
                            @onConfirm="
                                () =>
                                    router.delete(
                                        url + '/' + item[`${primaryKey}`]
                                    )
                            "
                        />
                        <v-icon size="18" icon="mdi-delete-empty"></v-icon>
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
    agency: Object,
    items: Object,
    headers: Object,
    filters: Object,
});

const url = `/a/certificates/${props.agency.id}/agency`;
const primaryKey = "id";

const formStructure = [
    {
        key: "range_start",
        type: "text",
        label: "Rango inicial",
        colMd: 6,
    },
    {
        key: "range_end",
        type: "text",
        label: "Rango final",
        colMd: 6,
    },
    {
        key: "quantity",
        type: "number",
        label: "Cantidad",
        colMd: 12,
        default: 0,
    },
];
</script>
