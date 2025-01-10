Enunciado
Se deben programar con Symfony un endpoint JSON y un comando. Ambos deben
ejecutar la misma tarea: realizar una petición a un proveedor ficticio, leer el xml de
respuesta y extraer la información de los vuelos disponibles.
Se deben programar también tests automatizados con PHPUnit.
El proyecto debe seguir la Arquitectura de Puertos y Adaptadores, también conocida
como Arquitectura Hexagonal
Formato del endpoint:
Será un GET a la ruta /api/avail enviando obligatoriamente los parámetros origin, destination
y date.
Ejemplo:
GET /api/avail?origin=MAD&destination=BIO&date=2022-06-01
Formato del comando:
Al ejecutar la tarea por la línea de comandos, se deben pasar los mismos datos
php bin/console lleego:avail MAD BIO 2023-06-01Sobre el proveedor ficticio:
La url del proveedor ficticio a la que debes realizar la petición es la siguiente:
https://testapi.lleego.com/prueba-tecnica/availability-price? origin=MAD&destination=BIO&date=2022-06-01
La petición al proveedor ficticio debe ser de tipo GET.
Esta url te devolverá un XML en formato SOAP.
La información relativa a los vuelos que debes extraer está bajo el xpath
AirShoppingRS/DataLists/FlightSegmentList/FlightSegment
Cada uno de los FlightSegment es una escala de un viaje. Para facilitar la tarea, estas
opciones solamente tendrán una escala, con lo que puedes asimilar un FlightSegment como
un vuelo/viaje individual.
En la respuesta ficticia hay un total de 5 vuelos
Formato de salida del endpoint:
El formato de salida del endpoint será un JSON como el siguiente:
Formato de salida del comando:
La salida del comando será en formato Tabla.
Se recomienda el uso del helper Table de Symfony que formatea automáticamente.
https://symfony.com/doc/current/components/console/helpers/table.html
Resumen
Programar con Symfony y Arquitectura de Puertos y Adaptadores el endpoint, el comando y
los tests automatizados que se consideren.
