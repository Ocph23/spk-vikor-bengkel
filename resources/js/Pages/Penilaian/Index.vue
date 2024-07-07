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
    FwbInput,
    FwbSelect
} from 'flowbite-vue'
import IconDelete from '@/icons/IconDelete.vue';
import IconEdit from '@/icons/IconEdit.vue';
import PenilaianSelect from '@/Components/PenilaianSelect.vue';
import { Kriteria } from '@/models';



const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    periode: {
        type: Array,
        required: true
    },
    kriteria: {
        type: Array<Kriteria>,
        required: true
    },
    alternatif: {
        type: Array,
        required: true
    },
    periodeAktif: {
        type: {} as any,
        required: true
    }
})

const periodeSelected = ref(props.periodeAktif);

const isShowModal=ref(false);

const periodeOptions = props.periode.map((item: any) => {
    return {
        name: item.nama,
        value: item
    }
})

const getAllSubKriteria = () => {
    let datas: any[] = [];
    let x = props.kriteria.map((item: any) => {
        datas.push(...item.subkriteria);
    }, []);
    return datas;
}

Array.prototype.max = function () {
    return Math.max.apply(null, this);
};

Array.prototype.min = function () {
    return Math.min.apply(null, this);
};

const hitung = () => {
    //total Bobot
    let totalBobotKriteria = props.kriteria.reduce((acc: any, item: any) => {
        return acc + item.bobot
    }, 0);


    let allSubKriteria = getAllSubKriteria();

    ///Collecting Data

    const dataAlternatif: any[] = [];
    props.alternatif.forEach((element: any) => {
        const nilaiPilihan = element.nilai_alternatif.filter(x => x.periode_id == periodeSelected.value.id);
        const fixNilai = nilaiPilihan.map((item: any) => {
            let subkriteria = allSubKriteria.find(x => x.id == item.sub_kriteria_id);
            return subkriteria;
        });
        dataAlternatif.push({ id: element.id, nama: element.nama, nilai: fixNilai })
    });



    //Calculate W=1
    props.kriteria.forEach(kriteria => {
        kriteria.W = kriteria.bobot / totalBobotKriteria;
        var dataNilai: number[] = dataAlternatif.map(item => {
            var r = item.nilai.find((x: any) => x && x.kriteria_id == kriteria.id);
            if (r) {
                return r.bobot
            }
            else {
                return 0
            }
        }
        );
        kriteria.Max = dataNilai.max();
        kriteria.Min = dataNilai.min();
    })

    let w1: number = props.kriteria.reduce((acc, item) => acc + item.W, 0)


    //Normalisasi 

    dataAlternatif.forEach(al => {
        al.nilaiNormal = [];
        props.kriteria.forEach(kr => {
            let nilai = al.nilai.find((x: any) => x && x.kriteria_id == kr.id);
            let bobotPilihan = nilai ? nilai.bobot : 0;
            let nilaiR = (kr.Max - bobotPilihan) / (kr.Max - kr.Min);
            al.nilaiNormal.push({
                kriteria_id: kr.id,
                R: nilaiR,
                S: nilaiR * kr.W
            });
        });
        al.S = al.nilaiNormal.reduce((acc: number, item: any) => acc + item.S, 0);
        al.R = al.nilaiNormal.map((x: any) => x.R).max();
    });


    //hitung nilai minmax r minmax s
    let rMin = dataAlternatif.map(x => x.R).min();
    let sMin = dataAlternatif.map(x => x.S).min();

    let rMax = dataAlternatif.map(x => x.R).max();
    let sMax = dataAlternatif.map(x => x.S).max();

    const V = 0.5;

    dataAlternatif.forEach(al => {
        al.Q = ((al.S - sMin) / (sMax - sMin) * V) + ((al.R - rMin) / (rMax - rMin) * V);
    });





    ///Nilai index /Q


    console.log('totalBobot :', totalBobotKriteria);
    console.log('w1 :', w1);
    console.log('allSubKriteria :', allSubKriteria);
    console.log('kriteria :', props.kriteria);
    console.log('data :', dataAlternatif);

    console.log('rMin :', rMin);
    console.log('sMin :', sMin);
    console.log('rMax :', rMax);
    console.log('sMax :', sMax);


    isShowModal.value = true;
}

const closeModal = () => {
    isShowModal.value = false;

}

const saveAction = () => {
    console.log('test');
    const form = useForm({
        periode: props.periodeAktif,
        penilaian: props.alternatif
    })
    if (form.id <= 0 || form.id == undefined) {
        form.post(route('penilaian.store'), {
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
    }
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

                }, onError: (err: any) => {
                    Swal.fire({
                        title: "Error",
                        text: err.message,
                        icon: "error"
                    });
                }
            })


        }
    });
}

const getModel = (alternatif: any, item2: any) => {
    let subkriteria = 0;
    if (alternatif.nilai_alternatif && alternatif.nilai_alternatif.length > 0) {
        let subkriteriax = alternatif.nilai_alternatif.find((item: any) => item.kriteria_id == item2.id && item.periode_id == periodeSelected.value.id)
        if (subkriteriax) {
            return subkriteriax;
        }
    }

    const data = {
        periode_id: props.periodeAktif.id,
        kriteria_id: item2.id,
        alternatif_id: alternatif.id,
        sub_kriteria_id: subkriteria,
    };
    alternatif.nilai_alternatif.push(data);
    return data;
}

</script>

<template>

    <Head title="Kriteria" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboardx</h2>
        </template>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">DATA PENILAIAN</h2>
            <div class="my-5 flex justify-between">
                <FwbSelect :options="periodeOptions" v-model="periodeSelected" placeholder="Pilih Periode">
                </FwbSelect>
                <div class="gap-1 flex">
                    <fwb-button @click="saveAction" color="green"
                        :disabled="!periodeSelected.status">Simpan</fwb-button>
                    <fwb-button @click="hitung" color="green">Hasil Analisa</fwb-button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <fwb-table>
                    <fwb-table-head>
                        <fwb-table-head-cell>No</fwb-table-head-cell>
                        <fwb-table-head-cell>Nama </fwb-table-head-cell>
                        <fwb-table-head-cell v-for="item2 in kriteria">{{ item2.nama }} -
                            ({{ item2.variabel }})</fwb-table-head-cell>

                    </fwb-table-head>
                    <fwb-table-body>
                        <fwb-table-row v-for="(item, index) in alternatif">
                            <fwb-table-cell>{{ index + 1 }}</fwb-table-cell>
                            <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                            <fwb-table-cell v-for="item2 in kriteria">
                                <PenilaianSelect :model="getModel(item, item2)" :kriteria="item2" :alternatif="item"
                                    :periode="periodeSelected"></PenilaianSelect>
                            </fwb-table-cell>
                        </fwb-table-row>
                    </fwb-table-body>
                </fwb-table>
            </div>
            <fwb-modal v-if="isShowModal" @close="closeModal"  size="7xl" persistent >
                <template #header>
                    <div class="flex items-center text-lg">
                       HASIL PERHITUNGAN
                    </div>
                </template>
                <template #body>
                   
                </template>
                <template #footer>
                    <div class="flex justify-end">
                        <fwb-button @click="closeModal" color="green">
                            Keluar
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </AuthenticatedLayout>
</template>
