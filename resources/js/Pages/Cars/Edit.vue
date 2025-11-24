<template>
    <AppLayout title="Editar Vehículo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Vehículo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submitForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Marca -->
                                <div>
                                    <label for="car_make" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Marca <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="car_make"
                                        v-model="form.car_make"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.car_make }"
                                        maxlength="100"
                                    />
                                    <p v-if="errors.car_make" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.car_make }}
                                    </p>
                                </div>

                                <!-- Modelo -->
                                <div>
                                    <label for="car_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Modelo <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="car_model"
                                        v-model="form.car_model"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.car_model }"
                                        maxlength="100"
                                    />
                                    <p v-if="errors.car_model" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.car_model }}
                                    </p>
                                </div>

                                <!-- Año -->
                                <div>
                                    <label for="car_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Año <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="car_year"
                                        v-model.number="form.car_year"
                                        type="number"
                                        min="1900"
                                        :max="currentYear"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.car_year }"
                                    />
                                    <p v-if="errors.car_year" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.car_year }}
                                    </p>
                                </div>

                                <!-- Precio -->
                                <div>
                                    <label for="car_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Precio <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="car_price"
                                        v-model.number="form.car_price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.car_price }"
                                    />
                                    <p v-if="errors.car_price" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.car_price }}
                                    </p>
                                </div>

                                <!-- Color -->
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Color <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="color"
                                        v-model="form.color"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.color }"
                                        maxlength="50"
                                    />
                                    <p v-if="errors.color" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.color }}
                                    </p>
                                </div>

                                <!-- Categoría -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Categoría <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="category_id"
                                        v-model.number="form.category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        :class="{ 'border-red-500': errors.category_id }"
                                    >
                                        <option value="">Seleccione una categoría</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="errors.category_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ errors.category_id }}
                                    </p>
                                </div>

                                <!-- Disponible -->
                                <div class="flex items-center">
                                    <input
                                        id="car_status"
                                        v-model="form.car_status"
                                        type="checkbox"
                                        class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    />
                                    <label for="car_status" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Vehículo disponible
                                    </label>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="mt-6 flex justify-end gap-3">
                                <Link
                                    :href="route('cars.index')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="processing"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                                >
                                    {{ processing ? 'Guardando...' : 'Actualizar' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    car: Object,
    categories: Array,
});

const currentYear = new Date().getFullYear();

const form = ref({
    car_make: props.car.car_make,
    car_model: props.car.car_model,
    car_year: props.car.car_year,
    car_price: props.car.car_price,
    color: props.car.color,
    category_id: props.car.category_id,
    car_status: props.car.car_status,
});

const errors = ref({});
const processing = ref(false);

const validateForm = () => {
    errors.value = {};

    if (!form.value.car_make || form.value.car_make.trim() === '') {
        errors.value.car_make = 'La marca es obligatoria.';
    } else if (form.value.car_make.length > 100) {
        errors.value.car_make = 'La marca no debe exceder 100 caracteres.';
    }

    if (!form.value.car_model || form.value.car_model.trim() === '') {
        errors.value.car_model = 'El modelo es obligatorio.';
    } else if (form.value.car_model.length > 100) {
        errors.value.car_model = 'El modelo no debe exceder 100 caracteres.';
    }

    if (!form.value.car_year) {
        errors.value.car_year = 'El año es obligatorio.';
    } else if (form.value.car_year < 1900 || form.value.car_year > currentYear) {
        errors.value.car_year = `El año debe estar entre 1900 y ${currentYear}.`;
    }

    if (form.value.car_price === null || form.value.car_price === '') {
        errors.value.car_price = 'El precio es obligatorio.';
    } else if (form.value.car_price < 0) {
        errors.value.car_price = 'El precio debe ser mayor o igual a 0.';
    }

    if (!form.value.color || form.value.color.trim() === '') {
        errors.value.color = 'El color es obligatorio.';
    } else if (form.value.color.length > 50) {
        errors.value.color = 'El color no debe exceder 50 caracteres.';
    }

    if (!form.value.category_id) {
        errors.value.category_id = 'La categoría es obligatoria.';
    }

    return Object.keys(errors.value).length === 0;
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    processing.value = true;

    router.put(route('cars.update', props.car.id_car), form.value, {
        onError: (backendErrors) => {
            errors.value = backendErrors;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>
