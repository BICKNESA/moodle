<?php
    #database connection
    echo "<!-- Database conection included -->";
    $pdo = new PDO(
        'mysql:host=localhost; dbname=movie',
        'movie',
        'password'
    );
 ?>
