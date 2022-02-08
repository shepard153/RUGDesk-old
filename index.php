<HTML>
    <head>
        <meta charset="UTF-8" lang="pl"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script nomodule>window.MSInputMethodContext && document.documentMode && document.write('<link rel="stylesheet" href="assets/css/bootstrap-ie11.min.css"><script src="assets/js/element-qsa-scope@1.js"><\/script>');</script>
        <style type="text/css">
            _:-ms-fullscreen, :root .col { flex: 1 0 auto; } /* Poprawka dla IE11. Bez tego, przeglądarka ustawia domyślną szerokość pól na 1% */
            .top-margin{margin-top: 2vw;}
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
                            <a class="nav-link active" aria-current="page" href="#">Zgłoś problem</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="staff.php">Panel admina</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-md-center top-margin">
            <div class="alert alert-danger text-center"><h4>System testowy - pomimo wysłanego zgłoszenia poinformuj przełożonego.</h4></div>
                <p class="fs-4 border-bottom text-center">Wybierz dział z którym chcesz się skontaktować.</p>
            </div>
            <div class="row justify-content-md-center top-margin">
                <div class="col-3">
                    <a href="ticket_step2.php?dzial=IT"><img src="assets/img/informatyka.jpg" class="rounded" width="250" height="250" alt="Informatyka"></a>
                </div>
            <!--    <div class="col-3">
                    <a href="ticket_step2.php?dzial=UR"><img src="assets/img/UR.jpg" class="rounded" width="250" height="250" alt="Utrzymanie Ruchu"></a>
                </div>
                <div class="col-3">
                    <a href="ticket_step2.php?dzial=Magazyn"><img src="assets/img/magazyn.jpg" class="rounded" width="250" height="250" alt="Magazyn"></a>
                </div>
            </div>
            <div class="row justify-content-center top-margin">
                <div class="col-3">
                    <a href="ticket_step2.php?dzial=TM"><img src="assets/img/tempimetodi.jpg" class="rounded" width="250" height="250" alt="Tempimetodi"></a>
                </div>
                <div class="col-3">
                    <a href="ticket_step2.php?dzial=Hurtownia"><img src="assets/img/hurtownia.jpg" class="rounded" width="250" height="250" alt="Hurtownia"></a>
                </div> -->
            </div>
        </div>
    </body>
</HTML>