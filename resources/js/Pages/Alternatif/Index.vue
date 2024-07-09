<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
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
    FwbInput
} from 'flowbite-vue'
import IconDelete from '@/icons/IconDelete.vue';
import IconEdit from '@/icons/IconEdit.vue';
import { Alternatif } from '@/models';


const props = defineProps({
    data: {
        type: Array<Alternatif>,
        required: true
    }
})

const tambahAction = () => {
    // isShowModal.value = true;
    window.location.href = '/alternatif/create'

}


const form = useForm({
    id: 0
})

function deleteItem(item: any) {
    Swal.fire({
        title: "Anda Yakin ?",
        text: "Menghapus Data !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('alternatif.delete', item.id), {
                onSuccess: (res) => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di hapus.",
                        icon: "success"
                    });

                }, onError: (err:any) => {
                    Swal.fire({
                        title: "Error",
                        text: err,
                        icon: "error"
                    });
                }
            })


        }
    });
}




</script>

<template>

    <Head title="Kriteria" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboardx</h2>
        </template>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="my-5 flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">DATA KARYAWAN</h2>
                <fwb-button @click="tambahAction" color="green">Tambah</fwb-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <fwb-table>
                    <fwb-table-head>
                        <fwb-table-head-cell>No</fwb-table-head-cell>
                        <fwb-table-head-cell>Nama </fwb-table-head-cell>
                        <fwb-table-head-cell>Jenis Kelamin</fwb-table-head-cell>
                        <fwb-table-head-cell>Alamat</fwb-table-head-cell>
                        <fwb-table-head-cell>
                            <span class="sr-only">Action</span>
                        </fwb-table-head-cell>
                    </fwb-table-head>
                    <fwb-table-body>
                        <fwb-table-row v-for="(item, index) in data">
                            <fwb-table-cell>{{ index + 1 }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.jenis_kelamin=='P'? 'Perempuan' : 'Laki-laki' }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.alamat }}</fwb-table-cell>
                            <fwb-table-cell>
                              <div class="flex space-x-2 justify-end">
                                <fwb-button :href="route('alternatif.edit', item.id)" color="yellow" square>
                                  <IconEdit />
                                </fwb-button>
                                <fwb-button @click="deleteItem(item)" color="red" square>
                                  <IconDelete />
                                </fwb-button>
                              </div>
                            </fwb-table-cell>
                        </fwb-table-row>
                    </fwb-table-body>
                </fwb-table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
