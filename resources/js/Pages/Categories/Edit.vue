<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    description: props.category.description,
    priority: props.category.priority,
    discount_percentage: props.category.discount_percentage,
    estado: props.category.estado,
});

/**
 * VALIDACIÓN FRONTEND
 * Valida los campos antes de enviar al backend
 * Retorna true si pasa todas las validaciones
 */
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    let isValid = true;

    // Validar nombre (requerido, longitud entre 3 y 100 caracteres)
    if (!form.name || form.name.trim().length === 0) {
        errors.value.name = 'El nombre es requerido';
        isValid = false;
    } else if (form.name.length < 3) {
        errors.value.name = 'El nombre debe tener al menos 3 caracteres';
        isValid = false;
    } else if (form.name.length > 100) {
        errors.value.name = 'El nombre no puede exceder 100 caracteres';
        isValid = false;
    }

    // Validar prioridad (requerido, número entero positivo)
    if (form.priority === null || form.priority === '') {
        errors.value.priority = 'La prioridad es requerida';
        isValid = false;
    } else if (form.priority < 1) {
        errors.value.priority = 'La prioridad debe ser mayor a 0';
        isValid = false;
    }

    // Validar descuento (debe estar entre 0 y 100)
    if (form.discount_percentage === null || form.discount_percentage === '') {
        errors.value.discount_percentage = 'El descuento es requerido';
        isValid = false;
    } else if (form.discount_percentage < 0 || form.discount_percentage > 100) {
        errors.value.discount_percentage = 'El descuento debe estar entre 0 y 100';
        isValid = false;
    }

    // Validar descripción (opcional, pero si existe no debe exceder 500 caracteres)
    if (form.description && form.description.length > 500) {
        errors.value.description = 'La descripción no puede exceder 500 caracteres';
        isValid = false;
    }

    return isValid;
};

/**
 * SUBMIT DEL FORMULARIO
 * 1. Valida en frontend primero
 * 2. Si pasa, envía al endpoint update del backend
 * 3. Usa método PUT para actualizar
 * 4. Muestra mensajes de éxito o error
 */
const submit = () => {
    // Validar en frontend primero
    if (!validateForm()) {
        return;
    }

    // Enviar al backend usando Inertia con método PUT
    form.put(route('categories.update', props.category.id), {
        preserveScroll: true,
        onSuccess: () => {
            // El mensaje de éxito se mostrará mediante flash message
        },
        onError: (backendErrors) => {
            // Mostrar errores del backend
            errors.value = { ...errors.value, ...backendErrors };
        },
    });
};
</script>

<template>
    <AppLayout title="Editar Categoría">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Categoría
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <!-- Mensaje de información -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Modifique los campos necesarios. Los campos marcados con * son obligatorios.
                                </p>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.name || form.errors.name }"
                                placeholder="Ej: Sedanes"
                                maxlength="100"
                            />
                            <p v-if="errors.name || form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.name || form.errors.name }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Mínimo 3 caracteres, máximo 100 caracteres
                            </p>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descripción
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.description || form.errors.description }"
                                placeholder="Descripción detallada de la categoría..."
                                maxlength="500"
                            ></textarea>
                            <p v-if="errors.description || form.errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ errors.description || form.errors.description }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Opcional. Máximo 500 caracteres. {{ form.description?.length || 0 }}/500
                            </p>
                        </div>

                        <!-- Prioridad y Descuento en grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Prioridad -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Prioridad <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="priority"
                                    v-model.number="form.priority"
                                    type="number"
                                    min="1"
                                    class="block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500': errors.priority || form.errors.priority }"
                                />
                                <p v-if="errors.priority || form.errors.priority" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ errors.priority || form.errors.priority }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Número entero mayor a 0
                                </p>
                            </div>

                            <!-- Descuento -->
                            <div>
                                <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Descuento (%) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="discount_percentage"
                                    v-model.number="form.discount_percentage"
                                    type="number"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    class="block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    :class="{ 'border-red-500': errors.discount_percentage || form.errors.discount_percentage }"
                                />
                                <p v-if="errors.discount_percentage || form.errors.discount_percentage" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ errors.discount_percentage || form.errors.discount_percentage }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Entre 0 y 100
                                </p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input
                                    v-model="form.estado"
                                    type="checkbox"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                                />
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Categoría activa
                                </span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Solo las categorías activas serán visibles en el sistema
                            </p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a
                                :href="route('categories.index')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="form.processing" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </span>
                                <span v-else>Actualizar Categoría</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
