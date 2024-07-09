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
    FwbSelect,
    FwbCard,
    FwbHeading
} from 'flowbite-vue'
import IconDelete from '@/icons/IconDelete.vue';
import IconEdit from '@/icons/IconEdit.vue';
import PenilaianSelect from '@/Components/PenilaianSelect.vue';
import { Kriteria } from '@/models';
import { VTIconPrint } from '@/icons';
import IconPrint from '@/icons/IconPrint.vue';



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
const dataAlternatif = ref([] as any[]);

const isShowModal = ref(false);

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

function rank(arr: [], f: any) {
    return arr
        .map((x, i) => [x, i])
        .sort((a, b) => f(a[0], b[0]))
        .reduce((a, x, i, s) => (a[x[1]] =
            i > 0 && f(s[i - 1][0], x[0]) === 0 ? a[s[i - 1][1]] : i + 1, a), []);
}

let rMin = 0;
let sMin = 0;
let rMax = 0;
let sMax = 0;
let ranking: number[] = [];


const hitung = () => {
    dataAlternatif.value = [];
    //total Bobot
    let totalBobotKriteria = props.kriteria.reduce((acc: any, item: any) => {
        return acc + item.bobot
    }, 0);


    let allSubKriteria = getAllSubKriteria();

    ///Collecting Data


    props.alternatif.forEach((element: any) => {
        const nilaiPilihan = element.nilai_alternatif.filter(x => x.periode_id == periodeSelected.value.id);
        const fixNilai = nilaiPilihan.map((item: any) => {
            let subkriteria = allSubKriteria.find(x => x.id == item.sub_kriteria_id);
            return subkriteria;
        });
        dataAlternatif.value.push({ id: element.id, nama: element.nama, jeniskelamin: element.jenis_kelamin, nilai: fixNilai })
    });



    //Calculate W=1
    props.kriteria.forEach(kriteria => {
        kriteria.W = kriteria.bobot / totalBobotKriteria;
        var dataNilai: number[] = dataAlternatif.value.map(item => {
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

    dataAlternatif.value.forEach(al => {
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
    rMin = dataAlternatif.value.map(x => x.R).min();
    sMin = dataAlternatif.value.map(x => x.S).min();

    rMax = dataAlternatif.value.map(x => x.R).max();
    sMax = dataAlternatif.value.map(x => x.S).max();

    const V = 0.5;

    dataAlternatif.value.forEach(al => {
        al.Q = ((al.S - sMin) / (sMax - sMin) * V) + ((al.R - rMin) / (rMax - rMin) * V);
    });


    let arrayOfQ = dataAlternatif.value.map(x => x.Q);
    ranking = rank(arrayOfQ, (a: number, b: number) => a - b)


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


    setTimeout(() => {
        isShowModal.value = true;
    }, 1000);
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


const print = () => {
    window.print();
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

<style>
.print {
    display: none;
}

@media print {
    .print {
        display: block;
    }

    .noprint {
        display: none
    }
}
</style>

<template>

    <Head title="Kriteria" />
    <AuthenticatedLayout class="noprint">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboardx</h2>
        </template>
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">DATA PENILAIAN</h2>
            <div class="my-5 flex justify-between">
                <FwbSelect :options="periodeOptions" v-model="periodeSelected" placeholder="Pilih Periode"></FwbSelect>
                <div class="gap-1 flex">
                    <fwb-button @click="saveAction" color="green"
                        :disabled="!periodeSelected.status">Simpan</fwb-button>
                    <fwb-button @click="hitung" color="yellow" >Analisa</fwb-button>
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
            <fwb-modal v-if="isShowModal" @close="closeModal" size="7xl" persistent>
                <template #header>
                    <div class=" w-full flex justify-between text-lg">
                        <div>HASIL PERHITUNGAN</div>
                        <div class="flex gap-1">
                            <div class="flex justify-end">
                                <fwb-button @click="print" color="yellow">
                                    <div class="flex">
                                        <IconPrint class="cursor-pointer text-yellow-200 mr-2" />
                                        Print
                                    </div>
                                </fwb-button>
                            </div>
                            <div class="flex justify-end">
                                <fwb-button @click="closeModal" color="green">
                                    Keluar
                                </fwb-button>
                            </div>
                        </div>
                    </div>
                </template>
                <template #body>
                    <div>
                        <FwbHeading tag="h5" class="mb-3">DATA NILAI ALTERNATIF PADA SETIAP KRITERIA</FwbHeading>
                        <fwb-table>
                            <fwb-table-head>
                                <fwb-table-head-cell>No</fwb-table-head-cell>
                                <fwb-table-head-cell>Nama</fwb-table-head-cell>
                                <fwb-table-head-cell class="text-center" v-for="item in kriteria">{{ item.nama }} ({{
                                    item.variabel
                                }})</fwb-table-head-cell>

                            </fwb-table-head>
                            <fwb-table-body>
                                <fwb-table-row v-for="(item, index) in dataAlternatif" :key="index">
                                    <fwb-table-cell>{{ index + 1 }} </fwb-table-cell>
                                    <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                                    <fwb-table-cell v-for="n in item.nilai" :key="item.id">
                                        <div class="w-full flex justify-center">
                                            {{ n.bobot.toFixed(2) }}
                                        </div>
                                    </fwb-table-cell>
                                </fwb-table-row>
                                <fwb-table-row>
                                    <fwb-table-cell colspan="2">Bobot</fwb-table-cell>
                                    <fwb-table-cell v-for="n in kriteria" :key="n.id">
                                        <div class="w-full flex justify-center">
                                            {{ n.bobot.toFixed(2) }} %
                                        </div>
                                    </fwb-table-cell>
                                </fwb-table-row>
                                <fwb-table-row>
                                    <fwb-table-cell colspan="2">W=1</fwb-table-cell>
                                    <fwb-table-cell v-for="n in kriteria" :key="n.id">
                                        <div class="w-full flex justify-center">
                                            {{ n.W.toFixed(2) }}
                                        </div>
                                    </fwb-table-cell>
                                </fwb-table-row>
                            </fwb-table-body>
                        </fwb-table>

                        <FwbHeading tag="h5" class="my-3 mt-10">NORMALISASI</FwbHeading>
                        <fwb-table>
                            <fwb-table-head>
                                <fwb-table-head-cell>No</fwb-table-head-cell>
                                <fwb-table-head-cell>Nama</fwb-table-head-cell>
                                <fwb-table-head-cell class="text-center" v-for="item in kriteria">{{ item.nama }} ({{
                                    item.variabel
                                }})</fwb-table-head-cell>

                            </fwb-table-head>
                            <fwb-table-body>

                                <fwb-table-row v-for="(item, index) in dataAlternatif" :key="index">
                                    <fwb-table-cell>{{ index + 1 }} </fwb-table-cell>
                                    <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                                    <fwb-table-cell v-for="n in item.nilaiNormal" :key="item.id">
                                        <div class="w-full flex justify-center">
                                            {{ n.R.toFixed(2) }}
                                        </div>
                                    </fwb-table-cell>

                                </fwb-table-row>


                            </fwb-table-body>
                        </fwb-table>

                        <FwbHeading tag="h5" class="my-3 mt-10">Menghitung Nilai S Dan R</FwbHeading>
                        <fwb-table>
                            <fwb-table-head>
                                <fwb-table-head-cell>No</fwb-table-head-cell>
                                <fwb-table-head-cell>Nama</fwb-table-head-cell>
                                <fwb-table-head-cell class="text-center" v-for="item in kriteria">{{ item.nama }} ({{
                                    item.variabel
                                }})</fwb-table-head-cell>
                                <fwb-table-head-cell>Nilai S</fwb-table-head-cell>
                                <fwb-table-head-cell>Nilai R</fwb-table-head-cell>

                            </fwb-table-head>
                            <fwb-table-body>
                                <fwb-table-row v-for="(item, index) in dataAlternatif" :key="index">
                                    <fwb-table-cell>{{ index + 1 }} </fwb-table-cell>
                                    <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                                    <fwb-table-cell v-for="n in item.nilaiNormal" :key="item.id">
                                        <div class="w-full flex justify-center">
                                            {{ n.S.toFixed(2) }}
                                        </div>
                                    </fwb-table-cell>
                                    <fwb-table-cell>{{ item.S.toFixed(2) }}</fwb-table-cell>
                                    <fwb-table-cell>{{ item.R.toFixed(2) }}</fwb-table-cell>
                                </fwb-table-row>
                                <fwb-table-row>
                                    <fwb-table-cell colspan="6">Min</fwb-table-cell>
                                    <fwb-table-cell>{{ sMin.toFixed(2) }}</fwb-table-cell>
                                    <fwb-table-cell>{{ rMin.toFixed(2) }}</fwb-table-cell>
                                </fwb-table-row>
                                <fwb-table-row>
                                    <fwb-table-cell colspan="6">Max</fwb-table-cell>
                                    <fwb-table-cell>{{ sMax.toFixed(2) }}</fwb-table-cell>
                                    <fwb-table-cell>{{ rMax.toFixed(2) }}</fwb-table-cell>
                                </fwb-table-row>

                            </fwb-table-body>
                        </fwb-table>


                        <FwbHeading tag="h5" class="my-3 mt-10">Hasil Perhitungan</FwbHeading>
                        <fwb-table>
                            <fwb-table-head>
                                <fwb-table-head-cell>No</fwb-table-head-cell>
                                <fwb-table-head-cell>Nama</fwb-table-head-cell>
                                <fwb-table-head-cell>Jenis Kelamin</fwb-table-head-cell>
                                <fwb-table-head-cell class="text-center">Nilai Q</fwb-table-head-cell>
                                <fwb-table-head-cell class="text-center">Ranking</fwb-table-head-cell>

                            </fwb-table-head>
                            <fwb-table-body>
                                <fwb-table-row v-for="(item, index) in dataAlternatif" :key="index">
                                    <fwb-table-cell>{{ index + 1 }} </fwb-table-cell>
                                    <fwb-table-cell>{{ item.nama }}</fwb-table-cell>
                                    <fwb-table-cell>{{ item.jeniskelamin == 'P' ? 'Perempuan' : 'Laki-laki'
                                        }}</fwb-table-cell>
                                    <fwb-table-cell>
                                        <div class="w-full flex justify-center">
                                            {{ item.Q.toFixed(2) }}
                                        </div>
                                    </fwb-table-cell>
                                    <fwb-table-cell>
                                        <div class="w-full flex justify-center">
                                            {{ ranking[index] }}
                                        </div>
                                    </fwb-table-cell>
                                </fwb-table-row>
                            </fwb-table-body>
                        </fwb-table>

                    </div>
                </template>
                <template #footer>

                </template>
            </fwb-modal>
        </div>
    </AuthenticatedLayout>


    <div class="print">
        <div class="w-full flex justify-center flex-col items-center my-5">
            <h1>HASIL SELEKSI PEMBERIAN REWARD</h1>
            <h1>BAGI KARYAWAN BENGKEL TOM SPEED MOTOR</h1>
            <h1>PERIODE {{ periodeAktif.nama }}</h1>
        </div>
        <table class="table-auto w-full text-sm">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-300 text-left">No</th>
                    <th class="p-2 border border-gray-300 text-left">Nama</th>
                    <th class="p-2 border border-gray-300 text-left">Jenis Kelamin</th>
                    <th class="p-2 border border-gray-300 text-center">Nilai Q</th>
                    <th class="p-2 border border-gray-300 text-center">Ranking</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataAlternatif" :key="index">
                    <td class=" text-sm p-2 border border-gray-300 text-left">{{ index + 1 }} </td>
                    <td class=" text-sm p-2 border border-gray-300 text-left">{{ item.nama }}</td>
                    <td class=" text-sm p-2 border border-gray-300 text-left">{{ item.jeniskelamin == 'P' ? 'Perempuan' :
                        'Laki-laki'
                        }}</td>
                    <td class=" text-sm p-2 border border-gray-300 text-left">
                        <div class="w-full flex justify-center">
                            {{ item.Q.toFixed(2) }}
                        </div>
                    </td>
                    <td class=" text-sm p-2 border border-gray-300 text-left">
                        <div class="w-full flex justify-center">
                            {{ ranking[index] }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
