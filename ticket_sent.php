<?php
require("includes/connect.php");
?>

<HTML>
    <head>
        <meta charset="UTF-8" lang="pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="bootstrap-ie11.min.css"><script src="element-qsa-scope@1.js"><\/script>');</script>
        <style type="text/css">
            _:-ms-fullscreen, :root .col { flex: 1 0 auto; } /* Poprawka dla IE11. Bez tego, przeglądarka ustawia domyślną szerokość pól na 1% */
            .top-margin{margin-top: 0.7vw;}
            @media all and (-ms-high-contrast:none)
            {
            *::-ms-backdrop, .ie11-margin { margin-left: 1vw;}
            }
            select::-ms-expand {
                display: none;
            }
            button{
                margin-left: 2%;
            }
        </style>
        <title>RUGDesk</title>
    </head>
    <body>
        
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <a class="navbar-brand" href="index.php">Menu</a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Zgłoś problem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff.php">Panel admina</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="padding-right: 1vw; margin-top:1%">

        <?php
            if(isset($_GET['id'])) {
                    
                echo '<div class="alert alert-success text-center"><h4>Pomyślnie dodano zgłoszenie o numerze <strong>'.$_GET['id'].'</strong>.</h4> <h2><a href="index.php" onClick="window.close();">Zamknij okno</a></h2></div>';
        
            }else {
                header("Location: 404.php");
        }
        ?>

    </div>
    </body>
</HTML>