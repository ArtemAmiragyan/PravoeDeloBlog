<template>
  <div v-if="!store.isGlobalLoading" class="header-profile-section">
    <div class="header-profile-section__account">
      <router-link v-if="!store.auth.isAuthenticated" class="header-profile-section__sign-in"
                   :to="{name: 'login'}">
        Sign in
      </router-link>
      <router-link v-else :to="{name: 'profile'}">
        {{ store.auth.user.name }}
      </router-link>

      <div class="header-profile-section__avatar-thumbnail">
        <img
          v-if="false"
          class="header-profile-section__avatar-thumbnail-image"
          src=""
          alt="profile image"
        >
        <div v-else class="header-profile-section__default-avatar-thumbnail">
          <UserSvg/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import UserSvg from '@/svgs/header/UserSvg.vue';
import { useStore } from '@/stores';

const store = useStore();
</script>
