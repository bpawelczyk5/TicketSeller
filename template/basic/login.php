<?php

if ( isset($_POST['login']) && isset($_POST['password']) ) {

    require_once '../Util/Manager.php';
    $manager = new \App\Util\Manager();

    $query = 'SELECT * FROM user WHERE state = 200 AND login = "'.$_POST['login'].'"';
    $result = $manager
        ->get()
        ->query($query)
        ->fetch_array(MYSQLI_ASSOC);

    if ( isset($result['password']) && password_verify($_POST['password'], $result['password']) ) {
        $_SESSION['user'] = [
            'id' => $result['id'],
            'login' => $result['login'],
            'type_id' => $result['type_id']
        ];
        
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['type_id'] == 1)
        {
            header("Location: /crud-panel");
        }
        else if (isset($_SESSION['user']['id']) && $_SESSION['user']['type_id'] == 2)
        {
            header("Location: /dodawanie-wydarzen");
        }
        else
        {
            header("Location: /");
        }

    } else {
        $errors['main'] = '<center> <p> <strong style="color: red; font-size: 0.8em; display: block;"> Błędne dane logowania </strong> </p></center>';
    }
}

?>
<a href="/"><img id="logo2" src="/img/event_logo.png" width="250px"></a>
<hr style="height:1px;border-width:0;color:gray;background-color:gray;margin-left: 250px;margin-right: 250px;">

<div class="logbox">
      <div id="naglowek">Logowanie</div>
         
        <?php if (isset($errors['main']) ): ?>
            <p> <strong style="color: red; font-size: 0.8em; display: block;"> <?php echo $errors['main']; ?> </strong> </p>
        <?php endif; ?>

        <form action="#" method="POST">          
            <div class="field">
                <input type="text" class="inputy" name="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : ''; ?>">
                <label>Login</label>
                 <strong style="color: red; font-size: 0.8em; display: block;"><?php echo isset($errors['login']) ? $errors['login'] : ''; ?></strong>
            </div>
                
            <div class="field">
                <input type="password" class="inputy" name="password">
                <label>Hasło</label>
                <strong style="color: red; font-size: 0.8em; display: block;"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></strong>
            </div>

            <div class="field">
                <input type="submit" id="button_log" class="inputy" name="log" value="Login">
            </div>

            <div class="signup-link">
                Nie masz konta? <a href="/rejestracja">Zarejestruj się</a>
            </div>
        
        </form>
</div>