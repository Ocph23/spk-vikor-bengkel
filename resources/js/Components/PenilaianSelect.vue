<template>
    <FwbSelect :disabled="!periode.status" :options="subkriteriaOption"  v-model="model.sub_kriteria_id" placeholder="Pilih Sub Kriteria">
    </FwbSelect>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import { FwbSelect } from 'flowbite-vue'

const props = defineProps({
    kriteria: { type: {} as any, required: true },
    periode: { type: {} as any, required: true },
    model: { type: {} as any, required: true },
});

const subkriteriaOption = props.kriteria.subkriteria.map((sub: any) => {
    return {
        name: `${sub.nama}`,
        value: sub.id
    }
});


</script>