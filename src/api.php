<?php
    // définition des constantes
    $API_KEY = "e93f866871a4e28d2076a2475e885408";
    $API_URL = "http://api.themoviedb.org/3/";

    function chargerDonnee(string $ressource, array $options = NULL) {
        # Génère l'URl de la donnée recherchée, puis renvoie le résultat sous
        # forme d'array
        global $API_KEY, $API_URL;

        # Génération de l'URL
        $url = $API_URL . $ressource . "?api_key=" . $API_KEY;
        $url .= ($options === NULL) ? "" : "&" . http_build_query($options);

        # Récupération des données
        $result_json = file_get_contents($url);
        $resultat_array = json_decode($result_json);

        return $resultat_array;
    }
?>