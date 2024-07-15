<template>
    <SimpleForm
        :formularioJson="formStructure"
        v-model="form"
        @onCancel="$emit('onCancel')"
        @onSumbit="submit"
    >
        <template #field.quantity>
            Cantidad de certificados:
            <strong>
                {{
                    (form.range_end ? form.range_end : 0) -
                    (form.range_start ? form.range_start : 0) +
                    1
                }}
            </strong>
        </template>
    </SimpleForm>
</template>

<script setup>
import { ref } from "vue";
import SimpleForm from "@/components/SimpleForm.vue";
import { useForm } from "@inertiajs/vue3";
const emit = defineEmits(["onCancel", "onSubmit"]);
const props = defineProps({
    formData: {
        type: Object,
        default: (props) =>
            props.formStructure?.reduce((acc, item) => {
                acc[item.key] = item.default;
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
});

const form = useForm({ ...props.formData });

const submit = async () => {
    console.log(form.quantity);

    let quantity = form.range_end - form.range_start + 1;

    if (quantity > 100) {
        if (
            confirm(
                "¿Está seguro que desea generar " + quantity + " certificados?"
            )
        ) {
            if (props.edit) {
                form.put(props.url, option);
            } else {
                form.post(props.url, option);
            }
        }
    } else {
        if (props.edit) {
            form.put(props.url, option);
        } else {
            form.post(props.url, option);
        }
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
