# Bruma Café 2.0

Demo de una cafetería ficticia con PHP, MySQL, HTML, CSS y JavaScript. Incluye catálogo, carrito, pedidos de prueba, contacto y administración. No procesa pagos ni envía pedidos a un negocio real.

## Requisitos

- XAMPP para Windows con Apache, PHP y MySQL/MariaDB.
- PHP con `pdo_mysql`, `mbstring` y sesiones habilitadas. Sintaxis comprobada con PHP 8.0.30; no supone recomendar esa versión para producción.
- Navegador moderno compatible con el elemento HTML `dialog`.
- No requiere Composer, npm, Node.js ni compilación.
- El CSS carga DM Sans y Playfair Display desde Google Fonts. Esa carga requiere Internet y realiza solicitudes a Google; sin conexión se usan fuentes de reserva. No hay otras dependencias externas del navegador.

## Instalar desde cero en XAMPP

1. Copia esta carpeta como `C:\xampp\htdocs\cafe_portafolio` en una instalación de prueba. No sobrescribas una instalación original existente. Usa otra instalación o adapta el nombre de carpeta y la base de datos.
2. Inicia Apache y MySQL desde XAMPP.
3. Abre `http://localhost/phpmyadmin/` e importa `database.sql`. Crea la base `cafe_portafolio`, las tablas y seis productos ficticios. Usa una base nueva: repetir el script duplica los productos de ejemplo. Cambiar solo el nombre de carpeta no aísla la base de datos.
4. Desde PowerShell en la carpeta del proyecto, crea la configuración local:

   ```powershell
   Copy-Item config.example.php config.php
   ```

   Edita `config.php` y sustituye `TU_USUARIO_MYSQL` y `TU_PASSWORD_MYSQL` por los valores de tu instalación. En un XAMPP local sin personalizar, pueden ser `root` y una cadena vacía, respectivamente; compruébalo. No uses una cuenta sin contraseña en un servidor público. El archivo está excluido mediante `.gitignore`.
5. Abre `http://localhost/cafe_portafolio/`. Adapta la URL si cambiaste el nombre de carpeta o el puerto de Apache.

Si necesitas otra base para no tocar la original, cambia primero el nombre en las instrucciones `CREATE DATABASE`/`USE` de los SQL y en el campo `database` de `config.php`.

## Crear un administrador

No se incluyen cuentas ni contraseñas de administrador. `crear_admin.php` solo funciona desde la terminal. En PowerShell, sustituye ambos marcadores por tus propios valores:

```powershell
cd C:\xampp\htdocs\cafe_portafolio
& C:\xampp\php\php.exe crear_admin.php '<USUARIO>' '<CONTRASENA_PRIVADA>'
```

El usuario debe tener al menos 3 caracteres y la contraseña al menos 12. Usa una contraseña exclusiva para esta demo. El script original recibe la contraseña como argumento: puede quedar en el historial de la terminal y ser visible a procesos locales. No compartas el historial ni reutilices una contraseña real. La base guarda un hash mediante `password_hash`.

Después entra en `http://localhost/cafe_portafolio/admin/login.php`. Puedes borrar `crear_admin.php` de la instalación local tras crear el administrador; se conserva en el código fuente para nuevas instalaciones.

## Migrar desde v1

Importa únicamente `actualizacion_v2.sql` sobre la base existente para añadir las tablas de pedidos. No vuelvas a importar `database.sql`. Conserva tu configuración privada. La instalación desde cero no requiere ejecutar esta migración adicional.

## Probar

1. Abre el catálogo y el detalle de un producto.
2. Añade productos al carrito, cambia cantidades y elimina alguno.
3. Registra un pedido con datos ficticios.
4. Accede al panel, consulta el pedido y cambia su estado.
5. Crea o edita un producto y verifica el catálogo.
6. Envía un mensaje ficticio y comprueba el panel.

Si falla la conexión, comprueba MySQL, la importación y `config.php`. Si falla `mb_strlen`, habilita `mbstring` en el `php.ini` de XAMPP y reinicia Apache.

## Archivos y privacidad

`database.sql` contiene esquema y productos ficticios; `actualizacion_v2.sql` solo contiene esquema. No publiques exportaciones posteriores de tu base: la aplicación almacena nombres, teléfonos, correos, mensajes y hashes de administradores. No publiques `config.php`, `.env`, claves privadas, logs o respaldos.

El `.gitignore` previene inclusiones accidentales comunes, pero no elimina archivos ya versionados. Revisa los archivos y el diff antes de publicarlos. Esta copia no incluye historial Git y no fue publicada automáticamente.

La revisión de archivos para compartir el código no certifica un despliegue público. Se conservó la lógica original: el login no limita intentos y no hay controles contra abuso de formularios. Incluye consultas parametrizadas, CSRF en formularios y hash de contraseñas; un despliegue público necesita una revisión adicional de HTTPS, cookies, privacidad y configuración del servidor.
