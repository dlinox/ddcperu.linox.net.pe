<template>
    <slot name="header" :filter="formfilters"> </slot>
    <v-table class="mt-3">
        <thead>
            <tr>
                <th
                    class="text-left th-data-table"
                    v-for="(item, index) in headers"
                    :key="index"
                    @click="
                        !item.short
                            ? (item.short = true)
                            : item.order == 'DESC'
                            ? ((item.short = false), (item.order = 'ASC'))
                            : (item.order = 'DESC')
                    "
                >
                    <div
                        class="d-flex justify-space-between align-center text-black"
                    >
                        <small>{{ item.text.toUpperCase() }} </small>
                    </div>
                </th>
                <th v-if="withAction" class="th-action">

                </th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="item in items.data" :key="item.name">
                <template v-for="(header, j) in headers">
                    <td>
                        <slot :name="'item.' + header.value" :item="item">
                            {{ item[`${header.value}`] }}
                        </slot>
                    </td>
                </template>
                <template v-if="withAction">
                    <td>
                        <slot name="action" :item="item"> </slot>
                    </td>
                </template>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td :colspan="withAction ? headers.length + 1 : headers.length">
                    <div class="d-flex justify-space-between align-center py-3">
                        <div class="">
                            <v-select
                                v-model="formfilters.perPage"
                                label="Mostrar"
                                density="compact"
                                placeholder="10"
                                :clearable="false"
                                :items="[1, 5, 10, 50, 100, 500]"
                            />
                        </div>
                        <v-spacer></v-spacer>
                        <div>
                            <PaginationDataTable :items="items" />
                        </div>
                    </div>
                    <slot name="footer" :filter="formfilters"> </slot>
                </td>
            </tr>
        </tfoot>
    </v-table>
</template>

<script setup>
import { ref, watch } from "vue";
import PaginationDataTable from "@/components/PaginationDataTable.vue";
import { pickBy, throttle, debounce } from "lodash";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    headers: Array,
    items: Object,
    url: String,
    withAction: {
        type: Boolean,
        default: false,
    },
});

const formfilters = ref({
    search: "",
    perPage: props.items.per_page,
});

watch(
    formfilters,
    debounce((val) => {
        router.get(props.url, pickBy(val), {
            preserveState: true,
        });
    }, 300),
    { deep: true }
);
</script>
<style lang="scss">
.th-data-table {
    &:hover {
        background-color: rgba(55, 55, 55, 0.04);
    }
}
.th-action {
    width: 150px;
}
</style>
