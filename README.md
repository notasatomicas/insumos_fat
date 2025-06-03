# Proyecto *Insumos_FAT*

## Descripción del Proyecto

Este proyecto es una tienda virtual de insumos informáticos desarrollada utilizando la última versión de **CodeIgniter 4**, un framework liviano y potente basado en PHP. La plataforma permite a los usuarios explorar, buscar y comprar una amplia gama de productos tecnológicos como periféricos, componentes, accesorios y dispositivos electrónicos.

La aplicación está diseñada con un enfoque en el rendimiento, la seguridad y la escalabilidad, aprovechando las características modernas que ofrece CodeIgniter 4, como el enrutamiento mejorado, los controladores basados en espacio de nombres y el uso de entidades para una gestión más limpia de los datos.

### Funcionalidades principales

- 🖥️ Catálogo dinámico de productos.  
- 👥 Gestión de usuarios y autenticación. (en proceso)
- 🛒 Carrito de compras y sistema de pagos.  (en proceso)
- ⚙️ Panel administrativo para la gestión de inventario y pedidos. (en proceso)

Este sistema es ideal tanto para pequeñas tiendas como para negocios en crecimiento que desean tener presencia en línea y ofrecer una experiencia de compra sencilla y eficiente.

## Requisitos del servidor

Se requiere PHP versión 8.1 o superior, con las siguientes extensiones instaladas:

- [intl](http://php.net/manual/en/intl.requirements.php)  
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> ⚠️ **ADVERTENCIA**  
> - La fecha de fin de vida útil de PHP 7.4 fue el 28 de noviembre de 2022.  
> - La fecha de fin de vida útil de PHP 8.0 fue el 26 de noviembre de 2023.  
> - Si aún estás utilizando PHP 7.4 o 8.0, deberías actualizarlo de inmediato.  
> - La fecha de fin de vida útil de PHP 8.1 será el 31 de diciembre de 2025.

Además, asegúrate de que las siguientes extensiones estén habilitadas en tu PHP:

- `json` (habilitada por defecto - no la desactives)  
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) si planeas usar MySQL  
- [libcurl](http://php.net/manual/en/curl.requirements.php) si planeas usar la biblioteca `HTTP\CURLRequest`

## Otros Frameworks Usados

Este proyecto también hace uso de otras tecnologías modernas para mejorar la experiencia del usuario, el diseño y la funcionalidad general del sitio:

- 🎨 **[Bootstrap 5](https://getbootstrap.com/)**  
  Utilizado para el diseño responsivo y la construcción rápida de interfaces limpias y modernas. Permite que el sitio se adapte a cualquier tamaño de pantalla.

- 🗺️ **[Leaflet](https://leafletjs.com/)**  
  Una biblioteca JavaScript ligera para mapas interactivos. Se usa en la sección de contacto para mostrar la ubicación de la tienda de forma visual e intuitiva.

- 💫 **[Animate.css](https://animate.style/)**  
  Biblioteca de animaciones CSS predefinidas que mejora la apariencia visual del sitio con transiciones suaves y llamativas.

- ✨ **[Hoverme.css](https://www.hoverme.epizy.com/)**  
  Conjunto de efectos de animación aplicados al pasar el cursor sobre elementos interactivos, como botones e imágenes, para mejorar la experiencia del usuario.

---

Estas herramientas complementan a **CodeIgniter 4**, potenciando tanto la estética como la usabilidad del sistema.

## 📄 Tabla: `users`

Esta tabla almacena la información de los usuarios registrados en el sistema, tanto compradores como administradores.

---

## 🧱 Estructura de la tabla

| Columna         | Tipo de dato         | Descripción |
|------------------|----------------------|-------------|
| `id_usuario`            | `INT(11) UNSIGNED` (AUTO_INCREMENT) | Identificador único del usuario. Clave primaria. |
| `email`         | `VARCHAR(255)`       | Correo electrónico del usuario. Debe ser único. |
| `username`      | `VARCHAR(30)`        | Nombre de usuario. Debe ser único. |
| `password_hash` | `VARCHAR(255)`       | Contraseña cifrada del usuario. |
| `nombre`        | `VARCHAR(100)`       | Nombre del usuario. |
| `apellido`      | `VARCHAR(100)`       | Apellido del usuario. |
| `dni`           | `VARCHAR(20)`        | Documento Nacional de Identidad. |
| `direccion`     | `VARCHAR(255)`       | Dirección física del usuario. |
| `type`          | `TINYINT(1)`         | Tipo de usuario: `0` = Comprador, `1` = Administrador. |
| `active`        | `TINYINT(1)`         | Estado del usuario: `0` = Inactivo, `1` = Activo. |
| `created_at`    | `DATETIME` (nullable) | Fecha de creación del registro. |
| `updated_at`    | `DATETIME` (nullable) | Fecha de la última actualización. |
| `deleted_at`    | `DATETIME` (nullable) | Fecha de eliminación lógica del registro. |

---

### Script que usamos para crear la tabla en PhpMyAdmin

```sql
CREATE DATABASE insumos_fat;
USE insumos_fat;

CREATE TABLE users (
    id_usuario INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(30) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    type TINYINT(1) NOT NULL DEFAULT 0, -- 0 = Comprador, 1 = Administrador
    active TINYINT(1) NOT NULL DEFAULT 1, -- 0 = Inactivo, 1 = Activo
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    deleted_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categorias (
    id_categoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    estado TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

CREATE TABLE productos (
    id_producto INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_prod VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    imagen_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    active TINYINT(1) DEFAULT 1,

    FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria) ON DELETE RESTRICT
);

CREATE TABLE facturas (
    id_factura INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(11) UNSIGNED NOT NULL,
    fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP,
    precio_total DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (id_usuario) REFERENCES users(id_usuario) ON DELETE CASCADE
);

CREATE TABLE detalle_factura (
    id_detalle_factura INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_factura INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad_prod INT NOT NULL,
    precio_unit DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (id_factura) REFERENCES facturas(id_factura) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE RESTRICT
);
```

## 🔑 Índices y restricciones

- **Clave primaria**: `id`
- **Únicos**:
  - `email`
  - `username`

---

## ⚙️ Motor y codificación

- **Motor**: InnoDB  
- **Codificación**: `utf8`


## Quienes somos

Somos estudiantes de la Lic. en Sistemas - este es un proyecto desarrollado para la asignatura Taller de programación 1

- Ariel Antinori
- Andres Sena

## Para hacer las migraciones

php spark migrate

## Para ejecutar el seed del admin
php spark db:seed UserSeeder
