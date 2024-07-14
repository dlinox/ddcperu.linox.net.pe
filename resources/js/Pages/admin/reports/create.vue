<template>
    <SimpleForm
        :formularioJson="formStructure"
        v-model="form"
        @onCancel="$emit('onCancel')"
        @onSumbit="submit"
    >
        <template v-slot:field.certificate_id="{ _field }">
            <v-combobox
                v-model="form.certificate_id"
                :items="numbers"
                label="Número de certificado"
                itemValue="id"
                itemTitle="number"
                :return-object="false"
                :rules="[isRequired]"
            ></v-combobox>
        </template>
    </SimpleForm>
</template>

<script setup>
import { ref } from "vue";
import SimpleForm from "@/components/SimpleForm.vue";
import { useForm } from "@inertiajs/vue3";
const emit = defineEmits(["onCancel", "onSubmit"]);
import { isRequired } from "@/helpers/validations.js";
const props = defineProps({
    formData: {
        type: Object,
        default: (props) =>
            props.formStructure?.reduce((acc, item) => {
                acc[item.key] = item.default;
                //el password no se debe enviar en el formulario cuando se esta editando
                if (item.key === "password" && props.edit) {
                    delete acc[item.key];
                }
                return acc;
            }, {}),
    },
    formStructure: {
        type: Array,
    },
    edit: {
        type: Boolean,
        default: false,
    },
    url: String,

    numbers: Array,
});

const form = useForm({ ...props.formData });


const submit = async () => {
    if (props.edit) {
        form.put(props.url, option);
    } else {
        form.post(props.url, option);
    }
};

const option = {
    onSuccess: (page) => {
        console.log("onSuccess");
        emit("onCancel");
    },
    onError: (errors) => {
        console.log("onError");
    },
    onFinish: (visit) => {
        console.log("onFinish");
    },
};
</script>
