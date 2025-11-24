<template>
    <AppLayout title="Detalle del Vehículo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Vehículo
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Información del vehículo -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                Información General
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">ID</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ car.id_car }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Marca</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ car.car_make }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Modelo</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ car.car_model }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Año</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ car.car_year }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Precio</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">${{ formatPrice(car.car_price) }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Color</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div :style="{ backgroundColor: car.color }" class="w-6 h-6 rounded-full border border-gray-300"></div>
                                        <span class="text-gray-900 dark:text-gray-100">{{ car.color }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Categoría</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        <Link 
                                            v-if="category"
                                            :href="route('categories.show', category.id)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            {{ category.name }}
                                        </Link>
                                        <span v-else>Sin categoría</span>
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                                    <p class="mt-1">
                                        <span v-if="car.car_status" class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Disponible
                                        </span>
                                        <span v-else class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            No disponible
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de creación</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ formatDate(car.created_at) }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Última actualización</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ formatDate(car.updated_at) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la categoría -->
                        <div v-if="category" class="mb-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                Información de la Categoría
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ category.name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ category.priority }}</p>
                                </div>
                                
                                <div v-if="category.discount_percentage">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descuento</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ category.discount_percentage }}%</p>
                                </div>
                                
                                <div class="md:col-span-2 lg:col-span-3" v-if="category.description">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ category.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                            <Link
                                :href="route('cars.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                            >
                                ← Volver al listado
                            </Link>
                            
                            <div class="flex gap-3">
                                <Link
                                    :href="route('cars.edit', car.id_car)"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                                >
                                    Editar
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    car: Object,
    category: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(price);
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>
