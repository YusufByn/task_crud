<?php

// require_once : inclut/requiert ce fichier suivant 
// ici c'est add_task.php qui contient une fonction qui me permet de connecter/relié a ma base de données tasks
// pourquoi Once ? (une fois), c'est pour éviter le fait de recharger la base de données et donc créer des bugs
require_once "config/add_task.php";

$errors = [];
// 1 ) Create du CRUD, traitement des données etc
// condition pour vérifier si on a recu une request en post (formulaire)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $formTitle = htmlspecialchars(trim($_POST["title"] ?? ""));
    $formDescription = htmlspecialchars(trim($_POST["description"] ?? ""));
    $formStatus = htmlspecialchars(trim($_POST["status"] ?? ""));
    $formPriority = htmlspecialchars(trim($_POST["priority"] ?? ""));
    $formDate = trim($_POST["due_at"] ?? "");

    // validation du titre
    if (empty($formTitle)) {
        $errors[] = "Le titre est obligatoire.";
    }
    // validation de la description
    if (empty($formDescription)) {
        $errors[] = "La description est obligatoire.";
    }

    $formDate = trim($_POST['due_at'] ?? '');

    if ($formDate !== '') {
    $date = DateTime::createFromFormat('Y-m-d', $formDate);
    if (!$date || $date->format('Y-m-d') !== $formDate) {
        // date invalide
        $errors[] = "La date n'est pas valide.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority, due_at, created_at, updated_at) 
        VALUES (?, ?, ?, ?, NOW(), NOW())");
        // les deux derniers champs created_at et updated_at sont automatiquement remplis par la fonction SQL NOW()
        $stmt->execute([$formTitle, $formDescription, $formPriority, $formDueAt]);
        // eventuellement redirection pour éviter le repost (header('Location: ...'))
    }

}

// 2) LE READ (SELECT) POUR RECUPERER ET AFFICHER MES DONNEES:
// je déclare une variable $pdo (qui est dans add_task mais pas ici) et je lui rappelle la fonction linkDatabase
$pdo = linkDatabase();

// je déclare ma variable tableElements, qui contient la variable pdo 
// qui fait une requete pour aller chercher TOUT depuis le tableau task
// au débutant j'avais juste select title et description et une fois que j'ai réussi j'ai fait all
$tableElements = $pdo->prepare("SELECT * FROM tasks");

// execute sert à lancer/executer la requête 
// donc en gros : il est parti les chercher mais ne les pas a encore récupérer 
$tableElements->execute();

// je déclare une variable $tasks qui est toutes les données
// que je suis allé récupérer et à qui je lui fais une requête pour qu'elle récupére les données
$tasks = $tableElements->fetchAll();



?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The amazing to do list</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- insertion de mon header directement via une page header.php qui contient les elements nécessaire
    j'utilise include pour inclure cette page la ici  -->
    <?php include "headerandfooter/header.php";?>
    <main>
        <section class="formContainer">
            <form method="POST">
                <input type="text" name="title" required>
                <textarea name="description" required></textarea>

                <select name="status">
                    <option value="" disabled selected>Status</option>
                    <option value="pending">À faire</option>
                    <option value="in_progress">En cours</option>
                    <option value="done">Terminée</option>
                </select>

                <select name="priority">
                    <option value="" disabled selected>Prioritée</option>
                    <option value="low">Basse</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                </select>

                <input type="date" name="due_at">

                <input type="submit" value="Créer">
            </form>
        </section>
        <section>
            <h2>Voici votre to-do list :</h2>
            <div class="tasksContainer">
                <?php 
                // après avoir récupérer les données dans la base de données 
                // si les taches(tasks) ne sont pas vides, tu me fais une boucle ou les taches sont devenu
                // une tache, tu m'écris dans un article : le titre de la tache ainsi que sa description
                    if(!empty($tasks)) {
                        foreach ($tasks as $task) {
                            echo "
                            <article>
                                <h3>$task[title]</h3>
                                <p> Description de la tâche : $task[description]</p>
                                <p> Statut de la tâche : $task[status]</p>
                                <p> Prioritée de la tâche : $task[priority]</p>
                                <p> Date d'échéance : $task[due_date]</p>
                                <p> Crée le : $task[created_at]</p>
                                <p> Mis à jour le : $task[updated_at]</p>
                            </article>
                            ";
                        }
                    }
                ?>
        </section>
    </main>
    <!-- insertion de mon footer directement via une page footer.php qui contient les elements nécessaire
    j'utilise include pour inclure cette page la ici  -->
    <?php include "headerandfooter/footer.php"; ?>
</body>
</html>