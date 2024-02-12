<?php

    //IS RECEIVED SHORTCUT

    if(isset($_GET['q'])){
        //VARIABLE 
        $shortcut = htmlspecialchars($_GET['q']);
        //IS A SHORTCUT ?
        $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root', '');
        $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
        $req->execute(array($shortcut));
        while($result = $req->fetch()) {
            if ($result['x'] !=1) {
                header('location: ../raccourcisseur/?error=true&message=Adresse url non connu');
        exit();
            }
         }

    //REDIRECTION

    $req = $bdd->prepare('SELECT * from links WHERE shortcut = ? ');
    $req->execute(array($shortcut));

    while($result = $req->fetch()) {

        header('location: '.$result['url']);
        exit();
    }
}
    
    //IS SENDING A FORM
    if (isset($_POST['url'])) {
        // VARIABLE
        $url = $_POST['url'];

        // VERIFICATION
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            // PAS UN LIEN
            header('location: ../raccourcisseur/?error=true&message=Adresse url non valide');
            exit();
        }
        // SHORTCUT
        $shortcut = crypt($url,rand());

        // HAS BEEN ALREADY SEND ?
        $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root', '');
      
        $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
        $req->execute(array($url));

        while($result = $req->fetch()) {
                if ($result['x'] != 0) {
                    header('location: ../raccourcisseur/?error=true&message=Adresse url non valide');
            exit();
            }
        }
        //SENDING
        $req = $bdd->prepare('INSERT INTO links (url,shortcut) VALUES (?,?)');

        $req->execute(array($url, $shortcut));
        header('location: ../raccourcisseur/?short='.$shortcut);
        exit();
        
    }

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="design/default.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="pictures/favico.png" />
    <title>Raccourcisseur</title>
</head>

<body>
    <!--Présentation-->
    <section id="hello">
        <!--Container-->
        <div class="container">
            <header>
                <a href="../raccourcisseur/">
                    <img src="pictures/logo.png" alt="logo" id="logo" />
                </a>
            </header>
            <!-- VP -->
            <h1>Une url longue ? Raccourcissez-là</h1>
            <h2>Largement meilleur et plus court que les autres</h2>
            <!--Formulaire-->
            <form method="post" action="../raccourcisseur/">
                <input type="url" name="url" placeholder="Collez un lien à raccourcir">
                <input type="submit" value="Raccourcir">
            </form>
            <?php
                if(isset($_GET['error']) && isset($_GET['message'])) {
                    ?>
            <div class="center">
                <div id="result">
                    <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                </div>
            </div>
            <?php } else if (isset($_GET['short'])){
                ?>
            <div class="center">
                <div id="result">
                    <b>URL : RACCOURCIE</b>
                    <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/raccourcisseur/?q='.htmlspecialchars($_GET['short']); ?>"
                        target="_blank">
                        Cliquez ici
                    </a>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </section>
    <!--BRANDS -->
    <section id="brands">
        <div class="container">
            <h3>Ces marques nous font confiance</h3>
            <img src="pictures/1.png" alt="1" class="pictures">
            <img src="pictures/2.png" alt="2" class="pictures">
            <img src="pictures/3.png" alt="3" class="pictures">
            <img src="pictures/4.png" alt="4" class="pictures">
        </div>
    </section>
    <!--FOOTER-->
    <footer>
        <img src="pictures/logo2.png" alt="logo" id="logo"></br>
        2023 © Bitly
        <a href="#">Contact</a> - <a href="#"> À propos</a>
    </footer>
</body>

</html>