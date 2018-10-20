<?php

// chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
$uploadDir = 'upload/';

$error = 0;
//echo '<pre>';
//var_dump($_FILES['file']['name']);
//echo '</pre>';
if(isset($_FILES) && !empty($_FILES)) {

    $fileNames = $_FILES['file']['name'];
    //var_dump($filenames);
    foreach ($fileNames as $fileName) {
        $fileExtension = strrchr($fileName, ".");
        $extensionAllow = array(".jpg", ".png", ".gif");
        if (!in_array($fileExtension, $extensionAllow)) {
            $errorMessage = "seuls les fichiers .jpg, .png et .gif sont autorisés";
            $error++;
        }
    }

    $fileTmp_names = $_FILES['file']['tmp_name'];
    //var_dump($fileTmp_names);
    foreach ($fileTmp_names as $fileTmp_name) {
        $taille = filesize($fileTmp_name);
        if ($taille > 1000000 || $_FILES['file'] = false) {
            $errorMessage = "La taille de fichier ne doit pas être supérieur à 1 MO";
            $error ++;
        }
    }



    if ($error == 0) {

        foreach ($fileNames as $fileName) {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION); // on récupère l'extension, exemple "pdf"
            $newFileNames [] = 'image' . uniqid() . '.' .$extension; // concatène nom de fichier unique avec l'extension
        }

        foreach ($newFileNames as $newFileName) {
            // on génère un nom de fichier à partir du nom de fichier sur le poste du client (mais vous pouvez générer ce nom autrement si vous le souhaitez)
            $uploadFiles[] = $uploadDir . basename($newFileName);
        }
        //var_dump($fileTmp_names);
        //var_dump($uploadFiles);
        foreach ($uploadFiles as $key => $uploadFile) {
            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ca y est, le fichier est uploadé
            move_uploaded_file($fileTmp_names[$key], $uploadFile);
        }
    }
}
    if (isset($_POST['name'])) {
        unlink('upload/' . $_POST['name']);
        header('Location : form.php');
    }

$arrayFiles = (scandir($uploadDir, 1));
array_pop($arrayFiles);
array_pop($arrayFiles);

//var_dump($uploadFiles);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>upload files</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <form action="" method="post" enctype="multipart/form-data">

            <input type="file" name="file[]" multiple="multiple"/><br/><br/>
            <input type="submit" value="Send" />
        </form>
    </div>

    <div class="row mt-5 justify-content-center">
        <?php if ($error != 0) {
            echo $errorMessage;
        }
        ?>
    </div>
    <div class="row mt-5 justify-content-center">

        <?php
        foreach ($arrayFiles as $name) { ?>
            <div class="col-3">
                <img class="img-thumbnail" src="upload/<?php echo $name ?>">
                <?php echo $name; ?>
                <br/>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?php echo $name; ?>" >
                    <button type="submit">DELETE</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>

