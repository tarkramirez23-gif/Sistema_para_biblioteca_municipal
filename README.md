# 📚 Sistema de Gestión para La Biblioteca Municipal de Cajamarca.

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white"/>
  <img src="https://img.shields.io/badge/Estado-%20LISTO-green?style=for-the-badge"/>
</p>

-Sistema web desarrollado para digitalizar y optimizar la gestión de la **Biblioteca Municipal de Cajamarca**, Permite administrar el catálogo de libros, los usuarios registrados, los préstamos activos y las reservas de material bibliográfico.

---

## 🗂️ INDICE.

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

## ✅ Características.

- 📖 **Gestión de libros** — Registrar, editar y eliminar títulos del catálogo bibliográfico.
- 👤 **Gestión de usuarios** — Registro y administración de usuarios (lectores y administradores).
- 🔄 **Préstamos de libros** — Control de préstamos activos con fechas de entrega y devolución.
- 🗓️ **Reservas** — Sistema de reserva de libros para usuarios registrados.
- 🔍 **Búsqueda** — Búsqueda de libros por título, autor o categoría.
- 🛡️ **Panel administrativo** — Acceso protegido para el personal de la biblioteca.

---

## 🛠️ Tecnologías utilizadas en el desarrollo del proyecto.

| Tecnología | Descripción |
|---|---|
| **PHP** | Lógica del servidor y procesamiento de datos |
| **MySQL** | Base de datos relacional para almacenar la información |
| **HTML** | Estructura de las vistas y formularios |
| **CSS** | Estilos y diseño visual de la interfaz |
| **Visual Studio Code** | Editor de código abierto utilizado en el desarrollo del proyecto |

---

## 📁 Estructura del proyecto.

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

## ⚙️ Requisitos previos.

Antes de instalar el sistema, asegúrate de tener lo siguiente en tu ordenador:

- [XAMPP](https://www.apachefriends.org/es/index.html) instalado (incluye Apache, PHP y MySQL)
- PHP **7.4** o superior
- MySQL **5.7** o superior
- Navegador web moderno (Chrome, Firefox, Edge, brave)

---

## 🚀 Instalación.

1. **Clona o descarga el repositorio para que puedas trabajar o contribuir en el proyecto:**

```bash
git clone https://github.com/tarkramirez23-gif/Sistema_para_biblioteca_municipal.git
```

2. **Copia la carpeta del proyecto a tu servidor local:**

   - Para XAMPP: copia la carpeta dentro de `C:/xampp/htdocs/`


3. **Inicia los servicios** de Apache y MySQL desde el panel de XAMPP.

4. **Abre el navegador** y accede  según tu LocalHost:

```
http://localhost/biblioteca_php/
```

---

## 🗄️ Configuración de la base de datos.

1. Abre **phpMyAdmin** en tu navegador mediante el LocalHost:
```
http://localhost/phpmyadmin
```

2. Crea una nueva base de datos a tu elección es este caso sera llamada:
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

## 📖 Uso.

Una vez instalado y configurado el sistema:

1. Ingresa a `http://localhost/biblioteca_php/` en tu navegador.
2. Inicia sesión con las credenciales de administrador.
3. Desde el panel principal puedes:
   - **Agregar o buscar libros** en el catálogo.
   - **Registrar nuevos usuarios** (lectores).
   - **Gestionar préstamos**: registrar prestamos y devoluciones.
   - **Administrar reservas** : activas.

---

## 📸 Capturas del proyecto.

Codigo fuente
<img width="1919" height="846" alt="image" src="https://github.com/user-attachments/assets/9da44d6f-6c21-4bbd-bb14-9861d164210e" />

<img width="671" height="432" alt="Captura de pantalla 2026-05-24 035707" src="https://github.com/user-attachments/assets/491306f3-b4ec-4829-bf62-b943fbe8c60a" />

<img width="1919" height="901" alt="image" src="https://github.com/user-attachments/assets/4baa3ca8-ad59-4473-a15a-79401c309c3f" />

<img width="1912" height="907" alt="image" src="https://github.com/user-attachments/assets/c390445c-4cfe-4c37-a7aa-aec8b48e6172" />

<img width="1916" height="755" alt="image" src="https://github.com/user-attachments/assets/d3e454dd-0d1f-4e63-96bc-bf0dd60c12b2" />

---

## 👤 Autor.

**THEYLOR RAMIREZ VASQUEZ**
- GitHub: (tarkramirez23-gif)
- LinkedIn: (https://www.linkedin.com/in/theylor-ramirez-vasquez-4799112a6/)
- Gmail: tarkramirez23@gmail.com

---



