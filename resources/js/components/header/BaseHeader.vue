<template>
  <header>
    <div class="container">
      <router-link class="header__logo-wrapper" :to="{name: 'home'}">
        <img class="header__logo" alt="logo" src="/assets/images/logo.png"/>
      </router-link>

      <BaseHeaderFilters/>

      <BaseHeaderProfileSection/>
    </div>
  </header>
</template>

<script setup lang="ts">
import BaseHeaderProfileSection from '@/components/header/BaseHeaderProfileSection.vue';
import BaseHeaderFilters from '@/components/header/BaseHeaderFilters.vue';
</script>
