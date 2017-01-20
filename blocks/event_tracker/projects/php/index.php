<?php
    #This is a page about something... not really sure what yet.
    include('includes/error_reporting.php');
    require_once('includes/database.php');
    $copyrightYear = date('Y');
    $string1 = "These are some";
    $string2 = "words in some variables.";
    $authorName = [
        'first' => 'Sammy',
        'last' => 'Bicknell',
    ];

    $people = [];
    $peopleQuery = $pdo->query("SELECT * FROM people ORDER BY name ASC");
    while ($person = $peopleQuery ->fetch(PDO::FETCH_OBJ)){
        $people[$person->id] = $person->name;
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP - Site</title>
         <link type="text/css" href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <select name="name">
            <?php
                foreach ($people as $id => $name) {
                    echo '<option value='.$id.'>'.$name.'</option>';
                }
             ?>
        </select>
        <?php
            $movieQuery = $pdo->query("SELECT * FROM movies");
            while ($movie = $movieQuery->fetch(PDO::FETCH_OBJ)) {
                $movieName = $movie->name;
        ?>
                    <div>
                        <h3>
                            <?php echo $movieName?>
                        </h3>
                        <img src="img/<?php echo $movieName ?>.jpg" alt="A poster of <?php echo $movieName ?>">
                    </div>

        <?php
            }
        ?>
        <footer>
            <p>
                <?php
                    echo $copyrightYear."<br>".implode(' ', $authorName) ;
                ?>
            </p>
        </footer>
    </body>
</html>
