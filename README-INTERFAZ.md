# MarketGo – actualización de interfaz

## Cambios incluidos
- Se agregó una base visual compartida y adaptable en `css/style.css` para evitar desplazamientos del menú y contenido en pantallas grandes y móviles.
- Se agregó `js/ui.js` para abrir/cerrar el menú lateral en pantallas pequeñas (botón ☰, clic fuera y tecla Escape).
- Los estilos `<style>` y atributos `style` estáticos de las páginas HTML/PHP se trasladaron a hojas CSS individuales dentro de `css/`.
- Se normalizó el panel del empleado con tarjetas de resumen que consultan cantidades desde MySQL y accesos rápidos.
- Se agregaron referencias a los archivos CSS/JS externos en las páginas con interfaz.

## Estructura
- `admin/`, `cliente/`, `empleado/`: vistas PHP; conservan la lógica de consultas, sesiones y formularios.
- `css/style.css`: estilos comunes y responsive.
- `css/page-*.css`: estilos particulares extraídos de cada página.
- `js/ui.js`: comportamiento común del menú.
- `js/` contiene además los scripts funcionales existentes.

## Nota técnica
Los scripts de páginas que imprimen datos PHP dentro de JavaScript (por ejemplo, listas serializadas con `json_encode`) se mantienen junto a su vista porque dependen de datos generados en el servidor. No se trasladaron a ciegas para evitar romper las funciones. La lógica PHP de autenticación, consultas y persistencia permanece en PHP; CSS y comportamiento común de interfaz están separados.
Antes de usarlo, respalda tu carpeta y prueba con la misma base de datos local. Este ZIP no modifica el esquema SQL.


## Fotografías de productos
En Administración > Productos puedes subir una fotografía JPG, PNG o WEBP de hasta 5 MB. El archivo se guarda en `uploads/productos/` y la columna `productos.imagen` conserva su ruta relativa. No se requiere alterar la base de datos si esa columna ya existe. Las imágenes se presentan con `object-fit: contain` para conservar la proporción y evitar deformaciones.

Para que las fotografías sean específicas de cada artículo, sube la foto real del empaque correspondiente al registrar el producto. Los productos que ya existían sin ruta de imagen mostrarán un marcador neutral hasta que les cargues una foto.
