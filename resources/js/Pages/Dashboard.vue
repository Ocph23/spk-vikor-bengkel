<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Dashboardx" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboardx</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-10 bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center items-center">
                    <img class=" w-36 h-36" src="../../../public/logobaru.png" alt="logo" />
                    <div class="ml-10">
                        <h1>APLIKASI SISTEM PENDUKUNG KEPUTUSAN SELEKSI</h1>
                        <h1>PEMBERIAN REWARD BAGI KARYAWAN MENGGUNAKAN METODE VIKOR</h1>
                        <h1>PADA BENGKEL TOM'S SPEED MOTOR</h1>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
