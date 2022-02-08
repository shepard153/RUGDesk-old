<?php
include("includes/staff_template.php");

$ticketID = $_GET["ticketID"];
$sql = $conn->prepare("SELECT * FROM TicketList WHERE ticketID = :ticketID"); 
$sql->bindParam(':ticketID', $ticketID);
$sql->execute();
$ticket = $sql->fetch(PDO::FETCH_ASSOC);

$notes = $conn->prepare("SELECT * FROM Notes WHERE ticketID = :ticketID ORDER BY data_dodania DESC");
$notes->bindParam(':ticketID', $ticketID);
$notes->execute();
$notesCount = $notes->rowCount();

$history = $conn->prepare("SELECT * FROM TicketHistory WHERE ticketID = :ticketID ORDER BY data_modyfikacji DESC");
$history->bindParam(':ticketID', $ticketID);
$history->execute();
$historyCount = $history->rowCount();

function autoHistoryUpdate($conn, $ticketID, $modyfikacja){
    $autoHistory = $conn->prepare("INSERT INTO TicketHistory (ticketID, uzytkownik, modyfikacja) VALUES (:ticketID, :uzytkownik, :modyfikacja)");
    $autoHistory->bindParam(':ticketID', $ticketID);
    $autoHistory->bindParam(':uzytkownik', $_SESSION['username']);
    $autoHistory->bindParam(':modyfikacja', $modyfikacja);
    $autoHistory->execute();
}

if(isset($_POST['addNote']))
{
    $addNote = $conn->prepare("INSERT INTO Notes (ticketID, username, tresc) VALUES (:ticketID, :username, :tresc)");
    $addNote->bindParam(':ticketID', $ticketID);
    $addNote->bindParam(':username', $_SESSION['username']);
    $addNote->bindParam(':tresc', $_POST['notatka']);
    $addNote->execute();
    $success[] = "Pomyślnie dodano notatkę.";
}

if(isset($_POST['editTicket']))
{
    $editTicket = $conn->prepare("UPDATE TicketList SET priorytet = :priorytet, dzial = :dzial, problem = :problem WHERE ticketID = :ticketID");
    $editTicket->bindParam(':priorytet', $_POST['priorytetSelect']);
    $editTicket->bindParam(':dzial', $_POST['dzialSelect']);
    $editTicket->bindParam(':problem', $_POST['problemSelect']);
    $editTicket->bindParam(':ticketID', $ticketID);
    $editTicket->execute();
    $success[] = "Pomyślnie edytowano zgłoszenie.";

    $editHistory = $conn->prepare("INSERT INTO TicketHistory (ticketID, uzytkownik, modyfikacja) VALUES (:ticketID, :uzytkownik, :modyfikacja)");
    $modyfikacja = "";

    if ($_POST['priorytetSelect'] != $ticket['priorytet']){
        $modyfikacja = "Zmieniono priorytet z ".$ticket['priorytet']." na ".$_POST['priorytetSelect'].".";
        $editHistory->bindParam(':ticketID', $ticketID);
        $editHistory->bindParam(':uzytkownik', $_SESSION['username']);
        $editHistory->bindParam(':modyfikacja', $modyfikacja);
        $editHistory->execute();
    }
    if ($_POST['dzialSelect'] != $ticket['dzial']){
        $modyfikacja = "Zmieniono dział z ".$ticket['dzial']." na ".$_POST['dzialSelect'].".";
        $editHistory->bindParam(':ticketID', $ticketID);
        $editHistory->bindParam(':uzytkownik', $_SESSION['username']);
        $editHistory->bindParam(':modyfikacja', $modyfikacja);
        $editHistory->execute();
    }
    if ($_POST['problemSelect'] != $ticket['problem']){
        $modyfikacja = "Zmieniono problem z ".$ticket['problem']." na ".$_POST['problemSelect'].".";
        $editHistory->bindParam(':ticketID', $ticketID);
        $editHistory->bindParam(':uzytkownik', $_SESSION['username']);
        $editHistory->bindParam(':modyfikacja', $modyfikacja);
        $editHistory->execute();
    }   
}

if(isset($_POST['takeTicket']))
{
    $status = 1;
    $takeTicket = $conn->prepare("UPDATE TicketList SET status=:status, data_podjecia = GETDATE() WHERE ticketID = :ticketID");
    $takeTicket->bindParam(':status', $status);
    $takeTicket->bindParam(':ticketID', $ticketID);
    $takeTicket->execute();

    $modyfikacja = "Zgłoszenie podjęte";
    autoHistoryUpdate($conn, $ticketID, $modyfikacja);
    $success[] = "Zgłoszenie podjęte.";
}

