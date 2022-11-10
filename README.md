# TP2 Web - TUDAI - 2022

## Descripcion 

La siguiente Api fue desarrollada y pensada para ser utilizada en un sistema web de un hospital para el control y el seguimiento de los pacientes. Contando con diversas funciones para un control amplio y tambien con una conexion a una base de datos la cual contiene tablas de pacientes, obras sociales, historias clinicas, y tambien una tabla de usuarios administradores para poder ejercer ciertas funciones.

### Postman

El endpoint de la api es: http://localhost/(tucarpetalocal)/TPE2/api/pacientes

## Recursos de la API

A continuacion se desarrollaran los distintos recursos con los que cuenta esta Api y sus funcionalidades.

* ### Router

El router brinda distintos metodos para acceder a las distintas funciones.

#### **Metodos Publicos**:

1. **_Metodo GET_** -> Con GET se logra traer y mostrar la tabla a la que estamos refiriendo. Existe la posibilidad de hacer un llamado con mayor especificidad utilizando distintos parametros como los que se detallaran a continuacion con algunos ejemplos

    * *Filtrar por ID*: Es posible obtener e inspeccionar un elemento obteniendo la ID de este. Para esto es necesario tomar como parametro la ID del elemento que se quiera inspeccionar, este parametro es tomado por el controlador de la tabla a la que estemos llamando y llevado al modelo de la misma, conectandose con la base de datos y trayendo el resultado. La URL quedaria de esta forma en caso de inspeccionar un paciente: *URL/pacientes/(id)*

    * *Ordenar por Sort*: Con el parametro Sort es posible reordenar la tabla en base a una columna que el usuario especifique, de manera predeterminada una vez reordenada la lista mediante un sort la tabla se mostrara de manera ascendente. Tanto en la tabla de pacientes como obra social es posible la utilizacion de este parametro. Una URL de ejemplo en la cual se solicita reordenar por nombre seria la siguiente: *URL/pacientes?sort=nombre*

    * *Ordenar por Order*: Con el parametro Order es posible cambiarle el orden a la columna a la que llamamos por "Sort", este orden puede ser Ascendente (ASC) o Descendente (DESC), en caso de no asignar ningun valor este sera Ascendente por defecto. Un ejemplo de como quedaria la URL seria el siguiente: *URL/pacientes?sort=nombre&order=DESC*

    * *Filtrar por Obra Social*: Es posible hacer un filtrado de pacientes por obra social. Para esto se usa el parametro "obrasocial" seguido del nombre de la obra social a la que queremos filtrar y nos devolvera una lista de los pacientes que pertenezcan a esta. Ejemplo de URL: *URL/pacientes?obrasocial=Osde*

    * *Paginacion*: Tambien existe la posibilidad de "paginar" la lista tanto de pacientes como de obra social, poniendo "page" para determinar en que pagina desea situarse (siendo 0 la primer pagina) y "limit" para establecer el limite de elementos que se desee mostrar por pagina. Un ejemplo de URL para este caso seria el siguiente: *URL/pacientes?sort=nombre&order=DESC&page=0&limit=2*

    * *Ingreso*: Es posible ingresar como usuario "Admin" en donde una vez hecho el login con la URL: "*URL/admin/token*", se generara un Token el cual habilitara el ingreso a las funciones a continuacion

#### **Metodos Privados (requieren Token)**:

2. **_Metodo POST_** -> Con POST existe la posibilidad de agregar elementos a la tabla. Es posible utilizar este metodo tanto en la tabla de pacientes, de obras sociales o de historia clinica. Para cada tabla sera necesario disponer de un formulario adecuado a los campos necesarios de cada una.


3. **_Metodo PUT_** -> Este metodo permite al usuario poder modificar algun elemento de la tabla sin necesidad de eliminarlo y agregarlo nuevamente. Para esto sera necesario utilizar el ID del elemento que se quiera modificar como parametro para que pueda ser llamado y actualizado por la base de datos. Ejemplo: *URL/obrasocial/(id)*


4. **_Metodo DELETE_** -> Con este metodo sera posible eliminar elementos de la tabla, para esto es necesario tomar como parametro el ID de dicho elemento y eliminarlo mediante SQL. Ejemplo: *URL/pacientes/(id)*


* ### Token

La API cuenta con un sistema de autentificacion por token para acceder a funciones privadas como POST, PUT y DELETE. Para obtenerlo es necesario hacer un login mediante autentificacion Basic (el usuario debe estar ingresado en la tabla "medicos"), una vez hecho el login se generara un token con una firma privada al final de este, el mismo contara con un tiempo de duracion de 3600 segundos = 60 minutos. El token debera ser ingresado en la autentificacion Bearer Token para poder ejercer las funciones de administrador.





