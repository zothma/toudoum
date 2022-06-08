<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/polices.css">
</head>
<body>
    <?php
        include("./src/carte_film.php");
        include("./src/api.php");

        $resultat = rechercher($_GET["query"]);
        foreach($resultat as $element)
        {
            generer_carte($element, 10);
        }
    ?>
</body>
</html>