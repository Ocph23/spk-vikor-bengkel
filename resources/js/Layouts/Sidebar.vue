<!-- eslint-disable vue/multi-word-component-names -->
<template>
  <div class="sticky top-0 z-10">
    <fwb-sidebar>
      <fwb-sidebar-item  @click="goto('/dashboard')">
        <template #icon>
          <svg
            class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
            <path
              d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
          </svg>
        </template>
        <template #default class="cursor-default">Dashboard</template>
      </fwb-sidebar-item>

      <FwbSidebarItem @click="goto('/kriteria')" link="">
        <template #icon>
          <VTListIcon></VTListIcon>
        </template>
        <template #default class="cursor-default">Kriteria</template>
      </FwbSidebarItem>
      <fwb-sidebar-item @click="goto('/kriteria/bobot')">
        <template #icon>
          <VTEcosystemIcon></VTEcosystemIcon>
      
        </template>
        <template #default class="cursor-default">Bobot Kriteria</template>
        <template #suffix> </template>
      </fwb-sidebar-item>
      <fwb-sidebar-item @click="goto('/subkriteria')">
        <template #icon>
         <VTSubMenuIcon></VTSubMenuIcon>
        </template>
        <template #default class="cursor-default">Sub Kriteria</template>
        <template #suffix> </template>
      </fwb-sidebar-item>
      <fwb-sidebar-item @click="goto('/periode')">
        <template #icon>
         <VTCalendarIcon></VTCalendarIcon>
        </template>
        <template #default class="cursor-default">Periode</template>
        <template #suffix> </template>
      </fwb-sidebar-item>
      <fwb-sidebar-item @click="goto('/alternatif')">
        <template #icon>
          <VTProfileIcon></VTProfileIcon>
        </template>
        <template #default class="cursor-default">Karyawan</template>
        <template #suffix> </template>
      </fwb-sidebar-item>
     
      <fwb-sidebar-item @click="goto('/penilaian')">
        <template #icon>
          <IconCommunity></IconCommunity>
        </template>
        <template #default class="cursor-default">Penilaian</template>
        <template #suffix> </template>
      </fwb-sidebar-item>
     
    </fwb-sidebar>
  </div>
</template>
<script setup lang="ts">
import { FwbSidebar, FwbSidebarItem, FwbSidebarDropdownItem } from 'flowbite-vue';
import { VTCalendarIcon, VTCheckIcon, VTDosenIcon, VTEcosystemIcon, VTProfileIcon, VTRoleIcon, VTSubMenuIcon, VTToolingIcon, VTListIcon } from "@/icons";
import IconCommunity from '@/icons/IconCommunity.vue';

function goto(params:string) {
  window.location.href = params;
}

</script>
