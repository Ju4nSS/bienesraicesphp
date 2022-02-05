<?php 
    // validar URL por id válido
    $id = $_GET['id'];
    $id = filter_var( $id, FILTER_VALIDATE_INT );

    if (!$id) { header('Location: /bienesraices/admin'); }

    // BBDD
    require '../../includes/config/database.php';
    $db = conectarBBDD();

    // obtenerlos datos de la propiedad
    $consulta = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = mysqli_query( $db, $consulta );
    $propiedad = mysqli_fetch_assoc( $resultado );

    // consultar para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query( $db, $consulta );

    // arreglo con msj de errores
    $errores = [];

    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $imagenPropiedad = $propiedad['imagen'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamiento = $propiedad['estacionamiento'];
    $vendedorId = $propiedad['vendedorId'];

    // ejecutar el código después de que el usuario envía el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // echo '<pre>'; 
        // var_dump($imagen);
        // echo '</pre>';

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');

        // asignar files hacia una variable
        $imagen = $_FILES['imagen'];

        if (!$titulo) { $errores[] = 'Debes definir un título'; }

        if (!$precio) { $errores[] = 'Debes definir un precio'; }

        // if (!$imagen['name']) { $errores[] = 'Debes importar una imagen'; }

        if ( strlen($descripcion) < 50 ) { $errores[] = 'La descripción no puede tener menos de 50 caracteres. Inventa algo'; }

        if (!$habitaciones) { $errores[] = 'Debes definir una cantidad de habitaciones'; }

        if (!$wc) { $errores[] = 'Debes definir una cantidad de wc'; }

        if (!$estacionamiento) { $errores[] = 'Debes definir una cantidad de estacionamientos'; }

        if (!$vendedorId) { $errores[] = 'Debes elegir un vendedor'; }

        // validar por tamaño 
        $limite = 1000 * 1000;
        
        if ( $imagen['size'] > $limite ) { $errores[] = 'El peso de la imagen no puede superar 1 mb'; }

        // revisar que el array de errores esté vacío
        if ( empty($errores) ) {
            // crear carpeta
            $carpetaImagenes = '../../imagenes/';
                
            if( !is_dir($carpetaImagenes) ) { // comprueba si la carpeta existe. En caso contrario, la crea
                mkdir($carpetaImagenes);
            }

            if ( $imagen['name'] ) {
                // eliminar imagen previa
                unlink( $carpetaImagenes . $propiedad['imagen'] );
                // generar nombre único
                $nombreImagen = md5( uniqid(rand(), true) ) . '.jpg';
                // subida de archivos
                move_uploaded_file( $imagen['tmp_name'], $carpetaImagenes . $nombreImagen );

            } else {
                $nombreImagen = $propiedad['imagen'];
            }

            // insertar en la BBDD
            $query = " UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '$nombreImagen', descripcion = '${descripcion}', habitaciones = ${habitaciones}, 
                       wc = ${wc}, estacionamiento = ${estacionamiento}, vendedorId = ${vendedorId} WHERE ${id}";

            $resultado = mysqli_query($db, $query);
    
            if ($resultado) { 
                // redireccionar al usuario
                header('Location: /bienesraices/admin?registro=2');
            } 
        }
    }

    require '../../includes/funciones.php';
    incluirTemplate( 'header' );
?>

    <main class = 'container section contenido-centrado'>
        <h1>Actualizar propiedad</h1>
        
        <?php foreach( $errores as $error ) { ?>
        
            <div class = 'alerta error'>
                <?php echo $error; ?>
            </div>

        <?php } ?>

        <form class = 'formulario' method = 'POST' enctype = 'multipart/form-data'>
            <fieldset>
                <legend>Información general</legend>

                <label for="titulo">Título:</label>
                <input type="text" id = 'titulo' name = 'titulo' value = '<?php echo $titulo ?>' placeholder = 'Titulo propiedad'>

                <label for="precio">Precio:</label>
                <input type="number" id = 'precio' name = 'precio' value = '<?php echo $precio ?>' placeholder = 'Precio propiedad' min = '1'>

                <label for="imagen">Imagen:</label>
                <input type="file" id = 'imagen' name = 'imagen' accept = 'image/jpeg, image/png'>
                <img class = 'tabla-imagen' style = 'margin-bottom: 1.5rem' src="/bienesraices/imagenes/<?php echo $imagenPropiedad ?>" alt="Imagen propiedad">

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name = 'descripcion' cols="30" rows="10"><?php echo $descripcion ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información	de la propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id = 'habitaciones' name = 'habitaciones' value = '<?php echo $habitaciones ?>' placeholder = 'Cantidad' min = '1' max = '9'>

                <label for="wc">Baños:</label>
                <input type="number" id = 'wc' name = 'wc' value = '<?php echo $wc ?>' placeholder = 'Cantidad' min = '1' max = '9'>

                <label for="estacionamiento">Estacionamientos:</label>
                <input type="number" id = 'estacionamiento' name = 'estacionamiento' value = '<?php echo $estacionamiento ?>' placeholder = 'Cantidad' min = '1' max = '9'>
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name = 'vendedor'>
                    <option value="">-- Seleccionar --</option>
                    <?php while( $vendedor = mysqli_fetch_assoc($resultado) ) { ?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value = '<?php echo  $vendedor['id']; ?>'><?php echo $vendedor['nombre'] . ' ' . $vendedor['apellido']; ?></option>    
                    <?php } ?>    
                </select>
            </fieldset>

            <input type="submit" value = 'Actualizar propiedad' class = 'btn-verde'>
        </form>
        
        <a href="../index.php" class = 'btn-amarillo'>Volver</a>

    </main>

    <?php incluirTemplate( 'footer' ) ?>