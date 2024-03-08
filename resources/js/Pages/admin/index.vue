<template>
    <AdminLayout>
        <HeadingPage :title="title" :subtitle="subtitle"> </HeadingPage>

        <v-container fluid>
            <v-row>
                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Instrictores </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">  0 </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Instructores
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar flat density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Certificados </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">0</span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Certificados
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Sub Agencias </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5">0 </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Sub Agencias
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-card>
                        <v-toolbar density="compact" color="primary">
                            <v-toolbar-title>
                                <small> Cursos </small>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-icon class="me-3">mdi-account</v-icon>
                        </v-toolbar>
                        <v-card-item>
                            <v-avatar size="60" color="primary">
                                <span class="text-h5"> 0 </span>
                            </v-avatar>
                            <span class="ms-4 text-h6 text-primary">
                                Cursos
                            </span>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import BtnDialog from "@/components/BtnDialog.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,

    users: [String, Number],
    doctors: [String, Number],
    posts: [String, Number],
});
</script>
