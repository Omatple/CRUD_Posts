
# **CRUD_Posts**

## Tabla de Contenidos

- [Introducción](#introducción)
- [Características](#características)
- [Tecnologías Usadas](#tecnologías-usadas)
- [Instrucciones de Configuración](#instrucciones-de-configuración)
- [Uso](#uso)
- [Capturas de Pantalla](#capturas-de-pantalla)
- [Estructura del Código](#estructura-del-código)
- [Contribución](#contribución)
- [Licencia](#licencia)

---

## Introducción

**CRUD_Posts** es una plataforma basada en la web que permite a los usuarios crear, actualizar, eliminar y ver publicaciones categorizadas. Los usuarios pueden interactuar con las publicaciones asignándoles estados como "Borrador" o "Publicado". La aplicación integra autenticación de usuarios y manejo de errores para garantizar una experiencia fluida.

Este sistema es ideal para demostrar operaciones CRUD y las mejores prácticas en desarrollo PHP, incluyendo validación, manejo de sesiones y gestión de bases de datos.

---

## Características

### Características para Invitados

- Visualización de publicaciones públicas categorizadas por etiquetas.
- Navegación hacia formularios de inicio de sesión y creación de publicaciones (restringido para usuarios no autenticados).

### Características para Usuarios Registrados

- Inicio/cierre de sesión con bloqueo de cuenta tras múltiples intentos fallidos.
- Crear nuevas publicaciones con título, contenido, selección de categoría y estado (Publicado/Borrador).
- Editar publicaciones existentes con validación de títulos únicos y longitud del contenido.
- Eliminar publicaciones con un cuadro de confirmación.
- Alternar el estado de las publicaciones (Publicado/Borrador).
- Ver publicaciones agrupadas por categorías.

---

## Tecnologías Usadas

### Frontend

- **HTML5** y **CSS3** para estructura y estilo.
- **Tailwind CSS** para diseño responsivo.
- **SweetAlert2** para alertas y modales interactivos.
- **Font Awesome** para íconos.

### Backend

- **PHP 8.1+** para la lógica del lado del servidor.
- **Faker Library** para generar datos ficticios.

### Base de Datos

- **MySQL** para almacenamiento persistente de datos.

---

## Instrucciones de Configuración

### Requisitos Previos

- PHP 8.1 o superior.
- MySQL.
- Composer.
- Un servidor web (Apache).

### Pasos

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/Omatple/CRUD_Posts.git
   cd CRUD_Posts
   ```

2. **Instalar dependencias:**

   ```bash
   composer install
   ```

3. **Configurar la base de datos:**
   - Crear una nueva base de datos en MySQL.
   - Importar las tablas de la base de datos desde `sql/tables.sql`.

4. **Configurar el entorno:**
   - Renombrar `.env.example` a `.env`.
   - Actualizar las credenciales de la base de datos en el archivo `.env`:

     ```env
     HOST=localhost
     PORT=3306
     DBNAME=<tu_base_de_datos>
     USERNAME=<tu_usuario>
     PASSWORD=<tu_contraseña>
     ```

5. **Ejecutar la aplicación:**
   - Asegúrate de que Apache esté apuntando al directorio público (`public/`).
   - Visitar `http://localhost` en tu navegador.

6. **Generar datos ficticios (opcional):**
   - Ejecutar el script para poblar la base de datos con datos de prueba.

     ```bash
     php scripts/seedFakeEntries.php
     ```

---

## Uso

### Invitado

- Navegar a la página principal para ver todas las publicaciones públicas categorizadas.
- Hacer clic en "Login" para acceder a funciones específicas de usuarios.

### Usuario Registrado

1. **Iniciar Sesión:**
   - Introducir el correo electrónico y la contraseña para acceder a tu cuenta.
   - Después de tres intentos fallidos, la cuenta se bloqueará temporalmente.

2. **Crear una Publicación:**
   - Navegar a la página "Nueva Publicación".
   - Completar los campos del formulario: Título, Contenido, Categoría y Estado.
   - Enviar el formulario.

3. **Editar una Publicación:**
   - Hacer clic en el ícono de edición de una publicación.
   - Modificar los campos y actualizar la publicación.

4. **Eliminar una Publicación:**
   - Hacer clic en el ícono de eliminación de una publicación.
   - Confirmar la acción en el modal.

5. **Alternar el Estado de una Publicación:**
   - Hacer clic en el botón de estado (Publicado/Borrador) para cambiar el estado de la publicación.

---

## Capturas de Pantalla

### Página Principal (Vista Invitado)

![Home Guest View](/public/screenshots/Home%20(Guest%20View).png)

### Página de Nueva Publicación (Vista Invitado - Restringido)

![New Post Guest View](/public/screenshots/Create%20Post%20(Guest%20Access).png)

### Página de Inicio de Sesión

![Login Page](public/screenshots/User%20Login.png)

### Inicio de Sesión con Errores de Validación

![Login Errors](public/screenshots/Login%20-%20Failed%20Attempts.png)

### Página Principal (Vista Usuario)

![Home User View](public/screenshots/User%20Dashboard%20-%20Posts%20Overview.png)

### Crear Nueva Publicación (Vista Usuario)

![New Post User View](public/screenshots/Create%20New%20Post%20-%20User%20View.png)

### Actualizar Publicación

![Update Post](public/screenshots/Update%20Post%20-%20User%20View.png)

### Mensaje de Éxito (Publicación Eliminada)

![Success Alert](public/screenshots/Post%20Deletion%20Confirmation%20-%20User%20View.png)

### Errores de Validación (Crear Publicación)

![Validation Errors - Create Post](public/screenshots/Create%20Post%20Form%20-%20Validation%20Errors.png)

### Errores de Validación (Actualizar Publicación)

![Validation Errors - Update Post](/public/screenshots/Update%20Post%20Form%20-%20Validation%20Errors.png)

---

## Estructura del Código

### Carpetas

- **`App/Database`:** Contiene clases relacionadas con la base de datos para gestionar usuarios, categorías y publicaciones.
- **`App/Utils`:** Contiene clases utilitarias para validación, navegación y constantes.
- **`public/`:** Archivos accesibles públicamente, como `index.php`.
- **`public/screenshots/`:** Carpeta que contiene las capturas de pantalla para la documentación.

### Clases Principales

1. **`User`:** Maneja operaciones específicas de usuarios, como el inicio de sesión y la generación de datos ficticios.
2. **`Post`:** Administra las operaciones CRUD de publicaciones y actualizaciones de estado.
3. **`Category`:** Maneja operaciones relacionadas con categorías, como la obtención y creación de categorías.

---

## Estructura del proyecto

```
CRUD_Posts/
├── sql/
│   ├── tables.sql                    # Esquema de las tablas de la base de datos
├── public/
│   ├── img/
│   │   ├── iesalandalus.png          # Logotipo utilizado en la interfaz
│   ├── screenshots/                  # Capturas de pantalla del proyecto
│   │   ├── Home (Guest View).png
│   │   ├── Create Post (Guest Access).png
│   │   ├── Create Post Form - Validation Errors.png
│   │   ├── User Login.png
│   │   ├── Login - Failed Attempts.png
│   │   ├── User Dashboard - Posts Overview.png
│   │   ├── Create New Post - User View.png
│   │   ├── Update Post - User View.png
│   │   ├── Post Deletion Confirmation - User View.png
│   │   ├── Update Post Form - Validation Errors.png
│   ├── delete.php                    # Página para eliminar una publicación
│   ├── index.php                     # Página principal de la aplicación
│   ├── login.php                     # Página de inicio de sesión
│   ├── logout.php                    # Cierre de sesión
│   ├── new.php                       # Página para crear una nueva publicación
│   ├── update.php                    # Página para actualizar una publicación
├── scripts/
│   ├── seedFakeEntries.php           # Script para generar datos ficticios
├── src/
│   ├── Database/
│   │   ├── Category.php              # Clase para gestionar categorías
│   │   ├── Connection.php            # Clase para manejar la conexión a la base de datos
│   │   ├── Post.php                  # Clase para gestionar publicaciones
│   │   ├── QueryExecutor.php         # Clase base para ejecutar consultas SQL
│   │   ├── User.php                  # Clase para gestionar usuarios
│   ├── Utils/
│       ├── AlertNotification.php     # Clase para mostrar mensajes con SweetAlert2
│       ├── AppConstants.php          # Constantes del proyecto
│       ├── BookCategories.php        # Enumeración de categorías de libros
│       ├── CookieHelper.php          # Clase para manejo de cookies
│       ├── FormInputValidator.php    # Clase para validar entradas del formulario
│       ├── PageNavigation.php        # Clase para manejar la navegación
│       ├── PostStatus.php            # Enumeración de estados de las publicaciones
│       ├── PostValidator.php         # Clase para validar publicaciones
│       ├── SessionErrorHandler.php   # Clase para manejar errores de sesión
│       ├── UserValidator.php         # Clase para validar datos de usuarios
├── .env                              # Variables de entorno del proyecto
├── .gitignore                        # Archivos ignorados por Git
├── composer.json                     # Dependencias del proyecto
├── composer.lock                     # Versión bloqueada de dependencias
├── env.example                       # Archivo de ejemplo para configuración del entorno
├── LICENSE.txt                       # Licencia del proyecto
├── README.md                         # Documentación del proyecto
```
---

## Contribución

### Pasos

1. Realiza un fork del repositorio.
2. Crea una rama de características:

   ```bash
   git checkout -b feature/new-feature
   ```

3. Realiza tus cambios y haz commits:

   ```bash
   git commit -m "Agregada nueva funcionalidad"
   ```

4. Sube los cambios a tu fork:

   ```bash
   git push origin feature/new-feature
   ```

5. Envía un Pull Request para revisión.

---

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE.txt).
