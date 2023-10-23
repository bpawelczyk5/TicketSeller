<?php

require_once '../Util/Validation.php';
require_once '../Util/Manager.php';

$validation = new \App\Util\Validation();

$rules = [
    'login' => [
        'min' => 5,
        'max' => 100
    ],
    'password' => [
        'min' => 5,
        'max' => 250
    ],
    'name' => [
        'min' => 2,
        'max' => 100
    ],
    'lastName' => [
        'min' => 2,
        'max' => 100
    ],
];

if ( isset($_POST['user']) ) {
    $user = $_POST['user'];
    $errors = $validation->validate($rules, $user);

    if ( $errors === null ) {
        $manager = new \App\Util\Manager();

        $duplicate = $manager
            ->get()
            ->query('SELECT COUNT(id) as counter FROM user WHERE login = "'.$user['login'].'"')
            ->fetch_array(MYSQLI_ASSOC);

        if ( $duplicate['counter'] == 0 ) {
            $query = 'INSERT INTO user (login, name, last_name, password, state, type_id) VALUES ("'
                .$user['login'].'", "'
                .$user['name'].'", "'
                .$user['lastName'].'", "'
                .password_hash($user['password'], PASSWORD_DEFAULT)
                .'", 200, 3)';
            $result = $manager
                ->get()
                ->query($query);

            //var_dump($query);

            if ( $result === true ) {
                header('Location: /logowanie');
            }

            $errors['main'] = 'Zapis użytkownika do bazy danych nie powiódł się.';

        } else {
            $errors['login'] = 'Podany login jest już zajęty.';
        }
    }
}
?>

<a href="/"><img id="logo2" src="/img/event_logo.png" width="250px"></a>
<hr style="height:1px;border-width:0;color:gray;background-color:gray;margin-left: 250px;margin-right: 250px;">

<div class="logbox">
      <div id="naglowek">Rejestracja</div>
    
    <form action="#" name="user" method="POST">
        <?php if ( isset($errors['main']) ): ?>
            <p> <?php echo $errors['main']; ?> </p>
        <?php endif; ?>

        <div class="field">
            <input type="text" id="login" class="inputy" name="user[login]" value="<?php echo isset($_POST['user']['login']) ? $_POST['user']['login'] : ''; ?>">
            <label for="login"> Login </label>
            <strong style="text-align: center;color: red; font-size: 0.5em; display: block;"><?php echo isset($errors['login']) ? $errors['login'] : ''; ?></strong>
        </div>
        
        <div class="field">
            <input type="password" class="inputy" id="pass" name="user[password]">
            <label for="pass"> Hasło </label>
            <strong style="text-align: center;color: red; font-size: 0.5em; display: block;"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></strong>
        </div>
        
        <div class="field"> 
            <input type="text" id="name" class="inputy" name="user[name]" value="<?php echo isset($_POST['user']['name']) ? $_POST['user']['name'] : ''; ?>">
            <label for="name"> Imię </label>
            <strong style="text-align: center;color: red; font-size: 0.5em; display: block;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></strong>
        </div>
        
        <div class="field">
            <input type="text" id="last_name" class="inputy" name="user[lastName]" value="<?php echo isset($_POST['user']['lastName']) ? $_POST['user']['lastName'] : ''; ?>">
            <label for="last_name"> Nazwisko </label>
            <strong style="text-align: center;color: red; font-size: 0.5em; display: block;"><?php echo isset($errors['lastName']) ? $errors['lastName'] : ''; ?></strong>
        </div>
        
        <br><div class="field">
            <input type="submit" class="inputy" id="button_log" name="sign" value="Zarejestruj">
        </div>

        <div id="link_reg">
                Masz konto? <br> <a style="" href="/logowanie">Zaloguj się</a>
            </div>
    </form>