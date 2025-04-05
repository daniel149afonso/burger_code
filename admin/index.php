<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Burger Menu</title>
        <!--CSS FILE-->
        <link rel="stylesheet" href="../style.css">
        <!--BOOTSTRAP LIBRARY----------->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <!--BOOTSTRAP ICONS LIBRARIES------->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
        <!---FONT AWESOME--------------->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!--GOOGLE FONTS HOTLINE-------->
        <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&family=Lato&display=swap" rel="stylesheet">
    </head>

    <body> 
        <h1 class="title text-center"><i class="fa-solid fa-utensils"></i>Burger Code <i class="fa-solid fa-utensils"></i></h1>
        <div class="container admin">
            <div class="row p-3">
                <h1 class="pb-3"><strong>Liste des items</strong> <a href="insert.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> Ajouter</a></h1>
                
                <!--TABLEAU------------>
                <table class="table table-striped table-bordered">
                    <!--TETE-------------->
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!--FIN TETE------------>

                    <!--CORPS----------------->
                    <tbody>
                        <!--PHP CODE AFFICHAGE----------->
                        <?php
                            #Inclue le fichier de connexion
                            require "database.php";
                            #Stock connexion variable
                            $db= Database::connect();
                            #Stock instruction SQL
                            $statement = $db->query("SELECT items.id, items.name, items.description, items.price, categories.name AS category
                            FROM items LEFT JOIN categories ON items.category = categories.id
                            ORDER BY items.id DESC");

                            while($item = $statement->fetch()){

                            #affichage 1 ligne d'enregistrement par boucle
                            echo '<tr>';
                            echo    '<td>' .$item['name']. '</td>';
                            echo    '<td>' .$item['description'].'</td>';
                            echo    '<td>' .number_format((float)$item['price'],2,".", "").'</td>';#fonction number pour laisser 2 chiffre after virg
                            echo    '<td>' .$item['category']. '</td>';
                            echo    '<td width=400>';#taille case buttons
                            #Bouton Voir
                                echo    '<a class="btn btn-light" href="view.php?id=' .$item['id']. '"><i class="fa-solid fa-eye"></i> Voir</a>';
                                echo " ";
                            #Bouton Modifier
                                echo    '<a class="btn btn-primary" href="update.php?id=' .$item['id']. '"><i class="bi bi-pencil-fill"></i> Modifier</a>';        
                                echo " ";  
                            #Bouton Supprimer
                                echo    '<a class="btn btn-danger" href="delete.php?id=' .$item['id']. '"> <i class="fa-regular fa-trash-can"></i></i> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                            
                        }     
                        #Deconnexion database après affichage
                        Database::disconnect();
                        ?>   
                    </tbody>
                    <!--FIN CORPS------------------->
                </table>
                <!--FIN TABLEAU-------------------->

            </div>
        </div>

    </body>
</html>