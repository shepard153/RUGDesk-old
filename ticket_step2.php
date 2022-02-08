<?php
require("includes/connect.php");

$dzial = $_GET['dzial'];

$ipaddress = $_SERVER['REMOTE_ADDR'];
$domain = gethostbyaddr($ipaddress);

if (strpos($domain, ".carcgl.com"))
{
    $domain = str_replace(".carcgl.com", "", $domain);
}

if(isset($_POST['submit'])) {
                    
    $status = 0;

    $stmt = $conn->prepare("INSERT INTO TicketList (nazwa, dzial, problem, linia, stanowisko, wiadomosc, priorytet, status)
                            VALUES (:nazwa, :dzial, :problem, :linia, :stanowisko, :wiadomosc, :priorytet ,:status)");
    $stmt->bindValue(':nazwa', $domain);                                           
    $stmt->bindValue(':dzial', $dzial);
    $stmt->bindValue(':problem', $_POST['problemSelect']);
    $stmt->bindValue(':linia', $_POST['liniaSelect']);
    $stmt->bindValue(':stanowisko', $_POST['stanowiskoSelect']);
    $stmt->bindValue(':wiadomosc', $_POST['wiadomosc']);
    $stmt->bindValue(':priorytet', $_POST['priorytetSelect']);
    $stmt->bindValue(':status', $status);

    $stmt->execute();

    $id = $conn->lastInsertId();
        
    header("Location: ticket_sent.php?id=$id");
}
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
        <div class="row justify-content-end">
            <form class="row" method="post">
                <div class="col-5 offset-md-1">
                    <div class="form-group">
                        <label class="form-label">Nazwa komputera</label>
                        <input type="text" name="fullname" value="<?php echo $domain;?>" class="form-control" readonly>
                    </div>	
                    <div class="form-group top-margin">
                        <label class="form-label">Obszar/dział <span style="color:red">*</span></label>
                        <select id="liniaSelect" name="liniaSelect" class="form-select form-select-lg mb-3" onchange="check()" required>
                            <option value="">Wybierz obszar</option>
                            <option value="Linia 1">Linia 1</option>
                            <option value="Linia 2">Linia 2</option>
                            <option value="Linia 3">Linia 3</option>
                            <option value="Linia 4">Linia 4</option>
                            <option value="Linia 5">Linia 5</option>
                            <option value="Panele">Panele</option>
                            <option value="Przedmontaże">Przedmontaże</option>
                            <option value="RUG2">RUG 2</option>
                            <option value="RUG3">RUG 3</option>
                            <option value="Magazyn">Magazyn</option>
                            <option value="Quality">Quality</option>
                            <option value="Biura">Biura - produkcja</option>
                            <option value="Logistyka">Logistyka</option>
                            <option value="Utrzymanie ruchu">Utrzymanie ruchu</option>
                            <option value="Tempimetodi">Tempimetodi</option>
                            <option value="Pozostałe">Pozostałe</option>
                        </select>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Stanowisko <span style="color:red">*</span></label>
                        <select id="stanowiskoSelect" name="stanowiskoSelect" class="form-select form-select-lg mb-3" onchange="check2()" disabled required>
                            <option value="">Wybierz stanowisko</option>
                            <!-- Opcje wyboru zaciągane z tabeli JS w zależności od wybranego działu. -->
                        </select>
                    </div>
                    <div class="form-group top-margin">
                        <label class="form-label">Problem<span style="color:red">*</span></label>
                        <select id="problemSelect" name="problemSelect" class="form-select form-select-lg mb-3" disabled required>
                            <option value="">Wybierz problem</option>
                            <!-- Opcje wyboru zaciągane z tabeli JS w zależności od wybranego działu. -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Wiadomość (max 500 znaków) (opcjonalnie)</label><br/>
                        <textarea class="form-control" name="wiadomosc" maxlength="500"></textarea>
                    </div>
                    <div class="form-group top-margin">
                        <input id="submit" name="submit" class="btn btn-lg btn-primary" type="Submit" disabled/>
                    </div>
                </div>
                <div class="col-5 ie11-margin">
                    <div class="form-group">
                        <label class="form-label">Priorytet (opcjonalnie)</label>
                        <select id="priorytetSelect" name="priorytetSelect" class="form-select form-select-lg mb-3">
                            <option value="0">Powiadomienie</option>
                            <option value="2" default selected>Średni</option>
                            <option value="3">Wysoki</option>
                            <option value="4">Krytyczny</option>
                        </select>
                    </div>
                    <div id="info" class="form-group alert alert-info text-center">
                        <table class="table">
                            <thead>
                                <td>Priorytet</td>
                                <td>Skutek</td>
                            </thead>
                            <tr>
                                <td>Powiadomienie</td>
                                <td>Uwagi, pomysły, usprawnienia, modyfikacje, utrudnienia, itd.</td>
                            </tr>
                            <tr>
                                <td>Średni</td>
                                <td>PRODUKCJA NIE JEST ZAGROŻONA - Usterka powoduje znaczne utrudnienia dla procesu produkcyjnego.</td>
                            </tr>
                            <tr>
                                <td>Wysoki</td>
                                <td>WYSOKIE RYZYKO ZATRZYMANIA PRODUKCJI - Interwencja musi być podjęta najszybciej jak to tylko możliwe.</td>
                            </tr>
                            <tr>
                                <td>Krytyczny</td>
                                <td>PRODUKCJA ZATRZYMANA - Wymaga natychmiastowej interwencji.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function val() {
            linia = document.getElementById("liniaSelect").value;
            if (linia != ""){return true;}
            else {return false;}
        }
        function val2() {
            stanowisko = document.getElementById("stanowiskoSelect").value;
            if (stanowisko != ""){return true;}
            else {return false;}
        }

        function stanowiskoFill(stanowisko){
            $("#stanowiskoSelect").empty();
            select = document.getElementById("stanowiskoSelect");
            var opt = document.createElement('option');
                opt.value = null;
                opt.innerHTML = "Wybierz stanowisko";
                select.appendChild(opt);

            for (var i = 0; i<stanowisko.length; i++){
                    select = document.getElementById("stanowiskoSelect");
                    var opt = document.createElement('option');
                    opt.value = stanowisko[i];
                    opt.innerHTML = stanowisko[i];
                    select.appendChild(opt);
                }
        }

        function check() {
            if (val() == true){$("#stanowiskoSelect").removeAttr("disabled");}

            produkcja = ["Komory", "Linia montażowa", "Collaudo", "Gwarancje", "Pakowanie", "Pozostałe stanowiska komputerowe"];
            produkcja2 = ["Grupy hydrauliczne", "Panele", "Pozostałe stanowiska komputerowe"];
            produkcja3 = ["Linia montażowa", "Pakowanie", "Pozostałe stanowiska komputerowe"];
            magazynierzy = ["Magazyn dostaw", "Magazyn hurtownia", "Magazyn spedycje", "Pozostałe stanowiska komputerowe"];
            zieloni = ["Laboratorium", "Jakość", "Pomiary 3D", "Pozostałe stanowiska komputerowe"];
            biura = ["Gwarancje", "Biura", "Pozostałe stanowiska komputerowe"];
            logistyka = ["Logistyka", "Pozostałe stanowiska komputerowe"];
            utrzymanie = ["Utrzymanie ruchu", "Pozostałe stanowiska komputerowe"];
            tempimetodi = ["Tempimetodi", "Pozostałe stanowiska komputerowe"];
            pozostałe = ["Pozostałe stanowiska komputerowe"];

            obszar = document.getElementById("liniaSelect").value;

            if (obszar == "Linia 1" || obszar == "Linia 2" || obszar == "Linia 3" || obszar == "Linia 4" || obszar == "Linia 5" || obszar == "RUG2" || obszar == "RUG3"){
                stanowiskoFill(produkcja);
            }
            else if (obszar == "Panele"){
                stanowiskoFill(produkcja2);                
            }
            else if (obszar == "Przedmontaże"){
                stanowiskoFill(produkcja3);                
            }
            else if (obszar == "Magazyn"){
                stanowiskoFill(magazynierzy);                
            }
            else if (obszar == "Quality"){
                stanowiskoFill(zieloni);                
            }
            else if (obszar == "Biura"){
                stanowiskoFill(biura);                
            }
            else if (obszar == "Logistyka"){
                stanowiskoFill(logistyka); 
            }
            else if (obszar == "Utrzymanie ruchu"){
                stanowiskoFill(utrzymanie); 
            }
            else if (obszar == "Tempimetodi"){
                stanowiskoFill(tempimetodi); 
            }
            else{
                stanowiskoFill(tempimetodi); 
            }            
        }

        function check2(){
            if (val2() == true){$("#problemSelect").removeAttr("disabled");}
            if (val() == true && val2() == true){
                $("#submit").removeAttr("disabled");
            }
            else if (val() == false || val2() == false){
                $("#submit").attr( "disabled", "disabled" );
            }
            $("#problemSelect").empty();
            stanowisko = document.getElementById("stanowiskoSelect").value;

            var allSQL = {
                <?php
                    $problemList = $conn->prepare("SELECT * FROM Problems WHERE dzial = :dzial");
                    $problemList->bindValue(':dzial', $dzial);
                    $problemList->execute();

                    while($row = $problemList->fetch( PDO::FETCH_ASSOC )) {
                        $problem = $row['value'];
                        $tag = $row['tag'];
                        echo "'$problem':'$tag',";
                    }
                ?>};

            for (let i in allSQL ){
                if (allSQL.hasOwnProperty(i)) {
                    if (allSQL[i].indexOf(stanowisko) !== - 1){
                        select = document.getElementById("problemSelect");
                        var opt = document.createElement('option');
                        opt.value = i;
                        opt.innerHTML = i;
                        select.appendChild(opt);
                    }
                }
            }
        }
    </script>
    </body>
</HTML>
