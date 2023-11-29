<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <title>Crear libro</title>
</head>

<body>
    <?php
    require_once 'conexion.php';
    require_once 'util.php';

    $pdate = null;
    $isbn = null;
    $pub_Id = null;
    //todos los autores disponibles en BD
    $authors = null;
    //los ids de los autores del libro
    $book_author_ids = null;
    $title = "";
    $exito = true;



    $publishers = findAllPublishers();
    $authors = findAllAuthors();

    if (isset($_POST["title"])) {
        if (isNotEmpty($_POST["title"])) {
            $title = $_POST["title"];
        }

        if (isset($_POST["isbn"]) && isNotEmpty($_POST["isbn"])) {
            $isbn = $_POST["isbn"];
        }

        if (isset($_POST["pdate"]) && isNotEmpty($_POST["pdate"])) {
            $pdate = $_POST["pdate"];
            $pdate_converted = DateTimeImmutable::createFromFormat("Y-m-d", $pdate);
            if ($pdate_converted !== false) {
                $pdate = $pdate_converted ->format("Y-m-d");;
            }
        }

        if (isset($_POST["publisher"]) && isNotEmpty($_POST["publisher"])) {
            $pub_Id = $_POST["publisher"];
        }
        if (isset($_POST["author_ids"])) {
            $book_author_ids = (count($_POST["author_ids"]) == 1 && $_POST["author_ids"][0] == "") ? null : $_POST["author_ids"];
        }
        $data = [
            "title" => $title,
            "isbn" => $isbn,
            "pdate" => $pdate,
            "publisher" => $pub_Id,
            "authors" => $book_author_ids
        ];
        $exito = insertBook($data);
    }

    /*  $conProyecto = getConnection();

        try {
            $conProyecto->beginTransaction();
    
            $inserted = insertBook($conProyecto, $title, $isbn, $pdate, $pub_Id);
    
            if ($inserted && !empty($book_author_ids)) {
                // Obtén el ID del libro recién insertado
                $book_id = $conProyecto->lastInsertId();
    
                // Llamada a la función para insertar los autores del libro
                insertBookAuthors($conProyecto, $book_id, $book_author_ids);
            }
    
            $conProyecto->commit();
        } catch (Exception $e) {
            $conProyecto->rollBack();
            $exito = false;
        }
    } */

    ?>
    <div class="container-fluid">
        <header class="mb-5">
            <div class="p-5 text-center " style="margin-top: 58px;">
                <h1 class="mb-3"> Crear libro </h1>

            </div>
        </header>
        <form class='form-control ' method="post">
            <div>
                <label for="title" class="form-label col-3">Título</label>
                <input name="title" type="text" class="form-control col-9" id="title" pattern="^(?!\s*$).+" required />
            </div>
            <div>
                <label for="isbn" class="form-label col-3">ISBN</label>
                <input name="isbn" type="text" class="form-control col-9" id="isbn" pattern="^(?!\s*$).+" />
            </div>

            <div>
                <label for="pdate" class="form-label col-3">Fecha de publicación</label>
                <input name="pdate" type="date" class="form-control col-9" id="pdate" />
            </div>

            <div class='row form-group my-3'>
                <label for="publisher" class="col-form-label col-2">Editorial</label>
                <div class='col-6'>
                    <select name="publisher" id="publisher" class="form-control col-3" required>

                        <option value="" disabled>----</option>
                        <?php
                        if (count($publishers) > 0) :
                            foreach ($publishers as $publisher) :
                        ?>
                                <option value="<?= $publisher["publisher_id"] ?>">
                                    <?= $publisher["name"] ?>
                                </option>
                        <?php
                            endforeach;
                        endif;
                        ?>


                    </select>
                </div>
            </div>

            <div class="form-group row my-3">
                <label for="authors" class="col-form-label col-2">Autor</label>

                <div class="col-6">
                    <select name="author_ids[]" id="authors" class="form-control" multiple>

                        <option value="">----</option>
                        <?php
                        //4- Completa en crear.php el <select name=author_ids[] ... >para que muestre una lista de opciones con los nombres completos de los autores recuperados en el punto anterior
                        if (count($authors) > 0) {
                            foreach ($authors as $author) {
                                echo "<option value='{$author['author_id']}'>{$author['nombre']}</option>";
                            }
                        }

                        ?>


                    </select>
                </div>


            </div>
            <div class="row d-flex justify-content-center">
                <button type="submit" class="btn btn-primary my-3 col-3">Crear libro</button>
            </div>

        </form>
        <a href="listado.php" class="btn btn-link mt-2">Volver</a>

        <?php if (($exito) && isset($_POST["title"])) : ?>
            <div class="alert alert-success" role="alert">
                El libro se ha creado correctamente
            </div>

        <?php endif;


        /**
         * findAllPublishers
         * Crea una consulta con PDO y obtiene todos los datos de la tabla publishers
         * @return array Array con todas las tuplas de la tabla publishers como array asociativo
         */
        function findAllPublishers(): array
        {
            $conProyecto = getConnection();

            $pdostmt = $conProyecto->prepare("SELECT *FROM publishers ORDER BY name");

            $pdostmt->execute();
            $array = $pdostmt->fetchAll(PDO::FETCH_ASSOC);


            return $array;
        }

        //3- Añade la extensión PHP de DEVSENSE a Visual Studio Code

        /**
         * Summary of findAllAuthors
         * @param PDO  $conProyecto (array asociativo de datos concatenados y ordenados)
         * @return array
         */
        function findAllAuthors(): array
        {
            try {
                $conProyecto = getConnection();

                //2-Implementa la function findAllAuthors() en crear.php para que haga una consulta con PDO y obtenga un único array con el identificador y  los nombres completos de todos los autores ordenados por last_name. El nombre completo debe ser la concatenación de last_name, first_name y middle_name. Mucho cuidado porque cualquiera de los 3 podría ser NULL.  Pueden ser de utilidad funciones SQL:

                $pdostmt = $conProyecto->prepare("SELECT author_id,concat(coalesce(last_name, ''), coalesce(first_name, ''), coalesce(middle_name, ' '))AS nombre FROM authors ORDER BY last_name ");
                $pdostmt->execute();
                $array = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "Error en la consulta::findAllAuthors,mensaje: " . $e->getMessage();
            }
            return $array;
        }

        /* 5- Completa crear.php para que inserte en una misma transacción el nuevo libro en la tabla books y, si se han seleccionado autores (uno o más), añada un nuevo registro en la tabla intermedia book_authors.
        Crea al menos una función para este propósito y documéntala con la extensión PHP de DEVSENSE(1 punto)
        Utiliza sentencias preparadas (1 punto)
        Utiliza parámetros nominales (1 punto)
        Controla las posibles excepciones en un try-catch y realiza un rollback en caso de error.(1 punto)
        El caso de uso funciona correctamente (1 punto)
        */

        /** Summary function insertBook
         * Inserta  en una misma transacción el nuevo libro en la tabla books y, si se han seleccionado autores (uno o más), añade un nuevo registro en la tabla intermedia book_authors
         * @param array $data 
         * title, isbn, publication_date, publisher_id,authors
         * @throws Exception
         * @return  bool
         */

        function insertBook(array $data): bool
        {
            try {
                $conProyecto = getConnection();
                $conProyecto->beginTransaction();
                $sql = "INSERT INTO books (title, isbn, published_date, publisher_id)
                    VALUES (:title, :isbn, :pdate, :publisher)";
                $stmt = $conProyecto->prepare($sql);

                $stmt->bindParam(":title", $data["title"]);
                $stmt->bindParam(":isbn", $data["isbn"]);
                $stmt->bindValue(":pdate", $data["pdate"]);
                $stmt->bindParam(":publisher", $data["publisher"]);

                if ($stmt->execute())
                    $book_id = $conProyecto->lastInsertId();
                else
                    throw new Exception();

                if ($data["authors"] != null) {
                    $sql2 = "INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)";

                    $stmt_b_authors = $conProyecto->prepare($sql2);

                    $stmt_b_authors->bindParam(":book_id", $book_id);


                    foreach ($data["authors"] as $author_id) {
                        $stmt_b_authors->bindParam(":author_id", $author_id);
                        if (!$stmt_b_authors->execute()) throw new Exception();
                    }
                }

                $conProyecto->commit();
            } catch (Exception $e) {
                $conProyecto->rollBack();
                echo "Error al crear el libro, mensaje: " . $e->getMessage();
                return false;
            }

            return true;
        }
        ?>



    </div>
</body>

</html>