if(isset($_POST['changeStatus']))
{
    switch ($ticket['status']){
        case 1:
            $status = 2;
            $updateTicket = $conn->prepare("UPDATE TicketList SET status=:status, data_zamkniecia = GETDATE() WHERE ticketID = :ticketID");
            $modyfikacja = "Zgłoszenie zamknięte";
            autoHistoryUpdate($conn, $ticketID, $modyfikacja);
            $success[] = "Zamknięto zgłoszenie.";
            break;
        case 2:
            $status = 1;
            $updateTicket = $conn->prepare("UPDATE TicketList SET status=:status, data_zamkniecia = NULL WHERE ticketID = :ticketID");
            $modyfikacja = "Zgłoszenie otwarte ponownie";
            autoHistoryUpdate($conn, $ticketID, $modyfikacja);
            $success[] = "Zgłoszenie zostało ponownie otwarte.";
            break;
        default:
            break;
        }
    $updateTicket->bindParam(':status', $status);
    $updateTicket->bindParam(':ticketID', $ticketID);
    $updateTicket->execute();
}

$date_closed  = new DateTime($ticket['data_zamkniecia']);
$date_closed->add(new DateInterval('P2D'));
$date_now = new DateTime('NOW');
$countdown = $date_now->diff($date_closed, true);
?>

