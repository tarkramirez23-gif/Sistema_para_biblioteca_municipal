# 📚 Sistema de Gestión — Biblioteca Municipal de Cajamarca

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white"/>
  <img src="https://img.shields.io/badge/Estado-En%20Desarrollo-green?style=for-the-badge"/>
</p>

Sistema web desarrollado para digitalizar y optimizar la gestión de la **Biblioteca Municipal de Cajamarca**, Perú. Permite administrar el catálogo de libros, los usuarios registrados, los préstamos activos y las reservas de material bibliográfico.

---

## 🗂️ Tabla de Contenidos

- [Características](#-características)
- [Tecnologías utilizadas](#-tecnologías-utilizadas)
- [Estructura del proyecto](#-estructura-del-proyecto)
- [Requisitos previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración de la base de datos](#-configuración-de-la-base-de-datos)
- [Uso](#-uso)
- [Capturas de pantalla](#-capturas-de-pantalla)
- [Autor](#-autor)

---

## ✅ Características

- 📖 **Gestión de libros** — Registrar, editar y eliminar títulos del catálogo bibliográfico.
- 👤 **Gestión de usuarios** — Registro y administración de usuarios (lectores y administradores).
- 🔄 **Préstamos de libros** — Control de préstamos activos con fechas de entrega y devolución.
- 🗓️ **Reservas** — Sistema de reserva de libros para usuarios registrados.
- 🔍 **Búsqueda** — Búsqueda de libros por título, autor o categoría.
- 🛡️ **Panel administrativo** — Acceso protegido para el personal de la biblioteca.

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Descripción |
|---|---|
| **PHP** | Lógica del servidor y procesamiento de datos |
| **MySQL** | Base de datos relacional para almacenar la información |
| **HTML5** | Estructura de las vistas y formularios |
| **CSS3** | Estilos y diseño visual de la interfaz |
| **Visual Studio Code** | Editor de código utilizado en el desarrollo |

---

## 📁 Estructura del proyecto

```
Sistema_para_biblioteca_municipal/
└── biblioteca_php/
    ├── index.php               # Página principal / Login
    ├── config/
    │   └── conexion.php        # Configuración de la base de datos
    ├── libros/
    │   ├── listar_libros.php
    │   ├── agregar_libro.php
    │   └── editar_libro.php
    ├── usuarios/
    │   ├── listar_usuarios.php
    │   ├── agregar_usuario.php
    │   └── editar_usuario.php
    ├── prestamos/
    │   ├── listar_prestamos.php
    │   └── registrar_prestamo.php
    ├── reservas/
    │   ├── listar_reservas.php
    │   └── registrar_reserva.php
    └── assets/
        ├── css/
        └── img/
```

> **Nota:** La estructura puede variar ligeramente según los archivos dentro de la carpeta `biblioteca_php`.

---

## ⚙️ Requisitos previos

Antes de instalar el sistema, asegúrate de tener lo siguiente:

- [XAMPP](https://www.apachefriends.org/es/index.html) o [WAMP](https://www.wampserver.com/) instalado (incluye Apache, PHP y MySQL)
- PHP **7.4** o superior
- MySQL **5.7** o superior
- Navegador web moderno (Chrome, Firefox, Edge)

---

## 🚀 Instalación

1. **Clona o descarga el repositorio:**

```bash
git clone https://github.com/tarkramirez23-gif/Sistema_para_biblioteca_municipal.git
```

2. **Copia la carpeta del proyecto a tu servidor local:**

   - Para XAMPP: copia la carpeta dentro de `C:/xampp/htdocs/`
   - Para WAMP: copia dentro de `C:/wamp64/www/`

3. **Inicia los servicios** de Apache y MySQL desde el panel de XAMPP/WAMP.

4. **Abre el navegador** y accede a:

```
http://localhost/biblioteca_php/
```

---

## 🗄️ Configuración de la base de datos

1. Abre **phpMyAdmin** en tu navegador:
```
http://localhost/phpmyadmin
```

2. Crea una nueva base de datos llamada:
```
biblioteca_municipal
```

3. Importa el archivo `.sql` incluido en el proyecto (si está disponible) o crea las tablas manualmente según la estructura requerida.

4. Abre el archivo de conexión (`config/conexion.php`) y ajusta los datos si es necesario:

```php
<?php
$host     = "localhost";
$usuario  = "root";
$password = "";
$base     = "biblioteca_municipal";

$conexion = mysqli_connect($host, $usuario, $password, $base);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
```

---

## 📖 Uso

Una vez instalado y configurado el sistema:

1. Ingresa a `http://localhost/biblioteca_php/` en tu navegador.
2. Inicia sesión con las credenciales de administrador.
3. Desde el panel principal puedes:
   - **Agregar o buscar libros** en el catálogo.
   - **Registrar nuevos usuarios** (lectores).
   - **Gestionar préstamos**: registrar salidas y devoluciones.
   - **Administrar reservas** activas.

---

## 📸 Capturas de pantalla

> *(Próximamente — agrega imágenes de tu sistema aquí)*

Para agregar capturas, crea una carpeta `/screenshots` en el repositorio y referencia las imágenes así:

```markdown
![Panel Principal](screenshots/panel_principal.png)
![Listado de Libros](screenshots/libros.png)
```

---

## 👨‍💻 Autor

**tarkramirez23-gif**

- GitHub: [@tarkramirez23-gif](https://github.com/tarkramirez23-gif)

---

## 📍 Contexto

Este sistema fue desarrollado como solución tecnológica para la **Biblioteca Municipal de Cajamarca**, Perú, con el objetivo de modernizar y agilizar los procesos de gestión bibliográfica, préstamos y reservas.

---

<p align="center">
  Desarrollado con ❤️ en Cajamarca, Perú 🇵🇪
</p>
