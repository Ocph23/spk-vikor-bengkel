<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, watch, ref, computed } from 'vue';
import Swal from 'sweetalert2';

import {
    FwbA,
    FwbTable,
    FwbTableBody,
    FwbTableCell,
    FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow,
    FwbButton,
    FwbModal,
    FwbInput,
    FwbAlert
} from 'flowbite-vue'
import IconDelete from '@/icons/IconDelete.vue';
import IconEdit from '@/icons/IconEdit.vue';


const props = defineProps({
    data: {
        type: Array,
        required: true
    }
})

const datax=ref(props.data);

const isBusy = ref(false);  
const saveAction = () => {
    isBusy.value= true;
    const form = useForm(datax.value);
    if (form.id <= 0 || form.id == undefined) {
        form.post(route('kriteria.bobot'), {
            onSuccess: (res:any) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
                isBusy.value = false;
            },
            onError: (err:any) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // form.errors = err;
                }
                isBusy.value = false;
            }
        });
    } else{
        isBusy.value = false;
    }
}

const errorMessage = ref(false);
const errorTextMessage = ref('Total Bobot Harus 100%');

const totalBobot = computed(() => {
    return datax.value.reduce((acc, item) => {
        if(!item.bobot){
            errorTextMessage.value = 'Bobot Kriteria Tidak Boleh ada yang kosong atau 0';
        }else{
            errorTextMessage.value = 'Total Bobot Harus 100%';
        }
        return acc + item.bobot
    }, 0)
})


watch(totalBobot, (val) => {
    if (val != 100) {
        errorMessage.value = true;
    } else {
        errorMessage.value = false;
    }
})


</script>

<template>

    <Head title="Kriteria" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboardx</h2>
        </template>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="my-5 flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">DATA BOBOT KRITERIA</h2>
                <fwb-button @click="saveAction" color="green" :disabled="errorMessage || isBusy">Simpan</fwb-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <fwb-table>
                    <fwb-table-head>
                        <fwb-table-head-cell>No</fwb-table-head-cell>
                        <fwb-table-head-cell>Nama Kriteria</fwb-table-head-cell>
                        <fwb-table-head-cell>Variabel</fwb-table-head-cell>
                        <fwb-table-head-cell>Bobot Kriteria</fwb-table-head-cell>
                    </fwb-table-head>
                    <fwb-table-body>
                        <fwb-table-row v-for="(item, index) in datax">
                            <fwb-table-cell>{{ index + 1 }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.variabel }}</fwb-table-cell>
                            <fwb-table-cell class=" w-32">
                                <fwb-input type="number" v-model="item.bobot" />
                            </fwb-table-cell>
                        </fwb-table-row>
                        <fwb-table-row>
                            <fwb-table-cell colspan="3">
                                <span>Total Bobot Harus 100%</span>
                            </fwb-table-cell>
                            <fwb-table-cell class="font-bold">
                                <span class="flex justify-center">
                                    {{ totalBobot }} %
                                </span>
                            </fwb-table-cell>
                        </fwb-table-row>
                        <fwb-table-row>
                            <fwb-table-cell v-if="errorMessage" colspan="4">
                                <fwb-alert type="danger">
                                    <span class="text-red-500">{{ errorTextMessage }}</span>
                                </fwb-alert>
                            </fwb-table-cell>
                        </fwb-table-row>
                    </fwb-table-body>
                </fwb-table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
