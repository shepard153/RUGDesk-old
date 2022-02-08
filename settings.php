<?php
include("includes/staff_template.php");

if ($_SESSION['admin'] != 1){echo '<script>window.location.href = "staff.php"</script>';}

if(isset($_POST['submit']))
{
    if(empty($_POST['passwordOld']) && empty($_POST['passwordNew']) && empty($_POST['passwordNewCheck']) && !empty($_POST['email']))
    {
        $stmt = $conn->prepare("UPDATE Staff SET email=:email WHERE login = :login");
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':login', $_SESSION['user']);
        $stmt->execute();    

        $success_msg = "Pomyślnie edytowano dane.";
    }
    else
    {
        $check = $conn->prepare("SELECT * FROM Staff WHERE login = :login");
        $check->bindParam(':login', $_SESSION['user']);
        $check->execute();

        $row = $check->fetch(PDO::FETCH_ASSOC);

        if (password_verify($_POST['passwordOld'], $row['password'])){
            if (strlen($_POST['passwordNew']) > 7 && strlen($_POST['passwordNewCheck']) > 7 ){
                if (!empty($_POST['passwordNew']) && !empty($_POST['passwordNewCheck']) && $_POST['passwordNew'] == $_POST['passwordNewCheck']){
                    $hash = password_hash($_POST['passwordNew'], PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("UPDATE Staff SET password=:password, email=:email WHERE login = :login");
                    $stmt->bindParam(':password', $hash);
                    if (!empty($_POST['email'])){$stmt->bindParam(':email', $_POST['email']);}
                    else {$stmt->bindParam(':email', $row['email']);};
                    $stmt->bindParam(':login', $_SESSION['user']);
                    $stmt->execute();

                    $success_msg = "Pomyślnie edytowano dane.";
                }
                else{
                    $error_msg = "Podane hasła nie zgadzają się.";
                }
            }
            else{
                $error_msg = "Podane nowe hasło jest za krótkie. Hasło musi zawierać minimum 8 znaków.";
            }
        }
        else {
            $error_msg = "Stare hasło jest błędne.";
        }
    }
}
?>
  
 <div class="container-fluid" style="background:#F2F2F2">
    <div class="row">
      <div class="col ">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Użytkownicy</p>
        <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
            <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Konfiguracja konta Administratora</p>
            <?php 
				if(isset($error_msg))
				{
					echo '<div class="alert alert-danger">'.$error_msg.'</div>';
				}
                if(isset($success_msg))
				{
						echo '<div class="alert alert-success">'.$success_msg.'</div>';
				}
			?>
            <form class="col-lg-8" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email (zmiana możliwa bez podawania hasła)</label>
                    <input type="text" class="form-control" id="email" name="email" value="">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Stare hasło <span style="color:red">*</span></label>
                    <input type="password" class="form-control" id="passwordOld" name="passwordOld">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nowe hasło <span style="color:red">*</span></label>
                    <input type="password" class="form-control" id="passwordNew" name="passwordNew">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Powtórz nowe hasło <span style="color:red">*</span></label>
                    <input type="password" class="form-control" id="passwordNewCheck" name="passwordNewCheck">
                </div>
                <div class="mb-3">
                    <input name="submit" class="btn btn-primary" type="Submit"/>
                </div>
            </form>
        </div>
    </div>
    <?php include('includes/staff_footer.php'); ?>