<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import HeaderTitle from "@/components/ui/HeaderTitle.vue";

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const type = ref('password')

async function submitLogin() {
    try {
        await auth.login(email.value, password.value)
        window.location.href = '/'
    } catch {
        // el error ya se maneja en el store
    }
}
</script>

<template>
    <v-container>
        <div class="d-flex justify-center my-4">
            <v-img src="/img/seonthebeach_logo.png" max-width="50%" min-width="320"></v-img>
        </div>

        <HeaderTitle text="Iniciar sessión" />

        <v-form @submit.prevent="submitLogin">
            <v-row>
                <v-col cols="12">
                    <v-text-field autocomplete="email" variant="solo" v-model="email" placeholder="Email" hide-details type="email" inputmode="email" />
                </v-col>
                <v-col cols="12">
                    <v-text-field autocomplete="password" variant="solo" v-model="password" placeholder="Contraseña" :type="type">
                        <template #append-inner>
                            <v-icon @click="type = (type === 'password' ? 'text' : 'password')">{{ type === 'password' ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
                        </template>
                    </v-text-field>
                </v-col>
            </v-row>
            <v-btn block size="large" type="submit" color="info" :loading="auth.loading">Entrar</v-btn>
            <v-alert v-if="auth.error" type="error" class="mt-4">{{ auth.error }}</v-alert>
        </v-form>
    </v-container>
</template>
