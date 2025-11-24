<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
    cars: Object, // Relación cargada con with() desde el backend
});
</script>

<template>
    <AppLayout title="Detalle de Categoría">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Detalle de Categoría
                </h2>
                <Link
                    :href="route('categories.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Listado
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Información de la Categoría -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Información de la Categoría
                        </h3>

                        <!-- Grid de información -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ID -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    #{{ category.id }}
                                </dd>
                            </div>

                            <!-- Nombre -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ category.name }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                                <dd class="mt-1">
                                    <span v-if="category.estado" class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Activo
                                    </span>
                                    <span v-else class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Inactivo
                                    </span>
                                </dd>
                            </div>

                            <!-- Prioridad -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 font-bold text-lg">
                                        {{ category.priority }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Descuento -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descuento</dt>
                                <dd class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ category.discount_percentage }}%
                                </dd>
                            </div>

                            <!-- Fecha de Creación -->
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Creación</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ new Date(category.created_date).toLocaleDateString('es-ES', { 
                                        weekday: 'long', 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    }) }}
                                </dd>
                            </div>

                            <!-- Descripción (ocupa 2 columnas) -->
                            <div class="border-l-4 border-indigo-500 pl-4 md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                                <dd class="mt-1 text-base text-gray-900 dark:text-gray-100">
                                    {{ category.description || 'Sin descripción' }}
                                </dd>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                            <Link
                                :href="route('categories.edit', category.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar Categoría
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Vehículos Asociados (Relación cargada con with()) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Vehículos Asociados
                                <span class="ml-3 px-3 py-1 text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full">
                                    {{ cars.length }} {{ cars.length === 1 ? 'vehículo' : 'vehículos' }}
                                </span>
                            </h3>
                        </div>

                        <!-- Lista de vehículos -->
                        <div v-if="cars.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Marca
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Modelo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Año
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Precio
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Color
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Disponible
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="car in cars" :key="car.id_car" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            #{{ car.id_car }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ car.car_make }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ car.car_model }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ car.car_year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                                            ${{ parseFloat(car.car_price).toLocaleString('es-ES', { minimumFractionDigits: 2 }) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center">
                                                <div 
                                                    class="w-4 h-4 rounded-full mr-2 border border-gray-300 dark:border-gray-600"
                                                    :style="{ backgroundColor: car.color }"
                                                ></div>
                                                <span class="text-gray-900 dark:text-gray-100">{{ car.color }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span v-if="car.car_status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Disponible
                                            </span>
                                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                No disponible
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje cuando no hay vehículos -->
                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay vehículos asociados</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Esta categoría aún no tiene vehículos registrados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
