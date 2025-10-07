# 🚀 RUTAS PARA PRUEBAS EN POSTMAN

## 📋 ÍNDICE DE ENDPOINTS

### 🚗 **CARS API RESOURCE (CRUD Completo)**
| Método | URL | Descripción | Datos Requeridos |
|--------|-----|-------------|------------------|
| `GET` | `http://localhost:8000/api/cars` | Listar todos los autos | Ninguno |
| `POST` | `http://localhost:8000/api/cars` | Crear nuevo auto | JSON Body (ver ejemplo) |
| `GET` | `http://localhost:8000/api/cars/{id}` | Mostrar auto específico | ID en URL |
| `PUT` | `http://localhost:8000/api/cars/{id}` | Actualizar auto completo | JSON Body + ID |
| `PATCH` | `http://localhost:8000/api/cars/{id}` | Actualizar auto parcial | JSON Body + ID |
| `DELETE` | `http://localhost:8000/api/cars/{id}` | Eliminar auto | ID en URL |

### 📁 **CATEGORIES API RESOURCE (CRUD Completo)**
| Método | URL | Descripción | Datos Requeridos |
|--------|-----|-------------|------------------|
| `GET` | `http://localhost:8000/api/categories` | Listar todas las categorías | Ninguno |
| `POST` | `http://localhost:8000/api/categories` | Crear nueva categoría | JSON Body (ver ejemplo) |
| `GET` | `http://localhost:8000/api/categories/{id}` | Mostrar categoría específica | ID en URL |
| `PUT` | `http://localhost:8000/api/categories/{id}` | Actualizar categoría completa | JSON Body + ID |
| `PATCH` | `http://localhost:8000/api/categories/{id}` | Actualizar categoría parcial | JSON Body + ID |
| `DELETE` | `http://localhost:8000/api/categories/{id}` | Eliminar categoría | ID en URL |

### ⭐ **RUTAS PERSONALIZADAS (Punto 8.1)**
| Método | URL | Descripción | Datos Requeridos |
|--------|-----|-------------|------------------|
| `GET` | `http://localhost:8000/api/categories/active` | **PRINCIPAL**: Categorías activas con autos relacionados | Ninguno |
| `GET` | `http://localhost:8000/api/categories/active/with-available-cars` | Categorías con autos disponibles | Ninguno |
| `GET` | `http://localhost:8000/api/categories/active/paginated` | Categorías activas paginadas | Ninguno |

### 👤 **AUTENTICACIÓN**
| Método | URL | Descripción | Datos Requeridos |
|--------|-----|-------------|------------------|
| `GET` | `http://localhost:8000/api/user` | Obtener usuario autenticado | Token Sanctum |

---

## 📝 EJEMPLOS DE PETICIONES

### 🚗 **CARS - Ejemplos JSON**

#### ✅ **POST /api/cars** (Crear Auto)
```json
{
    "car_make": "Toyota",
    "car_model": "Corolla",
    "car_year": 2023,
    "car_price": 25000.50,
    "car_status": "available",
    "category_id": 1,
    "codigo_barras": "CAR001234567890"
}
```

#### ✅ **PUT /api/cars/{id}** (Actualizar Auto Completo)
```json
{
    "car_make": "Honda",
    "car_model": "Civic",
    "car_year": 2024,
    "car_price": 28000.00,
    "car_status": "sold",
    "category_id": 2,
    "codigo_barras": "CAR001234567891"
}
```

#### ✅ **PATCH /api/cars/{id}** (Actualizar Auto Parcial)
```json
{
    "car_price": 26000.00,
    "car_status": "available"
}
```

### 📁 **CATEGORIES - Ejemplos JSON**

#### ✅ **POST /api/categories** (Crear Categoría)
```json
{
    "name": "SUV Premium",
    "description": "Vehículos SUV de alta gama con tecnología avanzada",
    "priority": 5,
    "discount_percentage": 15.5,
    "estado": true,
    "created_date": "2025-10-06"
}
```

#### ✅ **PUT /api/categories/{id}** (Actualizar Categoría Completa)
```json
{
    "name": "Deportivos",
    "description": "Autos deportivos de alto rendimiento",
    "priority": 8,
    "discount_percentage": 10.0,
    "estado": true,
    "created_date": "2025-10-06"
}
```

#### ✅ **PATCH /api/categories/{id}** (Actualizar Categoría Parcial)
```json
{
    "discount_percentage": 20.0,
    "estado": false
}
```

---

## 🔧 CONFIGURACIÓN EN POSTMAN

### 📋 **Headers Requeridos**
```
Content-Type: application/json
Accept: application/json
```

### 🔐 **Para rutas con autenticación**
```
Authorization: Bearer {token}
```

---

## 🧪 SECUENCIA DE PRUEBAS RECOMENDADA

### 1️⃣ **VERIFICACIÓN INICIAL**
```
GET http://localhost:8000/api/categories
GET http://localhost:8000/api/cars
```

### 2️⃣ **CREAR DATOS DE PRUEBA**
```
POST http://localhost:8000/api/categories (crear categoría)
POST http://localhost:8000/api/cars (crear auto con category_id válido)
```

### 3️⃣ **PROBAR FUNCIONALIDADES ESPECÍFICAS**
```
GET http://localhost:8000/api/categories/{id} (mostrar con relaciones)
GET http://localhost:8000/api/categories/active (Punto 8.1 - Principal)
```

### 4️⃣ **PROBAR ACTUALIZACIONES**
```
PATCH http://localhost:8000/api/cars/{id}
PUT http://localhost:8000/api/categories/{id}
```

### 5️⃣ **PRUEBAS AVANZADAS (Punto 8.1)**
```
GET http://localhost:8000/api/categories/active/with-available-cars
GET http://localhost:8000/api/categories/active/paginated
```

---

## ⚠️ NOTAS IMPORTANTES

### 🎯 **Validaciones Automáticas**
- **Cars**: `category_id` debe existir en la tabla categories
- **Categories**: `estado` debe ser boolean (true/false)
- **Códigos de barras**: Deben ser únicos

### 📊 **Respuestas Esperadas**
- **200**: Operación exitosa
- **201**: Recurso creado exitosamente
- **422**: Errores de validación
- **404**: Recurso no encontrado

### 🔍 **URLs de Ejemplo con IDs Reales**
Para obtener IDs válidos, primero ejecuta:
```
GET http://localhost:8000/api/categories
GET http://localhost:8000/api/cars
```

Luego usa los IDs obtenidos en rutas como:
```
GET http://localhost:8000/api/categories/21
GET http://localhost:8000/api/cars/1
```

---

## 🚀 **ENDPOINT DESTACADO - Punto 8.1**

### ⭐ **MÁS IMPORTANTE PARA PROBAR:**
```
GET http://localhost:8000/api/categories/active
```

**Esta ruta devuelve:**
- ✅ Solo categorías con `estado = true`
- ✅ Cada categoría incluye sus autos relacionados
- ✅ Conteo de autos por categoría
- ✅ Información completa usando Resources

**Respuesta esperada:**
```json
[
    {
        "id": 21,
        "name": "Convertible",
        "description": "...",
        "estado": true,
        "cars": [...],
        "cars_count": 2
    }
]
```