<?php
include("includes/staff_template.php");

if ($_SESSION['admin'] != 1){echo '<script>window.location.href = "staff.php"</script>';}

if(isset($_POST['submit']))
{
    $login = $_POST['login'];
    
    $check = $conn->prepare("SELECT * FROM Staff WHERE login = :login");
    $check->execute(array(':login'=>$login));
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if(!empty($row) && $login == $row['login'])
    {
        $errors[] = "Podany użytkownik już istnieje.";
    }
    else{
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $login = strtolower($_POST['login']);
        $email = strtolower($_POST['email']);

        if (isset($_POST['isAdmin'])) {$admin = 1;}
        else{ $admin = 0; }

        $stmt = $conn->prepare("INSERT INTO Staff (login, password, username, email, dzial, admin) VALUES (:login, :password, :username, :email, :dzial, :admin)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':dzial', $_POST['dzialSelect']);
        $stmt->bindParam(':admin', $admin);
        $stmt->execute();

        $success_msg = "Pomyślnie dodano użytkownika.";
    }
}
?>
  
 <div class="container-fluid" style="background:#F2F2F2">
    <div class="row">
      <div class="col ">
        <p class="fs-2 border-bottom" style="background:white; margin: 0 -0.6vw 1vw -0.6vw; padding: 0.5vw 4vw 0.6vw 0vw; text-align: right">Użytkownicy</p>
        <div class="col rounded shadow" style="background: white; padding: 1vw 1vw 0.5vw 1vw;">
            <p class="fs-4 border-bottom" style="padding: 0vw 0vw 0.6vw 0vw;">Dodaj użytkownika</p>
            <?php 
				if(isset($errors) && count($errors) > 0)
				{
					foreach($errors as $error_msg)
					{
						echo '<div class="alert alert-danger">'.$error_msg.'</div>';
					}
				}
                if(isset($success_msg))
				{
						echo '<div class="alert alert-success">'.$success_msg.'</div>';
				}
			?>
            <form class="col-lg-8" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Nazwa użytkownika</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dział</label>
                        <select id="dzialSelect" name="dzialSelect" class="form-select" required>
                            <option value="IT">IT</option>
                            <option value="UR">Utrzymanie ruchu</option>
                            <option value="Magazyn">Magazyn</option>
                            <option value="Hurtownia">Hurtownia</option>
                            <option value="TM">Tempimetodi</option>
                            <option value="All">Wszystkie</option>
                        </select>
                    </div>
                <div class="mb-3">
                    <input class="form-check-input" type="checkbox" value="" name="isAdmin" id="isAdmin">
                    <label class="form-check-label" for="isAdmin">Konto administratora</label>
                </div>
                <div class="mb-3">
                    <input name="submit" class="btn btn-primary" type="Submit"/>
                </div>
            </form>
        </div>
    </div>
    <?php include('includes/staff_footer.php'); ?>