<?php
    // définition des constantes
    $API_KEY = "e93f866871a4e28d2076a2475e885408";
    $API_URL = "http://api.themoviedb.org/3/";

    function charger_donnee_api(string $ressource, array $options = NULL) {
        # Génère l'URl de la donnée recherchée, puis renvoie le résultat sous
        # forme d'array
        global $API_KEY, $API_URL;

        # Génération de l'URL
        $url = $API_URL . $ressource . "?language=fr&api_key=" . $API_KEY;
        $url .= ($options === NULL) ? "" : "&" . http_build_query($options);

        # Récupération des données
        $result_json = file_get_contents($url);
        $resultat_array = json_decode($result_json, true);

        return $resultat_array;
    }

    function film_serie_valide(array $objet) {
        # Vérifie si l'objet passé en paramètre est un film ou une série conforme
        $backdrop_valide = array_key_exists('backdrop_path', $objet);
        if ($backdrop_valide) {
            $backdrop_valide = !is_null($objet['backdrop_path']);
        }

        $poster_valide = array_key_exists('poster_path', $objet);
        if ($poster_valide) {
            $poster_valide = !is_null($objet['poster_path']);
        }

        return $backdrop_valide && $poster_valide;
    }

    function rechercher(string $recherche) {
        # Récupère la liste des films et des séries liées à une recherche
        $contenu_brut = charger_donnee_api("search/multi", ["query" => $recherche]);
        $contenu_trie = array_filter($contenu_brut["results"], 'film_serie_valide');

        return $contenu_trie;
    }

    

    // rechercher("Interstellar");
    // print_r(rechercher("Interstellar"));

?>