<template>
    <div class="alert alert-success" role="alert">
        {{ userStore.name }}
        <br />
        {{ userStore.email }}
        <br />
        You are logged in successfully. 
        <RouterLink :to="{ name: 'auth.signout' }"
            ><i class="fas fa-sign-out-alt text-danger"></i
        ></RouterLink>
    </div>
</template>
<script setup>
import { useUserStore } from "@/stores/user";
const userStore = useUserStore();
</script>