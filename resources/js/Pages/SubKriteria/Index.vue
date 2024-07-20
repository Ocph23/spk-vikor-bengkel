<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, reactive, ref } from 'vue';
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
    FwbSelect,
    FwbTextarea

} from 'flowbite-vue'
import IconDelete from '@/icons/IconDelete.vue';
import IconEdit from '@/icons/IconEdit.vue';
import axios from 'axios';
import { Kriteria, ResponseRequest, SubKriteria } from '@/models';
import InputError from '../../Components/InputError.vue';



const props = defineProps({
    kriteria: {
        type: Array<Kriteria>,
        required: true
    }
})


const error=ref({} as any);

let model: SubKriteria = {
    nama: '',
    keterangan: '',
    bobot: 0,
    kriteria_id: 0,
    id: 0,
    errors: []
};

const isBusy = ref(false);
const kriteriaId = ref(0);

let listSubkriteria = ref([] as SubKriteria[]);

const kriteriaOption = props.kriteria.map((item: any) => {
    return {
        value: item.id,
        name: `${item.nama} - ( ${item.variabel} )`
    }
})

const changeKriteria = (e: any) => {
    kriteriaId.value = e.target.value;
    axios
        .get('/api/subkriteria/bykriteriaid/' + e.target.value)
        .then((response) => {
            listSubkriteria.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        })


}

const isShowModal = ref(false)

function closeModal() {
    setTimeout(() => {
        isShowModal.value = false
    }, 500);
}
function showModal(param: any) {
    if (param) {
        model = param
    } else {
        model = {
            id: 0,
            nama: '',
            kriteria_id: kriteriaId.value,
            keterangan: '',
            bobot: 0,
            errors:[]
        };
    }
    isShowModal.value = true
}


const saveAction = () => {
    if (model.id <= 0 || model.id == undefined) {
        axios.post('/api/subkriteria', model).then((response) => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Data Berhasil Disimpan ",
                showConfirmButton: false,
                timer: 1500
            });
            const item = response.data.data as SubKriteria;
            listSubkriteria.value.push(item);
            closeModal()
        }).catch((err: any) => {
            error.value.errors = err.response.data.errors;
            if(!error.value.errors){
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: err.response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    } else {
        axios.put('/api/subkriteria/' + model.id, model).then((response: any) => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Data Berhasil Disimpan ",
                showConfirmButton: false,
                timer: 1500
            });
            closeModal()
        }).catch((err: any) => {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: err,
                showConfirmButton: false,
                timer: 1500
            });
        })
    }

}

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
            axios.delete('/api/subkriteria/' + item.id).then((response: any) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
                listSubkriteria.value.splice(listSubkriteria.value.indexOf(item), 1);
            }).catch((err: any) => {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: err,
                    showConfirmButton: false,
                    timer: 1500
                });
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">DATA SUB KRITERIA</h2>
            <div class="my-5 flex justify-between">
                <FwbSelect :options="kriteriaOption" @change="changeKriteria" placeholder="Pilih Kriteria">

                </FwbSelect>
                <fwb-button @click="showModal(null)" color="green" :disabled="kriteriaId <= 0">Tambah</fwb-button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <fwb-table>
                    <fwb-table-head>
                        <fwb-table-head-cell>No</fwb-table-head-cell>
                        <fwb-table-head-cell>Nama Sub Kriteria</fwb-table-head-cell>
                        <fwb-table-head-cell>Keterangan</fwb-table-head-cell>
                        <fwb-table-head-cell>Bobot</fwb-table-head-cell>
                        <fwb-table-head-cell>
                            <span class="sr-only">Action</span>
                        </fwb-table-head-cell>
                    </fwb-table-head>
                    <fwb-table-body>
                        <fwb-table-row v-for="(item, index) in listSubkriteria">
                            <fwb-table-cell>{{ index + 1 }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.keterangan }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.bobot }}</fwb-table-cell>
                            <fwb-table-cell>
                                <div class="flex space-x-2 justify-end">
                                    <fwb-button @click="showModal(item)" color="yellow" square>
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

        <fwb-modal persistent v-if="isShowModal" @close="closeModal">
            <template #header>
                <div class=" mb-8 flex items-center text-lg">
                        Tambah/Edit Sub Kriteria
                    </div>
            </template>
            <template #body>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form>
                        <div class="mb-6">
                            <fwb-input :disabled="isBusy" v-model="model.nama" required placeholder="Sub Kriteria"
                                label="Nama Sub Kriteria" />
                            <InputError :message="error.errors ? error.errors['nama'] : ''" />
                        </div>
                   
                        <div class="mb-6">
                            <fwb-input type="number" :disabled="isBusy" v-model="model.bobot" placeholder="Bobot"
                                label="Bobot" />
                            <InputError :message="error.errors ? error.errors['bobot'] : ''" />
                        </div>
                        <div class="mb-6">
                            <FwbTextarea v-model="model.keterangan" placeholder="Keterangan" label="Keterangan" />
                            <!-- <InputError :message="error.errors ? error.errors['keterangan'] : ''" /> -->
                        </div>
                    </form>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <fwb-button @click="closeModal" color="alternative">
                        Batal
                    </fwb-button>

                    <fwb-button color="green" @click="saveAction">
                        Simpan
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>
    </AuthenticatedLayout>
</template>
