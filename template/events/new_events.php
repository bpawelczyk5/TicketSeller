<?php
require_once '../Util/Manager.php';
use App\Util\Manager;

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type_id'] == 3)
{
  header("Location: /");
  exit;
}

if(isset($_POST["submit"]))
{
  $name = $_POST["name"];
  $description = $_POST["description"];
  $location_id = $_POST["location_id"];
  $price = $_POST["price"];
  $image = "img/" . $_FILES["image"]["name"];
  $date = $_POST["date"];

  $manager = new Manager(); 


  $currentDate = date("Y-m-d"); 
  if ($date < $currentDate) {
    echo "<script>alert('Podana data jest wcześniejsza niż dzisiejsza.');</script>";
    echo "<script> window.location.href='/dodawanie-wydarzen';</script>";
    exit;
  }


  $check_event_query = "SELECT id FROM events WHERE name = '$name'";
  $check_event_result = $manager->query($check_event_query);

  if ($check_event_result && $check_event_result->num_rows > 0) {
    echo "<script> alert('Wydarzenie o podanej nazwie już istnieje.');</script>";
    echo "<script> window.location.href='/dodawanie-wydarzen';</script>";
    exit;
  }

  // Sprawdzanie czy lokalizacja istnieje
  $check_location_query = "SELECT id FROM locations WHERE name = '$location_id'";
  $check_location_result = $manager->query($check_location_query);

  if ($check_location_result && $check_location_result->num_rows > 0) {
    // Jesli tak ->
    $location_row = $check_location_result->fetch_assoc();
    $last_location_id = $location_row['id'];
  } else {
    // Jesli nie ->
    $add_location_id = "INSERT INTO locations (id, name) VALUES (NULL, '$location_id')";
    $location_result = $manager->query($add_location_id);

    if ($location_result) {
      // Pobieranie ostatnio wprowadzanego id lokalizacji
      $last_location_id = $manager->getLastInsertId();
    } else {
      echo "<script>alert('Błąd podczas dodawania lokalizacji.');</script>";
      exit;
    }
  }

  
  $add_event = "INSERT INTO events (location_id, name, description, price, image, date) VALUES ('$last_location_id', '$name', '$description', '$price', '$image', '$date')";
  $event_result = $manager->query($add_event);

  if ($event_result) {
    $targetDirectory = "C:/xampp/htdocs/projekt/public/img/";
    $targetFilePath = $targetDirectory . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);

    echo "<script>alert('Event dodany pomyślnie!');</script>";
  } else {
    echo "<script>alert('Błąd podczas dodawania wydarzenia.');</script>";
  }
}
?>

<br><br><br><br>

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
        .container input[type="number"],
        .container input[type="date"],
        .container textarea {
            width: 300px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .container textarea {
            height: 150px;
        }

        .container input[type="file"] {
            margin-top: 10px;
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
    <form method="POST" action="/dodawanie-wydarzen" enctype="multipart/form-data">
        <label for="name">Tytuł wydarzenia:</label>
        <input type="text" name="name" required placeholder="Tytuł wydarzenia">
        <br>
        <label for="description">Opis wydarzenia:</label>
        <input type="text" name="description" required placeholder="Opis wydarzenia" rows="6">
        <br>
        <label for="location_id">Miasto:</label>
        <input type="text" name="location_id" required placeholder="Miasto">
        <br>
        <label for="price">Cena:</label>
        <input type="number" name="price" min="1" required placeholder="Cena">
        <br>
        <label for="image">Zdjęcie:</label>
        <input type="file" name="image" required>
        <br>
        <label for="date">Data:</label>
        <input type="date" name="date" required>
        <br>
        <input type="submit" name="submit" value="Dodaj wydarzenie">
    </form>
</div>
</body>
</html>
