<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
            <template #actions>
                <BtnDialog title="Registrar" width="600px">
                    <template v-slot:activator="{ dialog }">
                        <v-btn
                            @click="dialog"
                            prepend-icon="mdi-plus"
                            variant="flat"
                        >
                            Nuevo
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

        <v-card flat rounded="0">
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
                        {{
                            item.days_remaining > 0
                                ? item.days_remaining + "día(s)"
                                : "Vencido"
                        }}
                    </v-chip>
                </template>

                <template v-slot:action="{ item }">
                    <BtnDialog title="Editar" width="600px">
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
                                :form-structure="formStructure"
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
import { id } from "vuetify/locale";

const props = defineProps({
    id: String,
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
    courses: Array,
});

const primaryKey = "id";
const url = "/a/instructors/" + props.id + "/license";

const formStructure = [
    {
        key: "course_id",
        label: "Seleccione un curso",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: [...props.courses],
        itemValue: "id",
        itemTitle: "name",
    },
    {
        key: "start_date",
        label: "Fecha de inicio",
        type: "date",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "end_date",
        label: "Fecha de fin",
        type: "date",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
];
</script>
