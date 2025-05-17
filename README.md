# DevWebCamp

DevWebCamp es una plataforma web desarrollada con PHP que simula un evento de conferencias tecnológicas. Permite a los usuarios registrarse, adquirir entradas y acceder a contenido exclusivo. El proyecto está construido utilizando el patrón de arquitectura MVC y emplea tecnologías modernas como SASS, Gulp y JavaScript para una experiencia de usuario optimizada.

## 🚀 Características

- **Gestión de usuarios**: Registro, autenticación y recuperación de contraseñas.
- **Panel de administración**: CRUD de ponentes, eventos y categorías.
- **Sistema de entradas**: Compra y validación de accesos.
- **Diseño responsive**: Adaptado para dispositivos móviles y de escritorio.
- **Automatización de tareas**: Compilación de SASS y minificación de archivos con Gulp.

## 🛠️ Tecnologías utilizadas

- **Backend**: PHP
- **Frontend**: SCSS, JavaScript
- **Herramientas**: Gulp, Composer, NPM
- **Base de datos**: MySQL

## 📁 Estructura del proyecto

- `classes/`: Clases auxiliares y utilidades.
- `controllers/`: Controladores que manejan la lógica de negocio.
- `models/`: Modelos que interactúan con la base de datos.
- `views/`: Vistas y plantillas HTML.
- `public/`: Archivos públicos accesibles desde el navegador.
- `src/`: Recursos como SASS y JavaScript.
- `includes/`: Archivos compartidos y configuraciones.
- `Router.php`: Sistema de enrutamiento personalizado.

## ⚙️ Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Julian-Sandoval-x/devwebcamp.git
   cd devwebcamp
   ```
2. Instala dependencias de PHP

   ```bash
   composer install
   ```

3. Instala dependencias de Node.js

   ```bash
   npm install
   ```

4. Configura la base de datos y el archivo .env con tus credenciales

5. Compila los archivos SASS y JavaScript con gulp

```bash
    npm run dev
```

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo LICENSE para más información.

## Autor

Desarrollado por [Julian Sandoval](https://github.com/Julian-Sandoval-x)
Estudiante de Ingeniería en Sistemas Computacionales
Apasionado por el desarrollo backend y en constante aprendizaje.
