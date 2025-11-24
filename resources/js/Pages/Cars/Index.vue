<template>
    <AppLayout title="Vehículos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestión de Vehículos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Barra de búsqueda y botón crear -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                            <div class="w-full sm:w-96">
                                <input
                                    v-model="searchQuery"
                                    @input="handleSearch"
                                    type="text"
                                    placeholder="Buscar por marca o modelo..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                            </div>
                            
                            <div class="flex gap-2 w-full sm:w-auto">
                                <select
                                    v-model="categoryFilter"
                                    @change="handleCategoryFilter"
                                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >
                                    <option value="">Todas las categorías</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                                
                                <Link
                                    :href="route('cars.create')"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition whitespace-nowrap"
                                >
                                    + Nuevo Vehículo
                                </Link>
                            </div>
                        </div>

                        <!-- Tabla de vehículos -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Marca
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Modelo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Año
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Precio
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Color
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Categoría
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="car in cars.data" :key="car.id_car" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ car.id_car }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ car.car_make }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ car.car_model }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ car.car_year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            ${{ formatPrice(car.car_price) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <div class="flex items-center gap-2">
                                                <div :style="{ backgroundColor: car.color }" class="w-4 h-4 rounded-full border border-gray-300"></div>
                                                {{ car.color }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ car.category?.name || 'Sin categoría' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="car.car_status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Disponible
                                            </span>
                                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                No disponible
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <Link
                                                    :href="route('cars.show', car.id_car)"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                >
                                                    Ver
                                                </Link>
                                                <Link
                                                    :href="route('cars.edit', car.id_car)"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="confirmDelete(car)"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Mostrando {{ cars.from }} a {{ cars.to }} de {{ cars.total }} vehículos
                            </div>
                            <div class="flex gap-2">
                                <Link
                                    v-for="link in cars.links"
                                    :key="link.label"
                                    :href="link.url"
                                    v-html="link.label"
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        link.active
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600',
                                        !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                    :disabled="!link.url"
                                    preserve-scroll
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <ConfirmationModal :show="confirmingDeletion" @close="closeModal">
            <template #title>
                Eliminar Vehículo
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar el vehículo "{{ carToDelete?.car_make }} {{ carToDelete?.car_model }}"? Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <button
                    @click="closeModal"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 mr-2"
                >
                    Cancelar
                </button>
                <button
                    @click="deleteCar"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                >
                    Eliminar
                </button>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    cars: Object,
    categories: Array,
    filters: Object,
});

const searchQuery = ref(props.filters.search || '');
const categoryFilter = ref(props.filters.category || '');
const confirmingDeletion = ref(false);
const carToDelete = ref(null);

let searchTimeout = null;

const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('cars.index'), {
            search: searchQuery.value,
            category: categoryFilter.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

const handleCategoryFilter = () => {
    router.get(route('cars.index'), {
        search: searchQuery.value,
        category: categoryFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const confirmDelete = (car) => {
    carToDelete.value = car;
    confirmingDeletion.value = true;
};

const closeModal = () => {
    confirmingDeletion.value = false;
    carToDelete.value = null;
};

const deleteCar = () => {
    if (carToDelete.value) {
        router.delete(route('cars.destroy', carToDelete.value.id_car), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(price);
};
</script>
