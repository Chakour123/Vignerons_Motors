<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/connect.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($id, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($id, $query);

    if ($result && mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['mdp'])) {

            $_SESSION['users'] = [
                'id' => $user['idu'],
                'nom' => $user['nom'],
                'role' => $user['role']
            ];

            header("Location: index.php");
            exit();

        } else {
            $error = "Mot de passe incorrect.";
        }

    } else {
        $error = "Cet utilisateur n'existe pas.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="SignUp_LogIn_Form.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
  text-decoration: none;
  list-style: none;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
}

.container {
  position: relative;
  width: 850px;
  height: 550px;
  background: #fff;
  margin: 20px;
  border-radius: 30px;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.container h1 {
  font-size: 36px;
  margin: -10px 0;
}

.container p {
  font-size: 14.5px;
  margin: 15px 0;
}

form {
  width: 100%;
}

.form-box {
  position: absolute;
  right: 0;
  width: 50%;
  height: 100%;
  background: #fff;
  display: flex;
  align-items: center;
  color: #333;
  text-align: center;
  padding: 40px;
  z-index: 1;
  transition: 0.6s ease-in-out 1.2s, visibility 0s 1s;
}

.container.active .form-box {
  right: 50%;
}

.form-box.register {
    visibility: hidden;
}
.container.active .form-box.register {
  visibility: visible;
}

.input-box {
  position: relative;
  margin: 20px 0;
}

.input-box input {
  width: 100%;
  padding: 13px 50px 13px 20px;
  background: #eee;
  border-radius: 8px;
  border: none;
  outline: none;
  font-size: 16px;
  color: #333;
  font-weight: 500;
}

.input-box input::placeholder {
  color: #888;
  font-weight: 400;
}

.input-box i {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 20px;
}

.forgot-link {
  margin: -15px 0 15px;
}
.forgot-link a {
  font-size: 14.5px;
  color: #333;
}

.btn {
  width: 100%;
  height: 48px;
  background: #E69225;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border: none;
  cursor: pointer;
  font-size: 16px;
  color: #fff;
  font-weight: 600;
}

.social-icons {
  display: flex;
  justify-content: center;
}

.social-icons a {
  display: inline-flex;
  padding: 10px;
  border: 2px solid #ccc;
  border-radius: 8px;
  font-size: 24px;
  color: #333;
  margin: 0 8px;
}

.toggle-box {
  position: absolute;
  width: 100%;
  height: 100%;
}

.toggle-box::before {
  content: "";
  position: absolute;
  left: -250%;
  width: 300%;
  height: 100%;
  background: #E69225;
  border-radius: 150px;
  z-index: 2;
  transition: 1.8s ease-in-out;
}

.container.active .toggle-box::before {
  left: 50%;
}

.toggle-panel {
  position: absolute;
  width: 50%;
  height: 100%;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 2;
  transition: 0.6s ease-in-out;
}

.toggle-panel.toggle-left {
  left: 0;
  transition-delay: 1.2s;
}
.container.active .toggle-panel.toggle-left {
  left: -50%;
  transition-delay: 0.6s;
}

.toggle-panel.toggle-right {
  right: -50%;
  transition-delay: 0.6s;
}
.container.active .toggle-panel.toggle-right {
  right: 0;
  transition-delay: 1.2s;
}

.toggle-panel p {
  margin-bottom: 20px;
}

.toggle-panel .btn {
  width: 160px;
  height: 46px;
  background: transparent;
  border: 2px solid #fff;
  box-shadow: none;
}

@media screen and (max-width: 650px) {
  .container {
    height: calc(100vh - 40px);
  }

  .form-box {
    bottom: 0;
    width: 100%;
    height: 70%;
  }

  .container.active .form-box {
    right: 0;
    bottom: 30%;
  }

  .toggle-box::before {
    left: 0;
    top: -270%;
    width: 100%;
    height: 300%;
    border-radius: 20vw;
  }

  .container.active .toggle-box::before {
    left: 0;
    top: 70%;
  }

  .container.active .toggle-panel.toggle-left {
    left: 0;
    top: -30%;
  }

  .toggle-panel {
    width: 100%;
    height: 30%;
  }
  .toggle-panel.toggle-left {
    top: 0;
  }
  .toggle-panel.toggle-right {
    right: 0;
    bottom: -30%;
  }

  .container.active .toggle-panel.toggle-right {
    bottom: 0;
  }
}

@media screen and (max-width: 400px) {
  .form-box {
    padding: 20px;
  }

  .toggle-panel h1 {
    font-size: 30px;
  }
}

.alert {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
}
.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
  <body>

      <div class="container">
          <div class="form-box login">
              <form action="connexion.php" method="POST">
                  <h1>Bon retour</h1>
                  <?php if ($error): ?>
                      <div class="alert alert-error"><?php echo $error; ?></div>
                  <?php endif; ?>
                  <div class="input-box">
                      <input type="email" name="email" placeholder="Votre mail" required>
                      <i class='bx bxs-user'></i>
                  </div>
                  <div class="input-box">
                      <input type="password" name="password" placeholder="Votre mot de passe" required>
                      <i class='bx bxs-lock-alt' ></i>
                  </div>
                  <button type="submit" class="btn">Se connecter</button>
              </form>
          </div>
          <div class="toggle-box">
              <div class="toggle-panel toggle-left">
                <img src="img/mopao.png" width="100px" height="100px" alt="">
                  <h1>Hey, Salut!</h1>
                  <p>Pas encore de compte?</p>
                 <a href="inscription.php"><button class="btn register-btn">S'inscrire</button></a> 
              </div>
          </div>
      </div>

      <script src="SignUp_LogIn_Form.js"></script>
  </body>
</html>