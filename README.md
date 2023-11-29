# CS-phpPDO
@lenvigo
Esta tarea trabajaremos sobre la base de datos bookdb.

Clona el repositorio https://github.com/dudwcs/Tarea04.1-enunciado.git. Se trata de una aplicación web que debe conectarse a la base de datos bookdb , mostrar un listado de libros y permitir su eliminación en una transacción.  Sobre ella, deberás realizar las siguientes modificaciones:

1- En el script conexion.php, modifica la función getConnection() para que lea los datos de conexión de un fichero db_settings.ini en lugar de almacenar directamente los datos de conexión el propio archivo php. En él deberán figurar:

driver
host
schema
username
password
persistent (para indicar si la conexión a usar será persistente (true) o no (false). Lee el ejemplo 4 de la documentación: https://www.php.net/manual/es/pdo.connections.php
(1 punto)

2-Implementa la function findAllAuthors() en crear.php para que haga una consulta con PDO y obtenga un único array con el identificador y  los nombres completos de todos los autores ordenados por last_name. El nombre completo debe ser la concatenación de last_name, first_name y middle_name. Mucho cuidado porque cualquiera de los 3 podría ser NULL.  Pueden ser de utilidad funciones SQL:

https://mariadb.com/kb/en/concat/
https://mariadb.com/kb/en/coalesce/
(2 puntos)

3- Añade la extensión PHP de DEVSENSE a Visual Studio Code (https://docs.devsense.com/en/vscode/editor/phpdoc)



En File> Preferences > settings > busca "editor.formatOnType y selecciona la checkbox



Sitúa el cursor sobre la firma de la function y escribe /**

Debería sustituirse automáticamente por un esqueleto de la documentación (aquí se muestra un ejemplo con borrar_libro):



Añade una breve descripción de qué hace la función findAllAuthors y qué devuelve

(1 punto)

4- Completa en crear.php el <select name=author_ids[] ... >para que muestre una lista de opciones con los nombres completos de los autores recuperados en el punto anterior.

(1 punto)

5- Completa crear.php para que inserte en una misma transacción el nuevo libro en la tabla books y, si se han seleccionado autores (uno o más), añada un nuevo registro en la tabla intermedia book_authors.

Crea al menos una función para este propósito y documéntala con la extensión PHP de DEVSENSE(1 punto)
Utiliza sentencias preparadas (1 punto)
Utiliza parámetros nominales (1 punto)
Controla las posibles excepciones en un try-catch y realiza un rollback en caso de error.(1 punto)
El caso de uso funciona correctamente (1 punto)





