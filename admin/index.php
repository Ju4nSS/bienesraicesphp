<?php 
    // importar la conexión (BBDD)
    require '../includes/config/database.php';
    $db = conectarBBDD();

    // escribir el query
    $query = 'SELECT * FROM propiedades';

    // enviar el query (consultar la BBDD)
    $resultado = mysqli_query($db, $query);

    // mensaje condicional
    $registro = $_GET['registro'] ?? null ;

    // incluir template
    require '../includes/funciones.php';
    incluirTemplate( 'header' );
?>

    <main class = 'container section'>
        <h1>Administrador de Bienes Raíces</h1>

        <?php if ( intval( $registro ) === 1 ): ?>
            <p class = 'alerta exito'>Propiedad registrada correctamente</p> 
        <?php elseif ( intval( $registro ) === 2 ): ?>
            <p class = 'alerta exito'>Propiedad actualizada correctamente</p> 
        <?php endif; ?>

        <a href="propiedades/crear.php" class = 'btn-verde'>Nueva propiedad</a>
    
        <table class = 'tabla-propiedades'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th class = 'crud' >Acciones</th>       
                </tr>
            </thead>
            <tbody>
                
                <?php while( $propiedad = mysqli_fetch_assoc($resultado) ) : ?>
                    <tr>
                        <td><?php echo $propiedad['id'] ?></td>
                        <td><?php echo $propiedad['titulo'] ?></td>
                        <td><img class = 'tabla-imagen' src="/bienesraices/imagenes/<?php echo $propiedad['imagen'] ?>" alt="imagen propiedad"></td>
                        <td>$<?php echo $propiedad['precio'] ?></td>
                        <td>
                            <a class = 'btn-m2-rojo' href="#">Eliminar</a>
                            <a class = 'btn-m2-azul' href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id'] ?>">Actualizar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </main>

    <?php 

    // cerrar la conexión (BBDD)
    mysqli_close($db);

    incluirTemplate( 'footer' ); 

    ?>