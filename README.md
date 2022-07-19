# TFM - GESTOR DE VACACIONES

##### ICONOS
https://fontawesome.com/icons

- Si se quiere utilizar alguno pero no funciona, añadirlo en: "app/symfony/public/bootstrap_adminLTE/plugins/fontawesome-free/css/all.css"
- También se pueden usar los de bootstrap


## 🐳 Primero pasos a seguir

Se ha de tener instalado [Docker](https://www.docker.com/get-started)

Una vez instalado se ha de inicializar el proyecto, para ello se tiene que levantar el entorno siguiendo estos pasos:

    * Abrimos un terminal y hacemos git clone  https://github.com/rubencougil-mpwar/rendimiento-practica-final-MireiaCastillejo.git
    * Para levantar el servidor y todos los servicios   
        make start
    * Para entrar al contenedor de PHP
        make ssh-php-fpm 
    * Accedemos a la carpeta especifica de symfony
        cd /appdata/www/app/symfony
    * Instalamos la carpeta de vendor dentro de symfony 
        composer install
    * Nos conectamos a la base de datos Acordaros que el puerto es el 10000 y para actualizar cualquier cambio de la BD  
        bin/console doctrine:schema:update --force