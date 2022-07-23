<?php
    // définition des constantes
    const API_KEY = "e93f866871a4e28d2076a2475e885408";
    const API_URL = "https://api.themoviedb.org/3/";
    const IMAGE_URL = "https://image.tmdb.org/t/p/w300";
    const HD_IMAGE_URL = "https://image.tmdb.org/t/p/w1280";
    const FULL_HD_IMAGE_URL = "https://image.tmdb.org/t/p/original";

    function charger_donnee_api(string $ressource, array $options = NULL): array {
        # Génère l'URL de la donnée recherchée, puis renvoie le résultat sous
        # forme d'array
        $url = API_URL . $ressource . "?language=fr&api_key=" . API_KEY;
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

    function recuperer_date(array $objet): int {
        // Récupère la date de sortie d'un film ou d'un série
        $resultat = 0;

        if (array_key_exists("release_date", $objet)) {
            $resultat = strtotime($objet["release_date"]);
        } else if (array_key_exists("first_air_date", $objet)) {
            $resultat = strtotime($objet["first_air_date"]);
        }

        return $resultat;
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

        $date = recuperer_date($objet);
        $date_correcte = $date <= time() && $date != 0;

        return $backdrop_valide && $poster_valide && $type_correct && $date_correcte;
    }

    function formater_donnee(array $donnee, bool $est_film = null) {
        // Formate un film ou une série pour ne garder que les informations essentielles
        // dans une affiche poster
        $id = $donnee["id"];
        $poster = IMAGE_URL . $donnee["poster_path"];           // Récupère l'URL complète du poster
        $fond = FULL_HD_IMAGE_URL . $donnee["backdrop_path"];   // Récupère l'URL complète du fond
        $genre = rechercher_genre($donnee["genre_ids"][0]);     // Récupère le nom du premier genre de la liste
        $popularite = round(floatval($donnee["vote_average"]) * 10);

        if ($est_film ?? (isset($donnee["media_type"]) && $donnee["media_type"] === "movie")) {
            $donnee_formate = [
                "nom" => $donnee["title"],
                "annee_sortie" => substr($donnee["release_date"], 0, 4),
                "poster" => $poster,
                "fond" => $fond,
                "genre" => $genre,
                "popularite" => $popularite
            ];
        } else {
            $donnee_formate = [
                "nom" => $donnee["name"],
                "annee_sortie" => substr($donnee["first_air_date"], 0, 4),
                "poster" => $poster,
                "fond" => $fond,
                "genre" => $genre,
                "popularite" => $popularite
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
            $id = ($donnee["media_type"] === "movie" ? 'f' : 's') . $donnee['id'];
            $resultat[$id] = $donnee_formate;
        }

        return $resultat;
    }

    function recuperer_par_id(string $string_id): array {
        // récupère les informations du film nécessaires pour une affiche
        $est_film = str_starts_with($string_id, 'f');
        
        $prefix = $est_film ? 'movie' : 'tv';
        $id = intval(substr($string_id, 1));
        $donnee = charger_donnee_api("$prefix/$id");

        return formater_donnee($donnee, $est_film);
    }

    function rechercher_collection(int $id_collection) {
        // Récupère tous les films d'une collection
        $contenu_brut = charger_donnee_api("collection/$id_collection");
        $contenu_filtre = array_filter($contenu_brut["parts"], 'film_serie_valide');

        $resultat = [];
        foreach ($contenu_filtre as $film) {
            $film_formate = [
                "nom" => $film["title"],
                "annee_sortie" => substr($film["release_date"], 0, 4),
                "resume" => $film["overview"],
                "fond" => HD_IMAGE_URL . $film["backdrop_path"]
            ];

            $resultat['f' . $film["id"]] = $film_formate;
        }

        return $resultat;
    }

    function detail_general(array $donnee): array {
        // Retourne les données générales rendues par les films et les séries

        $get_name = fn ($el) => $el["name"];
        $genres = array_map($get_name, $donnee["genres"]);
        $acteurs = array_map($get_name, array_slice($donnee["credits"]["cast"], 0, 10));
        $production = array_map($get_name, $donnee["production_companies"]);

        // Gestion des plateformes de contenu accessibles en France
        $plateformes = [];
        if (array_key_exists('FR', $donnee["watch/providers"]["results"])) {
            if (array_key_exists('flatrate', $donnee["watch/providers"]["results"]["FR"])) {
                $plateformes = array_map(function($el) {
                    return FULL_HD_IMAGE_URL . $el["logo_path"];
                }, $donnee["watch/providers"]["results"]["FR"]["flatrate"]);
            }
        }

        $recommendations = [];
        $recommendations_brutes = array_filter($donnee["recommendations"]["results"], 'film_serie_valide');
        foreach (array_slice($recommendations_brutes, 0, 5) as $film) {
            $film_formate = formater_donnee($film);
            $id = ($film["media_type"] === "movie" ? 'f' : 's') . $film["id"];
            $recommendations[$id] = $film_formate;
        }

        return [
            "resume" => $donnee["overview"],
            "origine" => $donnee["production_countries"][0]["iso_3166_1"],
            "fond" => FULL_HD_IMAGE_URL . $donnee["backdrop_path"],
            "acteurs" => $acteurs,
            "genres" => $genres,
            "production" => $production,
            "plateformes" => $plateformes,
            "recommendations" => $recommendations,
            "popularite" => round(floatval($donnee["vote_average"]) * 10)
        ];
    }

    function detail_film(int $id): array {
        # Récupère les données détaillées d'un film
        try {
            $donnee = charger_donnee_api("movie/$id", ["append_to_response" => "credits,watch/providers,recommendations"]);
            if (!film_serie_valide($donnee, true)) {
                throw new TypeError("Film invalide");
            }
            $resultat = detail_general($donnee);

            // Gestion de la collection
            $films_collection = [];
            if (array_key_exists('belongs_to_collection', $donnee) && !is_null($donnee["belongs_to_collection"])) {
                $films_collection = rechercher_collection($donnee["belongs_to_collection"]["id"]);
                unset($films_collection['f' . $id]);  // On retire le film actuel de la collection
            }

            $resultat["nom"] = $donnee["title"];
            $resultat["annee_sortie"] = substr($donnee["release_date"], 0, 4);
            $resultat["collection"] = $films_collection;
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
            $donnee = charger_donnee_api("tv/$id", ["append_to_response" => "credits,watch/providers,recommendations"]);
            if (!film_serie_valide($donnee, false)) {
                throw new TypeError("Série invalide");
            }
            $resultat = detail_general($donnee);

            // Gestion des résumés des saisons
            $saisons = [];
            foreach($donnee["seasons"] as $s) {
                $saisons[$s["season_number"]] = $s["overview"];
            }

            $resultat["nom"] = $donnee["name"];
            $resultat["annee_sortie"] = substr($donnee["first_air_date"], 0, 4);
            $resultat["saisons"] = $saisons;
            $resultat["nb_saisons"] = $donnee["number_of_seasons"];
            $resultat["nb_episodes"] = $donnee["number_of_episodes"];
            $resultat["createur"] = array_map(fn ($el) => $el["name"], $donnee["created_by"]);
        } catch (TypeError $err) {
            # Erreur 404, série non trouvée
            $resultat = [];
        }

        return $resultat;
    }

    function nouveautes_films(): array 
    {
        $contenu_brut = charger_donnee_api("movie/popular")["results"];
        $contenu_filtre = array_filter($contenu_brut, 'film_serie_valide');
        $resultat = [];

        foreach($contenu_filtre as $film) {
            $id = 'f' . $film['id'];
            $resultat[$id] = formater_donnee($film, true);
        }

        return $resultat;
    }

    function nouveautes_series(): array 
    {
        $contenu_brut = charger_donnee_api("tv/popular")["results"];
        $contenu_filtre = array_filter($contenu_brut, 'film_serie_valide');
        $resultat = [];

        foreach($contenu_filtre as $serie) {
            $id = 's' . $serie['id'];
            $resultat[$id] = formater_donnee($serie);
        }

        return $resultat;
    }

    function populaires_series(): array 
    {
        $contenu_brut = charger_donnee_api("tv/top_rated")["results"];
        $contenu_filtre = array_filter($contenu_brut, 'film_serie_valide');
        $resultat = [];

        foreach($contenu_filtre as $serie) {
            $id = 's' . $serie['id'];
            $resultat[$id] = formater_donnee($serie);
        }

        return $resultat;
    }

    function populaires_films(): array 
    {
        $contenu_brut = charger_donnee_api("movie/top_rated")["results"];
        $contenu_filtre = array_filter($contenu_brut, 'film_serie_valide');
        $resultat = [];

        foreach($contenu_filtre as $film) {
            $id = 'f' . $film['id'];
            $resultat[$id] = formater_donnee($film, true);
        }

        return $resultat;
    }

    // print_r(detail_film(453395));
?>