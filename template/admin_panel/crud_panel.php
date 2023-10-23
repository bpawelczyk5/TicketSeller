<?php
require_once '../Util/Manager.php';
use App\Util\Manager;

$manager = new Manager();

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type_id'] == 2 || $_SESSION['user']['type_id'] == 3 )
{
  header("Location: /");
  exit;
}

function hashPassword($password) {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  return $hash;
}

if (isset($_POST["add_user"])) {
  $login = $_POST["login"];
  $name = $_POST["name"];
  $last_name = $_POST["last_name"];
  $password = hashPassword($_POST["password"]); 
  $type_id = $_POST["type_id"];

  
  $check_user_query = "SELECT * FROM user WHERE login = '$login'";
  $existing_user = $manager->query($check_user_query);

  if ($existing_user->num_rows > 0) {
    echo "<script>alert('Użytkownik o podanym loginie już istnieje.');</script>";
    echo "<script> window.location.href='/crud-panel';</script>";
  } else {
    $add_user_query = "INSERT INTO user (login, name, last_name, password, state, type_id) VALUES ('$login', '$name', '$last_name', '$password', '200', '$type_id')";
    if ($manager->query($add_user_query) === TRUE) {
      echo "<script>alert('Użytkownik dodany pomyślnie!');</script>";
      echo "<script> window.location.href='/crud-panel';</script>";
    } else {
      echo "<script>alert('Błąd podczas dodawania użytkownika.');</script>";
      echo "<script> window.location.href='/crud-panel';</script>";
    }
  }
}


if (isset($_GET["delete_user"])) {
  $user_id = $_GET["delete_user"];

  $delete_cart_event_query = "DELETE FROM cart_event WHERE cart_id IN (SELECT id FROM cart WHERE user_id = '$user_id')";
  $manager->query($delete_cart_event_query);

  $delete_user_query = "DELETE FROM user WHERE id = '$user_id'";
  if ($manager->query($delete_user_query) === TRUE) {
    echo "<script>alert('Użytkownik usunięty pomyślnie!');</script>";
    echo "<script> window.location.href='/crud-panel';</script>";
  } else {
    echo "<script>alert('Błąd podczas usuwania użytkownika.');</script>";
    echo "<script> window.location.href='/crud-panel';</script>";
  }
}

$get_users_query = "SELECT * FROM user";
$users_result = $manager->query($get_users_query);
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

        .user-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .user-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Dodaj użytkownika</h2>
    <form method="POST" action="">
        <label for="login">Login:</label>
        <input type="text" name="login" required placeholder="Login">
        <br>
        <label for="name">Imię:</label>
        <input type="text" name="name" required placeholder="Imię">
        <br>
        <label for="last_name">Nazwisko:</label>
        <input type="text" name="last_name" required placeholder="Nazwisko">
        <br>
        <label for="password">Hasło:</label>
        <input type="password" name="password" required placeholder="Hasło">
        <br>
        <label for="type_id">Typ użytkownika:</label>
        <select name="type_id">
            <option value="1">Administrator</option>
            <option value="2">Moderator</option>
            <option value="3">Użytkownik</option>
        </select>
        <br>
        <input type="submit" name="add_user" value="Dodaj użytkownika">
    </form>

    <h2>Lista użytkowników</h2>
    <table class="user-table">
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Typ użytkownika</th>
            <th>Akcje</th>
        </tr>
      <?php
      if ($users_result->num_rows > 0) {
        while ($row = $users_result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>".$row['id']."</td>";
          echo "<td>".$row['login']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['last_name']."</td>";
          echo "<td>".$row['type_id']."</td>";
          echo "<td><a href='/crud-panel-edycja?id=".$row['id']."'>Edytuj</a> | <a href='?delete_user=".$row['id']."'>Usuń</a></td>";
          echo "</tr>";
        }
      }
      ?>
    </table>
</div>
</body>
</html>
