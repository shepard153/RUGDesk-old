<?php
include("includes/staff_template.php");

$dzial = $_SESSION['dzial'];

if ($dzial != 'All'){
  #Najnowsze zgłoszenia
  $newest = $conn->query("SELECT TOP 5 * FROM TicketList WHERE dzial = '$dzial' AND status = 0 ORDER BY data_zgloszenia DESC");
  $count = $newest->rowCount();
  
  #Najczęstsze problemy
  $topProblems = $conn->query("SELECT problem, COUNT(*) AS occurrence FROM TicketList WHERE dzial = '$dzial' GROUP BY problem ORDER BY occurrence DESC");

  #Najwięcej zgłoszeń
  $sql = $conn->query("SELECT linia, COUNT(*) AS obszar FROM TicketList WHERE dzial = '$dzial' GROUP BY linia ORDER BY obszar DESC");
  $mostProblematic = $sql->fetch( PDO::FETCH_ASSOC );

  #Wszystkie zgłoszenia
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial'");
  $total = $sql->fetchColumn();

  #Nowe zgłoszenia
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial' AND status = '0'");
  $total_new = $sql->fetchColumn();

  #Zgłoszenia aktywne
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial' AND status = '1'");
  $total_open = $sql->fetchColumn();

  #Zgłoszenia zamknięte
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE dzial = '$dzial' AND status = '2'");
  $total_closed = $sql->fetchColumn();

  #Średni czas obsługi zgłoszenia
  $sql = $conn->query("SELECT DATEDIFF(minute, data_podjecia, data_zamkniecia) [diff] FROM TicketList WHERE dzial = '$dzial'");
  $closeTime = 0;
  while($row = $sql->fetch( PDO::FETCH_ASSOC )){
    if ($row['diff'] != NULL){
      $closeTime += $row['diff'];
    }
  }
}
else{
  #Najnowsze zgłoszenia spośród wszystkich działów
  $newest = $conn->query("SELECT TOP 5 * FROM TicketList WHERE status = 0 ORDER BY data_zgloszenia DESC");
  $count = $newest->rowCount();

  #Najczęstsze problemy spośród wszystkich działów
  $topProblems = $conn->query("SELECT problem, COUNT (*) AS occurrence FROM TicketList GROUP BY problem ORDER BY occurrence DESC");

  #Najwięcej zgłoszeń spośród wszystkich działów
  $sql = $conn->query("SELECT linia, COUNT(*) AS obszar FROM TicketList GROUP BY linia ORDER BY obszar DESC");
  $mostProblematic = $sql->fetch( PDO::FETCH_ASSOC );

  #Wszystkie zgłoszenia spośród wszystkich działów
  $sql = $conn->query("SELECT count(*) from TicketList");
  $total = $sql->fetchColumn();

  #Nowe zgłoszenia spośród wszystkich działów
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE status = '0'");
  $total_new = $sql->fetchColumn();

  #Zgłoszenia aktywne spośród wszystkich działów
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE status = '1'");
  $total_open = $sql->fetchColumn();

  #Zgłoszenia zamknięte spośród wszystkich działów
  $sql = $conn->query("SELECT COUNT(*) FROM TicketList WHERE status = '2'");
  $total_closed = $sql->fetchColumn();

  #Średni czas obsługi zgłoszenia dla wszystkich działów
  $sql = $conn->query("SELECT DATEDIFF(minute, data_podjecia, data_zamkniecia) [diff] FROM TicketList");
  $closeTime = 0;
  while($row = $sql->fetch( PDO::FETCH_ASSOC )){
    if ($row['diff'] != NULL){
      $closeTime += $row['diff'];
    }
  }
}

