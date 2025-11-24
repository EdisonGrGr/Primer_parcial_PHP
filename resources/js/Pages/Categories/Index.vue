<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    categories: Object,
    filters: Object,
});

// Variable reactiva para el input de búsqueda
const search = ref(props.filters?.search || '');

// Watch para detectar cambios en el input y hacer la búsqueda
// Usamos debounce manual para evitar múltiples requests mientras el usuario escribe
let timeout = null;
watch(search, (value) => {
    // Cancelar búsqueda anterior si existe
    if (timeout) clearTimeout(timeout);
    
    // Esperar 500ms después de que el usuario deje de escribir
    timeout = setTimeout(() => {
        // Realizar búsqueda en el backend usando Inertia
        router.get('/categories', 
            { search: value }, 
            { 
                preserveState: true,  // Mantener el estado del componente
                replace: true,        // Reemplazar la URL en lugar de agregar a historial
            }
        );
    }, 500);
});

// Control del modal de confirmación de eliminación
const showDeleteModal = ref(false);
const categoryToDelete = ref(null);

/**
 * CONFIRMACIÓN DE ELIMINACIÓN
 * Muestra modal para confirmar antes de eliminar
 */
const confirmDelete = (category) => {
    categoryToDelete.value = category;
    showDeleteModal.value = true;
};

/**
 * ELIMINAR CATEGORÍA
 * Consume el endpoint destroy del controlador web
 * Usa método DELETE de Inertia
 * Refresca el listado sin recargar la página completa
 */
const deleteCategory = () => {
    if (!categoryToDelete.value) return;
    
    router.delete(route('categories.destroy', categoryToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            categoryToDelete.value = null;
            // El mensaje de éxito se mostrará mediante flash message
        },
        onError: () => {
            showDeleteModal.value = false;
            // El mensaje de error se mostrará mediante flash message
        },
    });
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    categoryToDelete.value = null;
};
</script>

<template>
    <AppLayout title="Categorías">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Categorías
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Header con buscador -->
                        <div class="mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Listado de Categorías
                                </h3>
                                
                                <div class="flex items-center gap-3">
                                    <!-- Buscador por nombre -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input
                                            v-model="search"
                                            type="text"
                                            placeholder="Buscar por nombre..."
                                            class="block w-full sm:w-64 pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                    
                                    <!-- Botón crear categoría -->
                                    <Link
                                        :href="route('categories.create')"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nueva Categoría
                                    </Link>
                                </div>
                            </div>
                            
                            <!-- Indicador de búsqueda activa -->
                            <div v-if="filters?.search" class="mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filtrado por: "{{ filters.search }}"
                                    <button
                                        @click="search = ''"
                                        class="ml-2 inline-flex items-center justify-center w-4 h-4 text-indigo-600 dark:text-indigo-300 hover:text-indigo-800 dark:hover:text-indigo-100"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Tabla de categorías -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Prioridad
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Descuento (%)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Vehículos
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Fecha Creación
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ category.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span v-if="category.estado" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Activo
                                            </span>
                                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Inactivo
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 font-semibold">
                                                {{ category.priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">
                                            <span class="font-semibold text-green-600 dark:text-green-400">
                                                {{ category.discount_percentage }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                            {{ category.description || 'Sin descripción' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ category.cars_count || 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ new Date(category.created_date).toLocaleDateString('es-ES') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- Botón Ver Detalle -->
                                                <Link
                                                    :href="route('categories.show', category.id)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors duration-150"
                                                    title="Ver detalle"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver
                                                </Link>
                                                
                                                <!-- Botón Editar -->
                                                <Link
                                                    :href="route('categories.edit', category.id)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150"
                                                    title="Editar categoría"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Editar
                                                </Link>
                                                
                                                <!-- Botón Eliminar -->
                                                <button
                                                    @click="confirmDelete(category)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors duration-150"
                                                    title="Eliminar categoría"
                                                >
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="categories.data.length === 0">
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No hay categorías disponibles
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div v-if="categories.links.length > 3" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Mostrando 
                                <span class="font-medium">{{ categories.from }}</span>
                                a
                                <span class="font-medium">{{ categories.to }}</span>
                                de
                                <span class="font-medium">{{ categories.total }}</span>
                                resultados
                            </div>
                            <div class="flex space-x-2">
                                <Link
                                    v-for="(link, index) in categories.links"
                                    :key="index"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        link.active 
                                            ? 'bg-indigo-600 text-white' 
                                            : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600',
                                        !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                    ]"
                                    :preserve-scroll="true"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal de Confirmación de Eliminación -->
        <ConfirmationModal :show="showDeleteModal" @close="cancelDelete">
            <template #title>
                Eliminar Categoría
            </template>

            <template #content>
                ¿Está seguro de que desea eliminar la categoría "<strong>{{ categoryToDelete?.name }}</strong>"? 
                Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <button
                    @click="cancelDelete"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 mr-2 transition-colors duration-150"
                >
                    Cancelar
                </button>
                <button
                    @click="deleteCategory"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-150"
                >
                    Eliminar
                </button>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
