<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success'); // success, error, warning, info

// Computed para obtener los mensajes flash
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

// Watch para mostrar mensajes cuando cambien
watch([flashSuccess, flashError], ([success, error]) => {
    if (success) {
        showMessage(success, 'success');
    } else if (error) {
        showMessage(error, 'error');
    }
});

const showMessage = (msg, msgType) => {
    message.value = msg;
    type.value = msgType;
    show.value = true;
    
    // Auto ocultar después de 5 segundos
    setTimeout(() => {
        show.value = false;
    }, 5000);
};

const close = () => {
    show.value = false;
};
</script>

<template>
    <div
        v-if="show"
        class="fixed top-4 right-4 z-50 max-w-md w-full animate-slide-in"
    >
        <div
            :class="[
                'p-4 rounded-lg shadow-lg flex items-start',
                type === 'success' ? 'bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700' : '',
                type === 'error' ? 'bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700' : '',
            ]"
        >
            <!-- Icono -->
            <div class="flex-shrink-0">
                <svg
                    v-if="type === 'success'"
                    class="w-6 h-6 text-green-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg
                    v-if="type === 'error'"
                    class="w-6 h-6 text-red-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <!-- Mensaje -->
            <div class="ml-3 flex-1">
                <p
                    :class="[
                        'text-sm font-medium',
                        type === 'success' ? 'text-green-800 dark:text-green-200' : '',
                        type === 'error' ? 'text-red-800 dark:text-red-200' : '',
                    ]"
                >
                    {{ message }}
                </p>
            </div>
            
            <!-- Botón cerrar -->
            <div class="ml-4 flex-shrink-0">
                <button
                    @click="close"
                    :class="[
                        'inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2',
                        type === 'success' ? 'text-green-500 hover:text-green-600 focus:ring-green-500' : '',
                        type === 'error' ? 'text-red-500 hover:text-red-600 focus:ring-red-500' : '',
                    ]"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