?>
  <meta http-equiv="refresh" content="30">
  <div class="container-fluid" style="background:#F2F2F2">
    <div class="row">
      <div class="col">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Dashboard</p>
        <div class="row justify-content-center">
          <div class="col rounded shadow" style="background: white; margin: 0vw 1vw 0vw 1vw;">
            <p class="fs-3 border-bottom" style="text-align: center;">Najnowsze zgłoszenia</p>
            <?php
              if ($count < 0) {
            ?>
            <table class="table table-hover">
              <thead>               
                <tr>
                  <td><b>Problem</b></td>
                  <td><b>Linia</b></td>
                  <td><b>Stanowisko</b></td>
                  <td><b>Priorytet</b></td>
                  <td><b>Data zgłoszenia</b></td>
                  <td><b>Status</b></td>
                </tr>
              </thead>
              <?php
                while($row = $newest->fetch( PDO::FETCH_ASSOC )) {
              ?>
              <tr class='clickable-row' data-href='ticket_details.php?ticketID=<?php echo $row['ticketID']; ?>' <?php if($row['priorytet'] == "4"){echo 'style="background-color: #ff7f7f"';} ?>>
                <td><?php echo $row['problem']; ?></td>
                <td><?php echo $row['linia']; ?></td>
                <td><?php echo $row['stanowisko']; ?></td>
                <td>
                  <?php
                    switch ($row["priorytet"]){
                      case "0":
                        echo "Powiadomienie";
                        break;
                      case "1":
                        echo "Niski";
                        break;
                      case "2":
                        echo "Średni";
                        break;
                      case "3":
                        echo "Wysoki";
                        break;
                      case "4":
                        echo "Krytyczny";
                        break;
                      default: echo "----------";
                    }
                  ?>
                </td>
                <td><?php echo $row['data_zgloszenia']; ?></td>
                <td>
                  <?php
                    if ($row["status"] == '0') {echo "<span class='badge rounded-pill bg-success'>Nowe</span>";}
                    elseif ($row["status"] == '1') {echo "<span class='badge rounded-pill bg-warning'>Aktywne</span>";}
                    elseif ($row["status"] == '2') {echo "<span class='badge rounded-pill bg-danger'>Zamknięte</span>";}
                  ?>
                </td>
              </tr>
              <?php
                  }
                }
                else{
                  echo '<p class="fs-2 text-center" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>';
                }
              ?>
            </table>
          </div>
        </div>
        <div class="row justify-content-center" style="margin-left: 2vw; margin-top: 1vw;">
          <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px; margin-right: 3vw">
            <?php
              switch ($dzial){
                case 'IT' : echo '<img src="assets/img/dashboard-it.png" class="rounded" >'; break;
                case 'UR' : echo '<img src="assets/img/dashboard-ur.png" class="rounded" >'; break;
                case 'TM' : echo '<img src="assets/img/dashboard-tm.png" class="rounded" >'; break;
                case 'Magazyn' : echo '<img src="assets/img/dashboard-magazyn.png" class="rounded" >'; break;
                case 'Hurtownia' : echo '<img src="assets/img/dashboard-hurtownia.png" class="rounded" >'; break;
                default : echo '<img src="assets/img/dashboard-all.png" class="rounded" >'; break;
              }
            ?>
            <span class="dane">
                <h4>Wszystkie zgłoszenia</h4>
                <h2><?php echo $total ?></h2>
            </span>
              <hr>
              <h5>Nowe</h5><br/>
              <h2 style="margin-top: -3.5vw;"><?php echo $total_new ?></h2>
              <h5>Aktywne</h5><br/>
              <h2 style="margin-top: -3.5vw;"><?php echo $total_open ?></h2>
              <h5>Zamknięte</h5>
              <h2 style="margin-top: -2.1vw;"><?php echo $total_closed ?></h2>
          </div>
          <div class="col-xl-3 col-lg-6 ">
            <div class="card card-stats mb-4 mb-xl-0 shadow">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Średni czas obsługi zgoszenia</h5>
                    <span class="h2 font-weight-bold mb-0"><?php echo floor($closeTime/60)." h ".($closeTime%60)." min"; ?></span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                      <i class="fas fa-chart-bar"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card card-stats mb-4 mb-xl-0 shadow" style="margin-top: 4vw">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Najwięcej zgłoszeń z:</h5>
                    <span class="h2 font-weight-bold mb-0"><?php echo $mostProblematic['linia']; ?></span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                      <i class="fas fa-chart-bar"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3 border border-success rounded shadow" style="background: white; max-width: 340px; margin-left: 3vw">
            <p class="fs-2 border-bottom">Najczęstsze problemy</p>
            <?php
              $i = 1;
              while($row = $topProblems->fetch( PDO::FETCH_ASSOC )) {
                if ($i > 5){break;}
                $i++;
            ?>
            <table class="table table-sm table-borderless">
              <tr>
                <td style="text-align: left"><?php echo $row['problem']; ?></td>
                <td class="text-end"><?php echo $row['occurrence']; ?></td>
              </tr>
            </table>
            <?php
              }
            ?>
          </div>
        </div>
      </div>     

      <div class="row justify-content-center" style="margin-top: 1vw">


      </div>
    </div>
<script>
  jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>
<?php include('includes/staff_footer.php'); ?>