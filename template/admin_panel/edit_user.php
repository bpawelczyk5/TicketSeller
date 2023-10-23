<?php
require_once '../Util/Manager.php';
use App\Util\Manager;

$manager = new Manager();

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type_id'] == 2 || $_SESSION['user']['type_id'] == 3) {
  header("Location: /");
  exit;
}


if (!isset($_GET['id'])) {
  header("Location: /");
  exit;
}

$user_id = $_GET['id'];


$get_user_query = "SELECT * FROM user WHERE id = '$user_id'";
$user_result = $manager->query($get_user_query);

if ($user_result->num_rows == 0) {
  echo "<script>alert('Użytkownik nie istnieje.');</script>";
  header("Location: /");
  exit;
}

$user_data = $user_result->fetch_assoc();

function hashPassword($password) {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  return $hash;
}


if (isset($_POST["update_user"])) {
  $login = $_POST["login"];
  $name = $_POST["name"];
  $last_name = $_POST["last_name"];
  $password = hashPassword($_POST["password"]);
  $type_id = $_POST["type_id"];

  $update_user_query = "UPDATE user SET login = '$login', name = '$name', last_name = '$last_name', password = '$password', type_id = '$type_id' WHERE id = '$user_id'";

  if ($manager->query($update_user_query) === TRUE) {
    echo "<script>alert('Dane użytkownika zaktualizowane pomyślnie!');</script>";
    echo "<script> window.location.href='/crud-panel';</script>";
    exit;
  } else {
    echo "<script>alert('Błąd podczas aktualizacji danych użytkownika.');</script>";
    echo "<script> window.location.href='/crud-panel';</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <style>
      body {
          background-color: #f2f2f2;
          font-family: Poppins;
      }

      .container {
          display: flex;
          flex-direction: column;
          align-items: center;
          margin: 10% auto;
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          background-color: #ffffff;
          padding: 20px;
      }

      .container label {
          font-weight: bold;
          margin-bottom: 10px;
      }

      .container input[type="text"],
      .container input[type="email"],
      .container input[type="password"],
      .container select {
          width: 300px;
          padding: 10px;
          margin-bottom: 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }

      .container input[type="submit"] {
          background-color: black;
          color: white;
          padding: 10px 20px;
          border: none;
          cursor: pointer;
      }

      .container input[type="submit"]:hover {
          background-color: mediumpurple;
      }
  </style>
</head>
<body>
<div class="container">
  <h2>Edytuj użytkownika</h2>
  <form method="POST" action="">
    <label for="login">Login:</label>
    <input type="text" name="login" required placeholder="Login" value="<?php echo $user_data['login']; ?>">
    <br>
    <label for="name">Imię:</label>
    <input type="text" name="name" required placeholder="Imię" value="<?php echo $user_data['name']; ?>">
    <br>
    <label for="last_name">Nazwisko:</label>
    <input type="text" name="last_name" required placeholder="Nazwisko" value="<?php echo $user_data['last_name']; ?>">
    <br>
    <label for="password">Hasło:</label>
    <input type="password" name="password" required placeholder="Hasło" value="<?php echo $user_data['password']; ?>">
    <br>
    <label for="type_id">Typ użytkownika:</label>
    <select name="type_id">
      <option value="1" <?php if ($user_data['type_id'] == 1) echo 'selected'; ?>>Administrator</option>
      <option value="2" <?php if ($user_data['type_id'] == 2) echo 'selected'; ?>>Moderator</option>
      <option value="3" <?php if ($user_data['type_id'] == 3) echo 'selected'; ?>>Użytkownik</option>
    </select>
    <br>
    <input type="submit" name="update_user" value="Zaktualizuj użytkownika">
  </form>
</div>
</body>
</html>
