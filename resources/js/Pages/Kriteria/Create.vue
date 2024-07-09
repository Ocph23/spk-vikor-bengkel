<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps, onMounted, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
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
    FwbTextarea
} from 'flowbite-vue'
import { Kriteria } from '@/models';

const isBusy = ref(false)

const form = useForm({
    id: 0,
    nama: '',
    variabel: '',
    keterangan: ''
} as unknown as Kriteria)

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
})


onMounted(() => {
    form.id = props.data.id
    form.nama = props.data.nama
    form.variabel = props.data.variabel
    form.keterangan = props.data.keterangan
})


const saveAction = () => {
    console.log('test');
    if (form.id == null || form.id == undefined  || form.id <= 0 ) {
        form.post(route('kriteria.store'), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
                form.reset();
            },
            onError: (err) => {
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
            }
        });
    } else {
        form.put(route('kriteria.update', form.id), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // form.errors = err;
                }
            }
        });
    }
}

const backAction = () => {
    console.log('test');
    window.location.href = '/kriteria'
}



</script>

<template>

    <Head title="Kriteria" />
    <AuthenticatedLayout>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class=" mb-8 flex items-center text-lg">
                        Tambah/Edit Kriteria
                    </div>
                    <form>
                        <div class="mb-6">
                            <fwb-input :disabled="isBusy" v-model="form.nama" required placeholder="Kriteria"
                                label="Nama Kriteria" />
                            <InputError :message="form.errors['nama']" />
                        </div>
                        <div class="mb-6">
                            <fwb-input :disabled="isBusy" v-model="form.variabel" placeholder="Variable"
                                label="Variabel" />
                            <InputError :message="form.errors['variabel']" />
                        </div>
                        <div class="mb-6">
                            <FwbTextarea v-model="form.keterangan" placeholder="Keterangan" label="Keterangan" />
                            <InputError :message="form.errors['keterangan']" />
                        </div>
                    </form>
                    <div class="flex justify-end gap-2">
                        <fwb-button @click="backAction" color="alternative">
                            Batal
                        </fwb-button>

                        <fwb-button color="green" @click="saveAction">
                            Simpan
                        </fwb-button>
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
