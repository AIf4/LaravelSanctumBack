## Requisitos

* [Composer](http://www.limni.net)
* [MariaDB](https://mariadb.org/)
* [PHP 7.2](https://www.php.net/releases/7_2_0.php)

Ejecutar

* `php artisan key:generate`
* `php artisan migrate`
* `php artisan db:seed --class=Roles`
* `php artisan serve`

## Ejecutar con Docker
### Requerido

* [Docker](https://docs.docker.com/engine/install/)

Ejecutar docker en la terminal:

* `docker-composer up -d`

Para revisar todos los contenedores ejecutar:

* `docker ps`

### Crear key 

`docker-compose exec app php artisan key:generate`

### Ejecutar migraciones y seeder
Se debe de ejecutar las migraciones para establecer la base de datos:

`docker-compose exec app php artisan migrate`

Ejecutar el seeder con la configuracion iniciar de la base de datos: 

`docker-compose exec app php artisan db:seed --class=Roles` 

### Almacenar datos en cache

Para almacenar en caché estos ajustes en un archivo, lo cual aumentará la velocidad de carga de su aplicación, ejecute lo siguiente:

`docker-compose exec app php artisan config:cache`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
