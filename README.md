# TP2 Web - TUDAI - 2022

## Descripcion 

La siguiente Api fue desarrollada y pensada para ser utilizada en un sistema web de un hospital para el control y el seguimiento de los pacientes. Contando con diversas funciones para un control amplio y tambien con una conexion a una base de datos la cual contiene tablas de pacientes, obras sociales, historias clinicas, y tambien una tabla de usuarios administradores para poder ejercer ciertas funciones.

## Recursos de la API

A continuacion se desarrollaran los distintos recursos con los que cuenta esta Api y sus funcionalidades.

* ## Base de datos

Se encuentra en la carpeta "database", sera necesario importar esta base de datos para el funcionamiento de la API.

* ## Router

El router brinda distintos metodos para acceder a las distintas funciones. Este esta vinculado a una libreria la cual provee todas las operaciones necesarias para que los metodos y parametros sean ejecutados.

            Endpoints:

            * http://localhost/(tucarpetalocal)/TPE2/api/pacientes
            * http://localhost/(tucarpetalocal)/TPE2/api/obrasocial
            * http://localhost/(tucarpetalocal)/TPE2/api/historiaclinica
            * http://localhost/(tucarpetalocal)/TPE2/api/admin/token

            Se recomienda la utilizacion de Postman para la visualizacion del funcionamiento.

#### **Metodos Publicos**:

1. **_Metodo GET_** -> Con GET se logra traer y mostrar la tabla a la que estamos refiriendo. Existe la posibilidad de hacer un llamado con mayor especificidad utilizando distintos parametros como los que se detallaran a continuacion con algunos ejemplos

    * ##### *Filtrar por ID*: 
    Es posible obtener e inspeccionar un elemento obteniendo la ID de este. Para esto es necesario tomar como parametro la ID del elemento que se quiera inspeccionar, este parametro es tomado por el controlador de la tabla a la que estemos llamando y llevado al modelo de la misma, conectandose con la base de datos y trayendo el resultado. La URL quedaria de esta forma en caso de inspeccionar un paciente: 
    
            URL/pacientes/(id)

            Se reemplaza el "(id)" por el id que se desee filtrar
        
    * Los codigos de errores de respuesta pueden ser:

    - 404 "Not found" en caso de no exista el ID solicitado.


    * ##### *Ordenar por "Sort"*: 
    Con el parametro Sort es posible reordenar la tabla en base a una columna que el usuario especifique, de manera predeterminada una vez reordenada la lista mediante un sort la tabla se mostrara de manera ascendente. Tanto en la tabla de pacientes como obra social es posible la utilizacion de este parametro. Una URL de ejemplo en la cual se solicita reordenar por nombre seria la siguiente: 
    
            URL/pacientes?sort=nombre

            sort= Se establece el nombre de columna por el cual se desea reordenar

    * Los codigos de errores de respuesta pueden ser:

    - 400 "Bad request" en caso de que la columna solicitada no exista.


    * ##### *Ordenar por "Order"*: 
    Con el parametro Order es posible cambiarle el orden a la columna a la que llamamos por "Sort", este orden puede ser Ascendente (ASC) o Descendente (DESC), en caso de no asignar ningun valor este sera Ascendente por defecto. Un ejemplo de como quedaria la URL seria el siguiente: 
    
            URL/pacientes?sort=nombre&order=DESC

            order= Se establece el orden, puede ser ascendente o descendente (en caso de no establecerse el orden por defecto en ascendente)

    * Los codigos de errores de respuesta pueden ser:

    - 400 "Bad request" en caso de que se pase un parametro no existente. Recordar que los unicos posibles son ASC y DESC.


    * ##### *Filtrar por Obra Social*: 
    Es posible hacer un filtrado de pacientes por obra social. Para esto se usa el parametro "obrasocial" seguido del nombre de la obra social a la que queremos filtrar y nos devolvera una lista de los pacientes que pertenezcan a esta. Ejemplo de URL: 
    
            URL/pacientes?obrasocial=Osde

            obrasocial= Se ingresa el nombre de la obra social al que se desee visualizar los pacientes que esta incluye.

    * Los codigos de errores de respuesta pueden ser:

    - 400 "Bad request" en caso de que la obra social no exista.


    * ##### *Filtrar Historia Clinica por Paciente*: 
    Es posible hacer un filtrado de historias clinicas por paciente. Para esto se usa el parametro "paciente" seguido del nombre del mismo y nos devolvera una lista de las historias clinicas que pertenezcan a dicho paciente. Ejemplo de URL: 
    
            URL/historiaclinica?paciente=Matias

            paciente= Se ingresa el nombre del paciente al que se desee visualizar las historias clinicas.

    * Los codigos de errores de respuesta pueden ser:

    - 400 "Bad request" en caso de que el nombre del paciente no exista.


    * ##### *Paginacion*: 
    Tambien existe la posibilidad de "paginar" la lista tanto de pacientes como de obra social, poniendo "page" para determinar en que pagina desea situarse (siendo 0 la primer pagina) y "limit" para establecer el limite de elementos que se desee mostrar por pagina. Un ejemplo de URL para este caso seria el siguiente: 
    
            URL/pacientes?sort=nombre&order=DESC&page=0&limit=2

            page= el numero de pagina que se desee visualizar.
            limit= el limite de elementos que se desee visualizar por pagina.
            

    * Los codigos de errores de respuesta pueden ser:

    - 400 "Bad request" en caso de parametros mal pasados en la URL (como por ejemplo una letra en lugar de un numero).


    * ##### *Ingreso*: 
    Es posible ingresar como usuario "Admin" en donde una vez hecho el login se generara un Token el cual habilitara el ingreso a las funciones a continuacion. Un ejemplo de la URL: 
    
            URL/admin/token 

    * Los codigos de errores de respuesta pueden ser:

    - 401 "Unauthorized" en caso de un login erroneo o que la autentificacion no sea Basic.