<div class="container-fluid" style="background:#F2F2F2; overflow: auto;">
    <div class="row">
      <div class="col ">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Zgłoszenia</p>        
        <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
            <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Szczegóły zgłoszenia</p>
            <?php 
				if(isset($success) && count($success) > 0)
				{
					foreach($success as $success_msg)
					{
						echo '<div class="alert alert-success">'.$success_msg.'</div>';
                        echo '<meta http-equiv="refresh" content="2" />';
					}
				}
			?>
            
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-ticket-tab" data-bs-toggle="tab" data-bs-target="#nav-ticket" type="button" role="tab" aria-controls="nav-ticket" aria-selected="true">Zgłoszenie</button>
                    <button class="nav-link" id="nav-note-tab" data-bs-toggle="tab" data-bs-target="#nav-note" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Dodaj notatkę</button>
                    <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history" type="button" role="tab" aria-controls="nav-note" aria-selected="false">Historia zgłoszenia</button>
                </div>
            </nav>
                
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-ticket" role="tabpanel" aria-labelledby="nav-ticket-tab">
                    <form method="post">
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <span class="fs-5">Data utworzenia <?php echo $ticket['data_zgloszenia']; ?></span>
                            <span class="fs-5" style="margin-left: 2vw">Data podjęcia 
                                <?php 
                                    if($ticket['data_podjecia']==null){echo '--------';}
                                    else{echo $ticket['data_podjecia'];}
                                ?>
                            </span>
                            <span class="fs-5" style="margin-left: 2vw">Data zamknięcia 
                                <?php 
                                    if($ticket['data_zamkniecia']==null){echo '--------';}
                                    else{echo $ticket['data_zamkniecia'];}
                                ?>
                            </span>
                            <span class="fs-5" style="margin-left: 4.3vw;">Status 
                                <?php 
                                    if($ticket['status'] == 0){echo '<span class="badge rounded-pill bg-success">Nowe</span>';}
                                    else if($ticket['status'] == 1){echo '<span class="badge rounded-pill bg-warning">Podjęte</span>';}
                                    else if($date_now > $date_closed){echo '<span class="badge rounded-pill bg-danger">Zamknięte permamentnie</span>';}
                                    else{echo '<span class="badge rounded-pill bg-danger">Zamknięte</span>';}
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <label class="form-label">Nazwa</label>
                            <input type="text" class="form-control" value="<?php echo $ticket['nazwa']; ?>" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Dział</label>
                            <select id="dzialSelect" name="dzialSelect" class="form-select" onChange="changeDzialSelect()" <?php if ($ticket['status'] == '2'){echo 'disabled';} ?>>
                                <option value="IT">IT</option>
                                <option value="UR">Utrzymanie ruchu</option>
                                <option value="Magazyn">Magazyn</option>
                                <option value="Hurtownia">Hurtownia</option>
                                <option value="TM">Tempimetodi</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Problem</label>
                            <select id="problemSelect" name="problemSelect" class="form-select" <?php if ($ticket['status'] == '2'){echo 'disabled';} ?>>
                                <?php
                                    $problemList = $conn->prepare("SELECT * FROM Problems WHERE dzial = :dzial"); 
                                    $problemList->bindParam(':dzial', $ticket['dzial']);
                                    $problemList->execute();

                                    while($row = $problemList->fetch( PDO::FETCH_ASSOC )) {
                                        echo '<option value="'.$row['value'].'">'.$row['value'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <label class="form-label">Linia</label>
                            <input type="text" class="form-control" value="<?php echo $ticket['linia']; ?>" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Stanowisko</label>
                            <input type="text" class="form-control" value="<?php echo $ticket['stanowisko']; ?>" disabled/>
                        </div>
                        <div class="col">
                            <label class="form-label">Priorytet</label>
                            <select id="priorytetSelect" name="priorytetSelect" class="form-select" <?php if ($ticket['status'] == '2'){echo 'disabled';} ?>>
                                <option value="0">Powiadomienie</option>
                                <option value="2">Średni</option>
                                <option value="3">Wysoki</option>
                                <option value="4">Krytyczy</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                        <?php
                                if($ticket['status'] == 0){echo '<input name="takeTicket" class="btn btn-warning" type="Submit" value="Podejmij zgłoszenie"/>';}
                                else if($ticket['status'] == 1)
                                {
                                    echo '<input name="editTicket" class="btn btn-success" type="Submit" value="Zapisz"/>';
                                    echo '<input name="changeStatus" class="btn btn-danger" style="margin-left:1%" type="Submit" value="Zamknij zgłoszenie"/>';
                                }
                                else if ($date_now < $date_closed){
                                    echo '<input name="changeStatus" class="btn btn-primary" style="margin-left:1%" type="Submit" value="Otwórz ponownie zgłoszenie ('.$countdown->format( '%H:%I:%S' ).')"/>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                            <p class="fs-4 border-bottom">Wiadomość do zgłoszenia</p>
                            <span class="lead" style="overflow-wrap: break-word;">
                                <?php 
                                    if($ticket['wiadomosc']!=null){echo $ticket['wiadomosc'];}
                                    else{echo "Brak wiadomości";}
                                ?>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-note" role="tabpanel" aria-labelledby="nav-note-tab">
                    <div class="row" style="margin-top:1vw;">
                        <form method="post">
                            <div class="col">
                                <label class="form-label">Treść notatki (max 250 znaków)</label>
                                <textarea class="form-control" name="notatka" maxlength="250"></textarea><br/>
                                <input name="addNote" class="btn btn-primary" type="Submit" value="Dodaj notatkę"/>
                            </div>
                        </form> 
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                    <div class="row" style="margin-top:1vw;">
                        <div class="col">
                        <?php
                            if ($historyCount < 0) {
                                $history->setFetchMode(PDO::FETCH_ASSOC);
                                $iterator = new IteratorIterator($history);
                                foreach ($iterator as $edit)
                                {
                                    echo'
                                        <div class="col rounded shadow" style="background: white; margin-top:1vw; padding: 1vw 1vw 0.5vw 1vw;">
                                            <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Edytowane przez '.$edit['uzytkownik'].' data '.$edit['data_modyfikacji'].'</p>
                                            <p class="lead" style="overflow-wrap: break-word;">'.$edit['modyfikacja'].'</p>
                                        </div>';
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if ($notesCount < 0) {
                $notes->setFetchMode(PDO::FETCH_ASSOC);
                $iterator = new IteratorIterator($notes);
                foreach ($iterator as $note)
                {
                    echo'
                        <div class="col rounded shadow" style="background: white; margin-top:1vw; padding: 1vw 1vw 0.5vw 1vw;">
                            <p class="fs-5 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Notatka dodana '.$note['data_dodania'].' przez '.$note['username'].'</p>
                            <p class="lead" style="overflow-wrap: break-word;">'.$note['tresc'].'</p>
                        </div>';
                }
            }
        ?>
    </div>

    <script>
        document.getElementById('dzialSelect').value='<?php echo $ticket['dzial']; ?>';
        document.getElementById('priorytetSelect').value=<?php echo $ticket['priorytet']; ?>;
        document.getElementById('problemSelect').value='<?php echo $ticket['problem']; ?>';

        function changeDzialSelect(){
            var e = document.getElementById("dzialSelect");
            var check = e.value;

            var dzial = [
                    <?php
                        $problemList = $conn->prepare("SELECT * FROM Problems");
                        $problemList->execute();

                        while($problem = $problemList->fetch( PDO::FETCH_ASSOC )) {
                            $problem = $problem['dzial'];
                            echo "'$problem',";
                        }
                    ?>];
            var problem = [
                    <?php
                        $problemList = $conn->prepare("SELECT * FROM Problems");
                        $problemList->execute();

                        while($problem = $problemList->fetch( PDO::FETCH_ASSOC )) {
                            $problem = $problem['value'];
                            echo "'$problem',";
                        }
                    ?>];

            $('#problemSelect').children().remove();
          
            var wynik = [];
            for (var i = 0; i < dzial.length; i++) {
                for (var j = i; j < problem.length; j++){
                    if (dzial[i] == check){
                        $('#problemSelect').append($('<option>', {
                            value: problem[j],
                            text: problem[j]
                        }));
                    }
                    break;
                }
            }

        }
    </script>

    <?php include("includes/staff_footer.php"); ?>