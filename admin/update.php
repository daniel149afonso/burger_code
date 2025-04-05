<?php
    require "database.php";

    if(!empty($_GET['id'])){
        $id = checkInput('id');
    }

    #INIT VAR
    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";
    #CHECK INPUT IF EMPTY
    if(!empty($_POST)){
        #STOCK INPUT DANS VARIABLES 
        $name = checkInput($_POST["name"]);
        $description = checkInput($_POST["description"]);
        $category = checkInput($_POST["category"]);
        $price = checkInput($_POST["price"]);
        #Pour les fichiers
        $image = checkInput($_FILES["image"]["name"]);
        #chemin du fichier + son nom/ basename() 
        $imagePath = "../img/" . basename($image);
        #récupérer l'ext de l'image/ pathinfo()
        $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
        #check boolean
        $isSuccess = true;
        $isUploadSuccess = false;
        #check if empty value
        if(empty($name)){
            $nameError = "Ce champ ne peut pas être vide";
            $isSuccess = false;
        } 
        if(empty($description)){
            $descriptionError = "Ce champ ne peut pas être vide";
            $isSuccess = false;
        }
        if(empty($price)){
            $priceError = "Ce champ ne peut pas être vide";
            $isSuccess = false;
        }
        if(empty($category)){
            $categoryError = "Ce champ ne peut pas être vide";
            $isSuccess = false;
        }

        #Check image features
        if(empty($image)){
            $isImageUpdated = false;
        }
        else{
            $isImageUpdated = true;
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension!= "png" && $imageExtension!= "jpeg" && $imageExtension!= "gif"){
                $imageError = "Les fichiers autorisé sont: .jpg, .jpeg, .png, .gif";
                $isUploadSuccess = false;
            }
            if(file_exists($imagePath)){
                $imageError = "Le fichier existe déjà";
                $isUploadSuccess = false;
            }
            if($_FILES["image"]["size"] > 500000){
                $imageError = "Le fichier ne doit pas depasser les 500KB";
                $isUploadSuccess = false;
            }
            if($isUploadSuccess){
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)){
                    $imageError = " Il y a eu une erreur dans l'upload";
                    $isUploadSuccess = false;
                }
            }
        }
        
        if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated)){
            $db = Database::connect();
            if($isImageUpdated){
                $statement = $db->prepare("UPDATE items set name = ?, description = ?, price = ? ,category = ?, image = ? WHERE id = ?");
                $statement->execute(array($name,$description,$price,$category,$id));
            }
            else{
                $statement = $db->prepare("UPDATE items set name = ?, description = ?, price = ? ,category = ?, WHERE id = ?");
                $statement->execute(array($name,$description,$price,$category,$id));
            }
            Database::disconnect();
            header("location: index.php");
        }
        else if($isImageUpdated && !$isUploadSuccess){
            $image = "";
        }

    }
    
    #1er passage remplit le formulaire avec les valeurs de l'item
    else{
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM items WHERE id = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();
        $name = $item["name"];
        $description = $item["description"];
        $price = $item["price"];
        $category = $item["category"];
        $image = $item["image"];
        Database::disconnect();
    }

    #Check input(space etc..)
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
    <!--TITLE-------------->
    <h1 class="title text-center"><i class="fa-solid fa-utensils"></i>Burger Code <i class="fa-solid fa-utensils"></i></h1>
     <!--ADMIN--------------->
    <div class="container admin">
        <div class="row">
            <div class="col-sm-6">
                <h1>Modifier un item</h1><br>
                <!--FORMULAIRE--------------->
                <form action="update.php" method="post" enctype="multipart/form-data">
                    <!--INFOS--->
                    <label for="name"><strong>Nom:</strong></label><br>
                    <input type="text" name="name" id="name" class="form-control" value=""><br>
                    <span class="help-inline"></span>
                    <!--Description----------->
                    <label for="description"><strong>Description:</strong></label><br>
                    <input type="text" name="description" id="description" class="form-control" value=""><br>
                    <!--Price----------->
                    <label for="price"><strong>Prix:</strong></label><br>
                    <input type="text" name="price" id="price" class="form-control" value=""><br>
                    <!--Category----------->
                    <label for="category"><strong>Catégorie:</strong></label><br>
                    <select class="form-control" name="category" id="category">
                        <?php
                            $db = Database::connect();
                            //A EXPLIQUER
                            foreach($db->query('SELECT * FROM categories') as $row){
                                if($row["id"]== $category){
                                    echo '<option selected="selected" value="' . $row['id'].'">' . $row['name'] . '</option>';

                                }

                                else{
                                    echo '<option value="' . $row['id'].'">' . $row['name'] . '</option>';
                                }
                                
                            }
                            Database::disconnect();
                        ?>
                    </select>
                    <!--IMAGE------->
                    <div class="form-group">
                        <label for="image"><strong>Image:</strong></label><br>
                        <p><?php echo $image?></p>
                        <label for="image">Sélectionner une nouvelle image:</label><br>
                        <input type="file" name="image" id="image" class="form-control" value=""><br>
                    </div>
                    <!--BUTTON SUBMIT AND BACK-->
                    <div class="form-actions"><!--CHECK CLASS--->
                        <button class="btn btn-success" type="submit"><i class="bi bi-pencil-fill"></i> Modifier</button>
                        <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Retour</a>
                    </div>
                </form>
                <!--END FORM------->
           
                
            </div>
            

        </div>
       
    </div>
    
</body>
</html>