<?php

require_once '../Util/Manager.php';

use App\Util\Manager;

$query = 'SELECT l.name AS location_name, e.* FROM events e INNER JOIN locations l ON l.id = e.location_id';


if (isset($_GET['search']) && $_GET['search'] !== ''){
    $query .= ' WHERE l.name LIKE \'%'.$_GET['search'].'%\'
                OR e.name LIKE \'%'.$_GET['search'].'%\'
                OR e.description LIKE \'%'.$_GET['search'].'%\'';
}


$manager = new Manager();
$events = $manager
    ->get()
    ->query($query)
    ->fetch_all(MYSQLI_ASSOC);
?>

<div class="image-banner">
    <img src="/img/prices_banner.jpg" id="banner_search">
</div>

<br><center>
    <form id="wyszukiwarka">
        <input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>"/><button id="szukaj_button" type="submit"><i class="fas fa-search"></i></button>
    </form>
</center>

<?php
    function compareDates($a, $b) {
        $dateA = strtotime($a['date']);
        $dateB = strtotime($b['date']);
        return $dateA - $dateB;
    }
    
    usort($events, 'compareDates');

?>
<div class="box-wrapper">
  <?php foreach ($events as $event): ?>
    <form name="event_order" method="POST">
      <div class="box" onmouseover="showFullDescription(this)" onmouseout="hideFullDescription(this)">
        <img width="300px" height="300px" src="<?php echo $event['image'];?>">
        <h1 id="tytul" class="short-description"><?php echo $event['name'];?></h1>
        <p id="tytul"><?php echo ' ('.$event['date'].')';?></p>
        <p id="lokalizacja" class="short-description"><?php echo $event['location_name']; ?></p>
        <p class="cena"><?php echo $event['price'].' zł';?></p>
        <p id="opis" class="short-description" ><?php echo $event['description'];?></p>
        <p>
          <button type="submit" name="event_order[id]" value="<?php echo $event['id']; ?>"> Dodaj do koszyka </button>
        </p>
        <p>
          <input id="ilosc" type="number" max="10" min="1" name="event_order[amount]" value="1" />
        </p>
      </div>
    </form>
  <?php endforeach; ?>
</div>

<script>
  function showFullDescription(element) {
    const descriptions = element.querySelectorAll('.short-description');
    descriptions.forEach(description => {
      description.classList.add('full-description');
    });
  }

  function hideFullDescription(element) {
    const descriptions = element.querySelectorAll('.short-description');
    descriptions.forEach(description => {
      description.classList.remove('full-description');
    });
  }
</script>