#### **Metodos Privados (requieren Token)**:

2. **_Metodo POST_** -> Con POST existe la posibilidad de agregar elementos a la tabla. Es posible utilizar este metodo tanto en la tabla de pacientes, de obras sociales o de historia clinica. Para cada tabla sera necesario disponer de un formulario adecuado a los campos necesarios de cada una.

La API esta preparada para poder agregar imagenes de los estudios hechos en los pacientes como por ejemplo Rx, Resonancias magneticas, tomografias, etc.

* Los codigos de errores de respuesta pueden ser:

- 401 "Unauthorized" en caso de intentar hacer POST sin Token.
- 400 "Bad request" en caso de no completar los campos con los datos a enviar.


3. **_Metodo PUT_** -> Este metodo permite al usuario poder modificar algun elemento de la tabla sin necesidad de eliminarlo y agregarlo nuevamente. Para esto sera necesario utilizar el ID del elemento que se quiera modificar como parametro para que pueda ser llamado y actualizado por la base de datos. Ejemplo: 
    
        URL/obrasocial/(id)

* Los codigos de errores de respuesta pueden ser:

- 404 "Not found" en caso de solicitar una actualizacion del ID de un paciente, obra social, o historia clinica que noexista.
- 401 "Unauthorized" en caso de intentar hacer PUT sin Token.
- 400 "Bad request" en caso de no completar los campos con los datos a actualizar.


4. **_Metodo DELETE_** -> Con este metodo sera posible eliminar elementos de la tabla, para esto es necesario tomar como parametro el ID de dicho elemento y eliminarlo mediante SQL. Ejemplo: 
    
        URL/pacientes/(id)

* Los codigos de errores de respuesta pueden ser:

- 404 "Not found" en caso de solicitar la eliminacion del ID de un paciente, obra social o historia clinica que no exista.
- 401 "Unauthorized" en caso de intentar hacer DELETE sin Token.
- 400 "Bad request" en caso de no completar los campos con los datos a enviar.

* ## MVC

Esta API dispone de Modelo Vista y Controlador (Model View & Controller) para la ejecucion de sus funciones.

* ### Modelo:

    Este es el encargado de hacer la conexion a la base de datos mediante PDO, cuenta con un modelo por cada tabla de la misma.

* ### Vista:

    La vista es la encargada de traer la respuesta generada por el controller junto a su codigo de estado de respuesta de http, entre los que se encuentran:

    1. 200 "OK" -> La solicitud ha tenido éxito. El significado de un éxito varía dependiendo del método HTTP:
    2. 201 "Created" -> La solicitud ha tenido éxito y se ha creado un nuevo recurso como resultado de ello. Ésta es típicamente la respuesta enviada después de una petición PUT.
    3. 400 "Bad request" -> Esta respuesta significa que el servidor no pudo interpretar la solicitud dada una sintaxis inválida.
    4. 401 "Unauthorized" -> Es necesario autenticar para obtener la respuesta solicitada. Esta es similar a 403, pero en este caso, la autenticación es posible.
    5. 403 "Forbidden" -> El cliente no posee los permisos necesarios para cierto contenido, por lo que el servidor está rechazando otorgar una respuesta apropiada.
    6. 404 "Not found" -> El servidor no pudo encontrar el contenido solicitado. Este código de respuesta es uno de los más famosos dada su alta ocurrencia en la web.
    7. 500 "Internal Server Error" -> El servidor ha encontrado una situación que no sabe cómo manejarla.

* ### Controlador:

    El controlador es el encargado de comunicar el request del body con el servidor (en este caso el modelo) para posteriormente poder llevar ese contenido generado a la vista.

* ### Helper:

    La app tambien contiene un Helper, el cual es el destinado a corroborar la autenticacion del usuario mediante funciones que puedan obtener y decodificar el token para validarlo.

* ## Token

La API cuenta con un sistema de autentificacion por token para acceder a funciones privadas como POST, PUT y DELETE. Para obtenerlo es necesario hacer un login mediante autentificacion Basic (el usuario debe estar ingresado en la tabla "medicos"), una vez hecho el login se generara un token con una firma privada al final de este, el mismo contara con un tiempo de duracion de 3600 segundos = 60 minutos. El token debera ser ingresado en la autentificacion Bearer Token para poder ejercer las funciones de administrador.









