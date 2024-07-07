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
    FwbTextarea,
    FwbSelect
} from 'flowbite-vue'

const isBusy = ref(false)

const form = useForm({
    id: 0,
    nama: '',
    jenis_kelamin: '',
    alamat: ''
})

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
})

const genderOptions = [
    {
        value: "L",
        name: "Laki-laki"
    },
    {
        value: 'P',
        name: 'Perempuan'
    }
]



onMounted(() => {
    form.id = props.data.id
    form.nama = props.data.nama
    form.jenis_kelamin = props.data.jenis_kelamin
    form.alamat = props.data.alamat
})



const saveAction = () => {
    console.log('test');
    if (form.id <= 0 || form.id == undefined) {
        form.post(route('alternatif.store'), {
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
        form.put(route('alternatif.update', form.id), {
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
    window.location.href = '/alternatif'
}



</script>

<template>

    <Head title="alternatif" />
    <AuthenticatedLayout>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class=" mb-8 flex items-center text-lg">
                        Tambah/Edit Alternatif
                    </div>
                    <form >
                        <div class="mb-6">
                            <fwb-input :disabled="isBusy" v-model="form.nama" label="Nama" name="Nama" />
                            <InputError :message="form.errors['nama']" />
                        </div>
                        <div class="mb-6">
                            <FwbSelect label="Jenis Kelamin" placeholder="Jenis Kelamin" name="Jenis Kelamin" required
                                v-model="form.jenis_kelamin" :options="genderOptions"></FwbSelect>
                            <InputError :message="form.errors['jenis_kelamin']" />
                        </div>
                        <div class="mb-6">
                            <FwbTextarea v-model="form.alamat" label="Alamat" placeholder="Alamat" name="Alamat " />
                            <InputError :message="form.errors['alamat']" />
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
