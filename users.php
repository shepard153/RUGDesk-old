<?php
include("includes/staff_template.php");

if ($_SESSION['admin'] != 1){echo '<script>window.location.href = "staff.php"</script>';}

$result = $conn->query("SELECT * FROM Staff");
?>

 <div class="container-fluid" style="background:#F2F2F2; overflow: auto;">
    <div class="row">
      <div class="col">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Użytkownicy</p>
        <div class="col rounded shadow" style="background: white; margin-top: 1vw; padding: 1vw 1vw 0.5vw 1vw;">
            <a href="create_user.php" class="btn btn-success btn-sm" style="float:right; margin: 0.7vw 1vw 0vw 0vw;">Dodaj użytkownika</a>
            <p class="fs-4 border-bottom" style="padding: 0.5vw 0vw 0.6vw 1vw;">Zarządzaj użytkownikami</p>
            <?php 
				if(isset($_GET['destroyed']))
				{
					echo '<div class="alert alert-danger">Konto '.$_GET['login'].' zostało skasowane.</div>';
				}
			?>
            <table class="table table-striped table-hover responsive">
                <?php
                    if ($result->fetchColumn() > 0) {
                ?>
                <thead>               
                    <tr>
                        <td><b>Login</b></td>
                        <td><b>Nazwa użytkownika</b></td>
                        <td><b>Adres e-mail</b></td>
                        <td><b>Dział</b></td>
                        <td><b>Administrator</b</td>
                        <td><b>Operacje</b></td>
                    </tr>
                </thead>
                <?php
                    $result = $conn->query("SELECT * FROM Staff");
                    while($row = $result->fetch( PDO::FETCH_ASSOC )) {
                        if ($row['login']!="root") {
                ?>
                <tr>
                    <td><?php echo $row["login"]; ?></td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["dzial"]; ?></td>
                    <td><?php if ($row["admin"]==1){echo 'Tak';} else {echo 'Nie';} ?></a></td>
                    <td>
                        <a href="edit_user.php?staffID=<?php echo $row['staffID']; ?>" class="btn btn-success btn-sm">Edytuj</a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal" value="<?php echo $row['login']; ?>" data-id="<?php echo $row['staffID']; ?>">Usuń</button>

                        <!-- Okienko z potwierdzeniem -->
                        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Usuń użytkownika</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="text"> <!-- Tekst ze skryptu JS --></p>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                <a href="" type="button" id="delete" class="btn btn-danger">Potwierdź</a>
                                </div>
                            </div>
                            </div>
                        </div>

                    </td>
                </tr>
                <?php
                        }
                    }
                }
                else
                {
                    echo '<p class="fs-2 border-bottom" style="padding: 0.2vw 0px 0px 1vw;">Nie znaleziono wyników.</p>';
                }
                ?>
            </table>
        </div>
    </div>
    <script>
        $("button").click(function() {
            var buttonDelete = $(this).val();
            var staffID = $(this).attr('data-id');
            $('#text').text("Czy na pewno chcesz skasować konto " + buttonDelete + " o numerze ID " + staffID + "?");
            document.getElementById("delete").setAttribute("href", "includes/delete_user.php?login=" + buttonDelete + "&staffID=" + staffID);
        });
    </script>
    <?php include('includes/staff_footer.php'); ?>