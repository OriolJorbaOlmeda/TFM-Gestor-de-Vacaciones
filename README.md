# TFM - GESTOR DE VACACIONES



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
    * Ejecutar el test
        php bin/phpunit tests/UserTest.php


## FUNCIONALIDADES

**USUARIO ADMINISTRADOR**

- Crear calendario
- Dar de alta usuario
- Modificar usuario

**USUARIO EMPLEADO**
- Solicitar vacaciones
- Solicitar ausencia justificada
- Borrar solicitud de vacaciones
- Dashboard con su información personal
- Calendario vacacional del resto de empleados
- Listado del estado de solicitudes


**USUARIO EMPLEADO SUPERVISOR**
- Solicitar vacaciones
- Solicitar ausencia justificada
- Borrar solicitud de vacaciones
- Dashboard con su información personal
- Calendario vacacional del resto de empleados
- Listado del estado de solicitudes
- Aprobar o denegar solicitud de sus subordinados

**GENERALES**
- Login
- Logout
- Registrar una empresa
- Cambiar contraseña
- Cambiar idioma (inglés, castellano, catalán y euskera)


## SOLICITUDES

Estados: 
- PENDING
- ACCEPTED
- DENIED
- CANCELED (en este caso en vez de guardar el estado, se borra la petición directamente)

Tipos: 
- VACATION
- ABSENCE

