<?php

// logique de connexion à la database

// information pour se connecter
// l'endroit ou est ma database

// function qui crée et renvoi une connexion a la db
function linkDatabase() {
    $host = "localhost";
    // nom de la db
    $dbname = "task_crud";
    // identifiant de connexion
    $username = "root";
    // mdp de connexion
    $password = "";
    // port
    $port = 3306;
    // encodage
    $charset = "utf8mb4";

    try {
        // mes paramètres de co
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$port";
        $pdo = new PDO($dsn, $username, $password);
        // comment récuperer les exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // comment me renvoyer les données
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOExecption $e) {
        die("Erreur durant la connexion à la dataBase" . $e->getMessage());
    }
}

// toujours faire un var_dump pour voir si ca fonctionne
// var_dump(linkDatabase());
?>