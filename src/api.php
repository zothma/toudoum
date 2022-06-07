<?php
    error_reporting(E_ERROR | E_PARSE);

    // définition des constantes
    $API_KEY = "e93f866871a4e28d2076a2475e885408";
    $API_URL = "http://api.themoviedb.org/3/";
    $IMAGE_URL = "http://image.tmdb.org/t/p/w500";

    function charger_donnee_api(string $ressource, array $options = NULL): array {
        # Génère l'URL de la donnée recherchée, puis renvoie le résultat sous
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

    function rechercher_genre(int $id_genre = NULL): string {
        // Recherche un genre par son identifiant

        // La liste des genres sera remplie une fois au premier appel, puis conservée avec
        // le mot-clé static
        static $liste_genre = [];
        if ($liste_genre === []) {
            $liste_genre_films = charger_donnee_api("genre/movie/list")["genres"];
            $liste_genre_series = charger_donnee_api("genre/tv/list")["genres"];
            
            // On associe chaque id de genre à son nom
            foreach ($liste_genre_films as $genre) {
                $liste_genre[$genre["id"]] = $genre["name"];
            }
            foreach ($liste_genre_series as $genre) {
                $liste_genre[$genre["id"]] = $genre["name"];
            }
        }

        // Récupère le nom du genre s'il existe
        if (array_key_exists($id_genre, $liste_genre)) {
            return $liste_genre[$id_genre];
        } else {
            return "Genre inconnu";
        }
    }

    function film_serie_valide(array $objet): bool {
        # Vérifie si l'objet passé en paramètre est un film ou une série conforme
        $backdrop_valide = array_key_exists('backdrop_path', $objet);
        if ($backdrop_valide) {
            $backdrop_valide = !is_null($objet['backdrop_path']);
        }

        $poster_valide = array_key_exists('poster_path', $objet);
        if ($poster_valide) {
            $poster_valide = !is_null($objet['poster_path']);
        }

        $type_correct = true;
        if (array_key_exists('media_type', $objet)) {
            $type_correct = $objet["media_type"] === "movie" || $objet["media_type"] === "tv";
        }

        return $backdrop_valide && $poster_valide && $type_correct;
    }

    function rechercher(string $recherche): array {
        # Récupère la liste des films et des séries liées à une recherche
        global $IMAGE_URL;

        $contenu_brut = charger_donnee_api("search/multi", ["query" => $recherche]);
        $contenu_trie = array_filter($contenu_brut["results"], 'film_serie_valide');

        # Formate chaque objet pour qu'un film et une série aient la même forme
        $resultat = [];
        foreach($contenu_trie as $donnee) {
            $id = $donnee["id"];
            $donnee_formate = [];
            
            $poster = $IMAGE_URL . $donnee["poster_path"];          // Récupère l'URL complète du poster
            $genre = rechercher_genre($donnee["genre_ids"][0]);     // Récupère le nom du premier genre de la liste
            $origine = $donnee["origin_country"][0];                // Récupère le premier pays d'origine

            if ($donnee["media_type"] === "movie") {
                $id = "f$id";
                
                $donnee_formate = [
                    "nom" => $donnee["title"],
                    "annee_sortie" => substr($donnee["release_date"], 0, 4),
                    "poster" => $poster,
                    "genre" => $genre
                ];
            } else {
                $id = "s$id";
                $donnee_formate = [
                    "nom" => $donnee["name"],
                    "annee_sortie" => substr($donnee["first_air_date"], 0, 4),
                    "poster" => $poster,
                    "genre" => $genre
                ];
            }

            $resultat[$id] = $donnee_formate;
        }

        return $resultat;
    }

    function detail_film(int $id): array {
        # Récupère les données détaillées d'un film
        try {
            $contenu_brut = charger_donnee_api("movie/$id");
        } catch (TypeError $err) {
            # Erreur 404, film non trouvé
            $contenu_brut = [];
        }

        return $contenu_brut;
    }

    function detail_serie(int $id): array {
        # Récupère les données détaillées d'une série
        try {
            $contenu_brut = charger_donnee_api("tv/$id");
        } catch (TypeError $err) {
            # Erreur 404, série non trouvée
            $contenu_brut = [];
        }

        return $contenu_brut;
    }

    print_r(rechercher("Top Gun"));
?>