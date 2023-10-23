<center>
  <div style="margin-top: 220px" class="logbox">
    <div id="naglowek">Kontakt</div>
 
    <form action="" method="POST">
      <?php
        if(isset($_POST['wyslij'])) {
          $imie = $_POST['imie'];
          $nazwisko = $_POST['nazwisko'];
          $email = $_POST['mail'];
          $wiadomosc = $_POST['wiadomosc'];
          $temat = $_POST['temat'];

          
          if(empty($imie) || empty($nazwisko) || empty($email) || empty($wiadomosc) || empty($temat)) {
            echo '<p><strong style="text-align: center; color: red; font-size: 0.5em; display: block;">Wypełnij wszystkie pola formularza.</strong></p>';
          } else {
            $to = "vigo@hondaprelude.pl";
            $subject = $temat;
            $message = "Imię: " . $imie . "\n" .
                       "Nazwisko: " . $nazwisko . "\n" .
                       "E-mail: " . $email . "\n" .
                       "Wiadomość: " . $wiadomosc;
           
            $headers = "From: kontakt@vigo.hhd.pl" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();
           
            $a = mail($to, $subject, $message, $headers);
           
            if($a) {
              echo '<p><strong style="text-align: center; color: green; font-size: 0.5em; display: block;">Formularz został wysłany.</strong></p>';
            } else {
              echo '<p><strong style="text-align: center; color: red; font-size: 0.5em; display: block;">Wystąpił błąd podczas wysyłania formularza.</strong></p>';
            }
          }
        }
      ?>
      <div class="field">
        <input class="inputy" type="text" name="imie">
        <label>Imię</label>
      </div>

      <div class="field">
        <input class="inputy" type="text" name="nazwisko">
        <label>Nazwisko</label>
      </div>

      <div class="field">
        <input class="inputy" type="email" name="mail">
        <label>E-mail</label>
      </div>
      
      <div class="field">
        <input class="inputy" type="text" name="temat">
        <label>Temat</label>
      </div>

      <div class="field">
        <textarea class="inputy" name="wiadomosc" style="height:200px;padding-top: 20px;"></textarea>
        <label>Wiadomość</label>
      </div>

      <br><br><br><br>
      <div class="field">
        <input class="inputy" id="button_log" name='wyslij' type="submit" value="Wyślij">
      </div>
    </form>
  </div>
</center>
