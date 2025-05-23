<script setup lang="ts">
import { ref, computed } from 'vue'
import QRCode from 'qrcode'

const open = ref(false)
const qrCodeData = ref('')

const url = computed(() => window.location.origin)

function openDialog() {
    QRCode.toDataURL(url.value, { width: 200, margin: 2 }, (err, dataUrl) => {
        if (!err) qrCodeData.value = dataUrl
    })
    open.value = true
}

defineExpose({ openDialog })
</script>

<template>
    <v-dialog v-model="open" max-width="300">
        <v-card>
            <v-card-title class="text-h6">Escanea este código QR</v-card-title>
            <v-card-text class="d-flex justify-center">
                <img :src="qrCodeData" alt="QR Code" v-if="qrCodeData" />
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn @click="open = false">Cerrar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
