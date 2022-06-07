<?php
    error_reporting(E_ERROR | E_PARSE);

    // définition des constantes
    $API_KEY = "e93f866871a4e28d2076a2475e885408";
    $API_URL = "http://api.themoviedb.org/3/";
    $IMAGE_URL = "http://image.tmdb.org/t/p/w500";
    $HD_IMAGE_URL = "http://image.tmdb.org/t/p/original";

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

    function formater_donnee(array $donnee) {
        // Formate un film ou une série pour ne garder que les informations essentielles
        // dans une affiche poster
        global $IMAGE_URL;

        $id = $donnee["id"];
        $poster = $IMAGE_URL . $donnee["poster_path"];          // Récupère l'URL complète du poster
        $genre = rechercher_genre($donnee["genre_ids"][0]);     // Récupère le nom du premier genre de la liste

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

        return $donnee_formate;
    }

    function rechercher(string $recherche): array {
        # Récupère la liste des films et des séries liées à une recherche
        $contenu_brut = charger_donnee_api("search/multi", ["query" => $recherche]);
        $contenu_filtre = array_filter($contenu_brut["results"], 'film_serie_valide');

        # Formate chaque objet récupéré
        $resultat = [];
        foreach($contenu_filtre as $donnee) {
            $donnee_formate = formater_donnee($donnee);
            $id = $donnee["id"];
            $resultat[$id] = $donnee_formate;
        }

        return $resultat;
    }

    function rechercher_collection(int $id_collection) {
        // Récupère tous les films d'une collection
        global $HD_IMAGE_URL;

        $donnee = charger_donnee_api("collection/$id_collection");

        $resultat = [];
        foreach ($donnee["parts"] as $film) {
            $film_formate = [
                "nom" => $film["title"],
                "annee_sortie" => substr($film["release_date"], 0, 4),
                "resume" => $film["overview"],
                "fond" => $HD_IMAGE_URL . $film["backdrop_path"]
            ];

            $resultat[$film["id"]] = $film_formate;
        }

        return $resultat;
    }

    function detail_film(int $id): array {
        global $HD_IMAGE_URL;

        # Récupère les données détaillées d'un film
        try {
            $donnee = charger_donnee_api("movie/$id", ["append_to_response" => "credits,watch/providers,recommendations"]);

            $genres = array_map(function($el) {return $el["name"];}, $donnee["genres"]);
            $acteurs = array_map(function($el) {return $el["name"];}, array_slice($donnee["credits"]["cast"], 0, 10));
            $recommendations = array_map('formater_donnee', array_slice($donnee["recommendations"]["results"], 0, 5));

            // Gestion des plateformes de contenu accessibles en France
            $plateformes = [];
            if (array_key_exists('FR', $donnee["watch/providers"]["results"])) {
                $plateformes = array_map(function($el) {
                    global $HD_IMAGE_URL;
                    return $HD_IMAGE_URL . $el["logo_path"];
                }, $donnee["watch/providers"]["results"]["FR"]["flatrate"]);
            }
            $resultat["plateformes"] = $plateformes;

            // Gestion de la collection
            $films_collection = [];
            if (array_key_exists('belongs_to_collection', $donnee)) {
                $films_collection = rechercher_collection($donnee["belongs_to_collection"]["id"]);
                unset($films_collection[$id]);  // On retire le film actuel de la collection
            }

            $resultat = [
                "nom" => $donnee["title"],
                "annee_sortie" => substr($donnee["release_date"], 0, 4),
                "resume" => $donnee["overview"],
                "origine" => $donnee["production_countries"][0]["iso_3166_1"],
                "fond" => $HD_IMAGE_URL . $donnee["backdrop_path"],
                "acteurs" => $acteurs,
                "genres" => $genres,
                "collection" => $films_collection,
                "recommendations" => $recommendations,
            ];
        }
        catch (TypeError $err) {
            # Erreur 404, film non trouvé
            $resultat = [];
        }

        return $resultat;
    }

    function detail_serie(int $id): array {
        # Récupère les données détaillées d'une série
        try {
            $donnee = charger_donnee_api("tv/$id");
        } catch (TypeError $err) {
            # Erreur 404, série non trouvée
            $donnee = [];
        }

        return $donnee;
    }

    print_r(detail_film(284052));
