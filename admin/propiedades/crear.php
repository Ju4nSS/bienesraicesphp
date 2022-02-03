<?php 
    // BBDD
    require '../../includes/config/database.php';
    $db = conectarBBDD();

    // consultar para obtener los vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query( $db, $consulta );

    // arreglo con msj de errores
    $errores = [];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';

    // ejecutar el código después de que el usuario envía el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // echo '<pre>'; 
        // var_dump($_POST);
        // echo '</pre>';

        // echo '<pre>'; 
        // var_dump($_FILES);
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

        if (!$imagen['name']) { $errores[] = 'Debes importar una imagen'; }

        if ( strlen($descripcion) < 50 ) { $errores[] = 'La descripción no puede tener menos de 50 caracteres. Inventa algo'; }

        if (!$habitaciones) { $errores[] = 'Debes definir una cantidad de habitaciones'; }

        if (!$wc) { $errores[] = 'Debes definir una cantidad de wc'; }

        if (!$estacionamiento) { $errores[] = 'Debes definir una cantidad de estacionamientos'; }

        if (!$vendedorId) { $errores[] = 'Debes elegir un vendedor'; }

        // validar por tamaños 
        $limite = 1000 * 1000;
        
        if ( $imagen['size'] > $limite || $imagen['error'] ) { $errores[] = 'El peso de la imagen no puede superar 1 mb'; }

        // revisar que el array de errores esté vacío
        if ( empty($errores) ) {
            // subida de archivos

                // crear carpeta
                $carpetaImagenes = '../../imagenes/';
                
                if( !is_dir($carpetaImagenes) ) { // comprueba si la carpeta existe. En caso contrario, la crea
                    mkdir($carpetaImagenes);
                }

                // generar nombre único
                $nombreImagen = md5 ( uniqid(rand(), true) ) . '.jpg';
                
                // subir imagen
                move_uploaded_file( $imagen['tmp_name'], $carpetaImagenes . $nombreImagen );

            // insertar en la BBDD
            $query = "INSERT INTO propiedades ( titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId ) 
                      VALUES ( '$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId' ) ";
            
            $resultado = mysqli_query($db, $query);
    
            if ($resultado) { 
                // redireccionar al usuario
                header('Location: /bienesraices/admin?registro=1');
            } 
        }
    }

    require '../../includes/funciones.php';
    incluirTemplate( 'header' );
?>

    <main class = 'container section contenido-centrado'>
        <h1>Crear</h1>
        
        <?php foreach( $errores as $error ) { ?>
        
            <div class = 'alerta error'>
                <?php echo $error; ?>
            </div>

        <?php } ?>

        <form class = 'formulario' method = 'POST' action = '/bienesraices/admin/propiedades/crear.php' enctype = 'multipart/form-data'>
            <fieldset>
                <legend>Información general</legend>

                <label for="titulo">Título:</label>
                <input type="text" id = 'titulo' name = 'titulo' value = '<?php echo $titulo ?>' placeholder = 'Titulo propiedad'>

                <label for="precio">Precio:</label>
                <input type="number" id = 'precio' name = 'precio' value = '<?php echo $precio ?>' placeholder = 'Precio propiedad' min = '1'>

                <label for="imagen">Imagen:</label>
                <input type="file" id = 'imagen' name = 'imagen' accept = 'image/jpeg, image/png'>
            
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

            <input type="submit" value = 'Crear propiedad' class = 'btn-verde'>
        </form>
        
        <a href="../index.php" class = 'btn-amarillo'>Volver</a>

    </main>

    <?php incluirTemplate( 'footer' ) ?>