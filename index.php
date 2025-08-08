<?php

// require_once : inclut/requiert ce fichier suivant 
// ici c'est add_task.php qui contient une fonction qui me permet de connecter/relié a ma base de données tasks
// pourquoi Once ? (une fois), c'est pour éviter le fait de recharger la base de données et donc créer des bugs
require_once "config/add_task.php";


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