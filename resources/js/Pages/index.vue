<template>
    <v-app>
        <header class="header">
            <div class="header-top">
                <v-container class="d-flex justify-space-between align-center">
                    <div>
                        <img src="/assets/logos/logo.png" alt="" />
                    </div>
                    <div>
                        <img src="/assets/logos/nsc.png" alt="" />
                    </div>
                </v-container>
            </div>
            <div class="header-bot">
                <v-container>
                    <h1>Verifica tu certificado</h1>
                </v-container>
            </div>
        </header>
        <v-main>
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-form @submit.prevent="submit" ref="formRef">
                            <v-card class="py-3 px-2" rounded="lg">
                                <v-card-title
                                    class="d-flex justify-space-between"
                                >
                                    <v-row justify="space-between">
                                        <v-col cols="4">
                                            <h4>Buscar</h4>
                                        </v-col>
                                        <v-col cols="6" md="4">
                                            <v-select
                                                v-model="searchBy"
                                                :clearable="false"
                                                :items="['Documento', 'Código']"
                                                label="Buscar por:"
                                                :rules="[isRequired]"
                                            ></v-select>
                                        </v-col>

                                        <v-col cols="12">
                                            <v-text-field
                                                append-inner-icon="mdi-magnify"
                                                v-model="search"
                                                :label="`Ingrese su ${searchBy}`"
                                                :rules="[isRequired]"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <div class="d-flex justify-end">
                                                <vue-hcaptcha
                                                    sitekey="ff7ec74e-ee9d-4af4-b79d-5f5a3c7601db"
                                                    @verify="onVerify"
                                                    @expired="onExpired"
                                                    @error="onError"
                                                ></vue-hcaptcha>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-title>

                                <v-card-actions>
                                    <v-btn type="submit" block color="primary">
                                        buscar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-form>
                    </v-col>

                    <v-col cols="12" v-for="item in result">
                        <v-card elevation="10" rounded="lg" class="pa-2">
                            <v-card-title class="d-flex justify-end">
                                <h2 class="text-primary">
                                    N° Reg. {{ item.certificate_number }}
                                </h2>
                            </v-card-title>

                            <v-card-title class="d-flex justify-space-between">
                                <h4>
                                    Emitido por:
                                    <span class="text-primary">
                                        {{ item.agency }}
                                    </span>
                                </h4>
                            </v-card-title>
                            <v-card-title class="d-flex justify-space-between">
                                <h3 class="mb-2">
                                    <v-icon color="primary"
                                        >mdi-certificate</v-icon
                                    >
                                    {{ item.course }}
                                </h3>
                            </v-card-title>
                            <v-card-text>
                                <h2>{{ item.student }}</h2>
                                <p class="my-2">
                                    <strong class="me-2">Instructor:</strong>
                                    {{ item.instructor }}

                                    <br />
                                    <a
                                        :href="item.instructor_link"
                                        target="_blank"
                                    >
                                        <v-icon> mdi-eye </v-icon>
                                        Ver instructor
                                    </a>
                                </p>
                                <p
                                    class="text-end text-blue-grey-lighten-2 text-subtitle-1"
                                >
                                    <strong> Periodo de validez: </strong>
                                    {{ item.start_date }} - {{ item.end_date }}
                                </p>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-footer app absolute>
                <v-container class="my-4">
                    <v-row>
                        <v-col cols="6" md="3">
                            <a
                                target="_blank"
                                href="https://www.facebook.com/ddcperuoficial"
                            >
                                <v-icon class="me-2" color="primary" size="40"
                                    >mdi-facebook</v-icon
                                >
                            </a>
                            <a
                                target="_blank"
                                href="https://www.instagram.com/ddcperu/"
                            >
                                <v-icon class="me-2" color="primary" size="40"
                                    >mdi-instagram</v-icon
                                >
                            </a>
                            <a
                                target="_blank"
                                href="https://api.whatsapp.com/send?phone=51996000465&text=%C2%A1Hola!%20Quiero%20informaci%C3%B3n%20de%20los%20cursos%20"
                            >
                                <v-icon class="me-2" color="primary" size="40"
                                    >mdi-whatsapp</v-icon
                                >
                            </a>
                        </v-col>
                        <v-col cols="6" md="3">
                            <p>
                                <a href="tel:+51996000465">
                                    <strong class="text-primary">
                                        Teléfono:
                                    </strong>
                                    (+51) 996 000 465
                                </a>
                            </p>
                            <p>
                                <a href="mailto:ddcperu@ddcperu.com.pe">
                                    <strong class="text-primary">
                                        Correo:
                                    </strong>
                                    ddcperu@ddcperu.com.pe
                                </a>
                            </p>
                        </v-col>
                        <v-col cols="6" md="3">
                            <p>
                                <a
                                    href="https://ddcperu.com.pe/cursos-certificados-nsc/"
                                    target="_blank"
                                >
                                    <strong class="text-primary">
                                        Calendario:
                                    </strong>
                                    Cursos y Capacitaciones
                                </a>
                            </p>

                            <p>
                                <a
                                    href="https://ddcperu.com.pe/politica-de-proteccion-de-datos/"
                                    target="_blank"
                                >
                                    <strong class="text-primary">
                                        Política de Protección de Datos
                                    </strong>
                                </a>
                            </p>
                        </v-col>
                        <v-col cols="6" md="3">
                            <div class="d-flex justify-end">
                                <img
                                    src="/assets/logos/nsc.png"
                                    alt="Logo NSC"
                                    width="120"
                                />
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-footer>
        </v-main>
    </v-app>
</template>
<script setup>
import axios from "axios";

import { ref } from "vue";
import { isRequired } from "@/helpers/validations";
import VueHcaptcha from "@hcaptcha/vue3-hcaptcha";

const searchBy = ref("Documento");
const search = ref("");
const formRef = ref(null);

const onVerify = (token) => {
    console.log("verified", token);
};

const onExpired = () => {
    console.log("expired");
};

const onError = (error) => {
    console.log("error", error);
};

const result = ref([]);

const submit = async () => {
    //validar el formulario
    const { valid } = await formRef.value.validate();
    if (!valid) return;
    //validar el captcha
    if (!window.hcaptcha.getResponse()) {
        alert("Por favor, verifica que no eres un robot");
        return;
    }
    //post search-certificate-student'
    const { data } = await axios.post("/api/search-certificate-student", {
        by: searchBy.value,
        search: search.value,
    });
    result.value = data;

    if (data.length === 0) {
        alert("No se encontraron resultados");
    }
};
</script>
<style lang="scss">
a {
    color: inherit;
    text-decoration: none;
}
.header {
    .header-top {
        background-color: darken(#fff, 1);
        position: relative;
        z-index: 11;
    }
    .header-bot {
        padding: 3rem 0;
        position: relative;
        z-index: 15;
        text-align: center;
        width: 100%;
        display: flex;
        justify-content: center;
        text-align: center;

        h1 {
            color: #fff;
            font-size: 2.5rem;
            text-align: center;
            text-transform: uppercase;
        }

        &::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background-color: #fff;
        }
    }
    width: 100%;

    background: #ff6633;
    background-image: url("/assets/bg/header.jpg");
    background-size: cover;
    background-position: center;

    position: relative;

    z-index: 10;
    overflow: hidden;
    &::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(#ff6633, 0.9);
        z-index: 10;
    }

    &::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        z-index: 12;
    }
}
</style>
