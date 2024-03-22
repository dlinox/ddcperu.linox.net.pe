<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle">
            <template #actions>
                <BtnDialog title="Registrar" width="800px">
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
                        {{ item.days_remaining }} día(s)
                    </v-chip>
                </template>
                <template v-slot:item.is_enabled="{ item }">
                    <v-btn
                        :color="item.is_enabled ? 'blue' : 'red'"
                        variant="tonal"
                    >
                        <DialogConfirm
                            text="¿Activar/Desactivar?"
                            @onConfirm="
                                () =>
                                    router.patch(
                                        url +
                                            '/' +
                                            item[`${primaryKey}`] +
                                            '/change-state'
                                    )
                            "
                        />
                        {{ item.is_enabled ? "Activo" : "Inactivo" }}
                    </v-btn>
                </template>

                <template v-slot:action="{ item }">
                    <BtnDialog title="Editar" width="800px">
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
    items: Object,
    headers: Object,
    filters: Object,
    areas: Array,
    permissions: Array,
    agencies: Array,
});

const primaryKey = "id";
const url = "/a/instructors";

const formStructure = [
    {
        key: "instructor_id",
        label: "Instructor ID",
        type: "text",
        required: true,
        cols: 12,
        default: "",
    },

    {
        key: "document_type",
        label: "Tipo de documento",
        type: "select",
        required: true,
        cols: 12,
        colMd: 6,
        default: "001",
        options: [
            { value: "001", title: "DNI" },
            { value: "002", title: "Carnet de extranjería" },
            { value: "003", title: "Pasaporte" },
        ],
        itemValue: "id",
        itemTitle: "title",
    },
    {
        key: "document_number",
        label: "Número de documento",
        type: "text",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "name",
        label: "Nombre",
        type: "text",
        required: true,
        cols: 12,
        default: "",
    },
    {
        key: "last_name",
        label: "Apellidos",
        type: "text",
        required: true,
        cols: 12,

        default: "",
    },

    {
        key: "email",
        label: "Correo",
        type: "email",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },

    {
        key: "phone_number",
        label: "Teléfono",
        type: "text",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "username",
        label: "Usuario",
        type: "text",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "password",
        label: "Contraseña",
        type: "password",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },

    {
        key: "license_start",
        label: "Fecha de inicio de licencia",
        type: "date",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        key: "license_end",
        label: "Fecha de fin de licencia",
        type: "date",
        required: true,
        cols: 12,
        colMd: 6,
        default: "",
    },
    {
        //linkde instractor
        key: "instructor_link",
        label: "Link de instructor",
        type: "text",
        required: false,
        cols: 12,
        default: "",
    },
    {
        key: "agency_id",
        label: "Agencia",
        type: "combobox",
        required: true,
        cols: 12,
        default: null,
        options: props.agencies,
        itemValue: "id",
        itemTitle: "name",
    },
];
</script>
