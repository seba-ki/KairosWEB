# 🌐 KairosWEB

Bienvenido a **KairosWEB**, la plataforma web de la empresa de software **Kairos**. 

## 🚀 Tecnologías utilizadas
- **PHP 8.3**
- **CSS3**
- **JavaScript**
- **Symfony 6.4**

## 📥 Instalación

### 1️⃣ Requisitos previos
Antes de instalar KairosWEB, asegúrate de tener instalado:
- [PHP 8.3](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
- Un servidor web como Apache o Nginx
- Base de datos MySQL (opcional, según la configuración del proyecto)

### 2️⃣ Clonar el repositorio
```bash
    git clone https://github.com/seba-ki/KairosWEB.git
    cd KairosWEB
```

### 3️⃣ Instalar dependencias
```bash
    composer install
```

### 4️⃣ Configurar variables de entorno
Renombra el archivo `.env.example` a `.env` y edita las variables de conexión a la base de datos y otros ajustes necesarios.
```bash
    cp .env.example .env
```
Luego, genera la clave de aplicación:
```bash
    php bin/console cache:clear
```

### 5️⃣ Ejecutar la base de datos (opcional)
Si el proyecto requiere base de datos, ejecuta:
```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
```

### 6️⃣ Levantar el servidor de desarrollo
```bash
    symfony server:start
```
Tu proyecto estará disponible en [http://localhost:8000](http://localhost:8000).

---
## 📬 Contacto
Si tienes dudas o sugerencias, puedes contactarme en GitHub o por correo electrónico. 

¡Gracias por probar KairosWEB! 🚀
