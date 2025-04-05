<?php
    require "database.php";
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
            $imageError = "Ce champ ne peut pas être vide";
            $isSuccess = false;
        }
        else{
            $isUploadSuccess = true;
            if($imageExtension!= "jpg" && $imageExtension!= "png" && $imageExtension!= "jpeg" && $imageExtension!= "gif"){
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

        if($isSuccess && $isUploadSuccess){
            $statement = $db->prepare("INSERT INTO items (name,description,price,category,image) values(?,?,?,?,?)");
            $statement->execute(array($name,$description,$price,$category,$image));
            Database::disconnect();
            header("location: index.php");
        }

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
            <h1 class="pb-1"><strong>Ajouter un item</strong></h1>
            <br>
            <!--FORMULAIRE--------------->
            <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data"> <!--Pour les médias telech-->
                <!--INFOS--->
                <label for="name"><strong>Nom:</strong></label>
                <input  id="name" name="name"  type="text"  class="form-control" placeholder="Nom" value="<?php echo $name;?>">
                <span class="help-inline"><?php echo $nameError; ?></span>
                <br>
                <!--Description----------->
                <label for="description"><strong>Description:</strong></label>
                <input id="description" name="description"  type="text" class="form-control" placeholder="Description" value="<?php echo $description;?>">
                <span class="help-inline"><?php echo $descriptionError; ?></span>
                <br>
                <!--Price----------->
                <label for="price"><strong>Prix: (en $)</strong></label>
                <input id="price" name="price"  type="number" step="0.01" class="form-control" placeholder="Prix" value="<?php echo $price;?>">
                <span class="help-inline"><?php echo $priceError; ?></span>
                <br>
                <!--Category----------->
                <label for="category"><strong>Catégorie:</strong></label>
                <select class="form-control" name="category" id="category">
                    <?php
                        $db = Database::connect();
                        foreach($db->query("SELECT * from items") as $row){
                            echo '<option value="' .$row['id'] . '">'. $row['name'] . '</option> >';
                        }

                        Database::disconnect();
                    
                    ?>
                </select>
                <span class="help-inline"><?php echo $categoryError; ?></span>
                <br>
                <!--SELECT IMAGE--------->
                <label for="image"><strong>Sélectionner une image:</strong></label>               
                <br>
                <input id="image" name="image" type="file" value="Choose File" accept="image/*, .png, .jpg, jpeg, .gif" >
                <span class="help-inline"><?php echo $imageError; ?></span>
                <br><br>
                <!--BUTTON SUBMIT AND BACK-->
                <div class="form-actions"><!--CHECK CLASS--->
                    <button class="btn btn-success" type="submit"><i class="bi bi-pencil-fill"></i> Ajouter</button>
                    <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Retour</a>
                </div>
            </form>
            <!--END OF FORM----------->
            
        </div>

    </body>
</html>