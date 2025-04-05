<?php
#inclue database.php
require "database.php";

if(!empty($_GET["id"])){

    $id = checkInput($_GET["id"]);
}
#Stock connexion variable
$db = Database::connect();
#Instruction SQL avec 'prepare'
$statement = $db->prepare('SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category
FROM items LEFT JOIN categories ON items.category = categories.id WHERE items.id = ?');

#Execute le tableau
$statement->execute(array($id));

#Affichage 1 item
$item = $statement->fetch();
#Deconnexion Database après affichage
Database::disconnect();

function checkInput($data){
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;

}


?>

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
                <div class="col-md-6">
                    <h1 class="pb-1"><strong>Voir un item</strong></h1>
                    <br>
                    <form>
                        <div class="form-group"><!--CHECK class-->
                            <label>Nom:</label><?php echo " " . $item["name"];?>
                            <br> <br>
                        </div>
                        <div class="form-group">
                            <label>Description:</label><?php echo " " . $item["description"];?>
                            <br> <br>
                        </div>
                        <div class="form-group"> 
                            <label>Prix:</label><?php echo " " .number_format((float)$item['price'],2,".", "");?>
                            <span> $</span>
                            <br> <br>
                        </div>
                        <div class="form-group">
                            <label>Catégorie:</label><?php echo " " . $item["category"];?>
                            <br> <br>
                        </div>
                        <div class="form-group">
                            <label>Image:</label><?php echo " " . $item["image"];?>
                        </div>
                    </form>
                    <br>
                    <div class="form-actions"><!--CHECK class-->
                        <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Retour</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card img-thumbnail mb-4">
                        <img class="bg-warning" src="<?php echo "../img/$item[image]"  ; ?>" alt="">  
                        <div class="price"><?php echo number_format((float)$item['price'],2,".", "") ?> $</div>                      
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $item["name"]; ?></h5>
                            <p class="card-text"><?php echo $item["description"]; ?></p>
                            <a href="#" class="btn btn-primary" ><i class="bi bi-cart2"></i> Commander</a>
                        </div>
                    </div> 
                </div>

            </div><!--END ROW---------->
               

            </div>
        </div>

    </body>
</html>