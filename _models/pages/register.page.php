<?php

ob_start();
if ($functions->log_check()["status"] == "ok") {
    echo "<div align='center'>You are already logged in!</div>";
    return ob_get_clean();
}

$alerts = "";


if(isset($_POST['toss'])){
    @$cookie = $_COOKIE['request_number'];
    @$session_signup_temp = $_SESSION['_signup_temp'];
    if ($_POST['_toss'] == hash("adler32", $cookie . '_toss_signup') && $session_signup_temp == md5($cookie)) {
        $login_req = $functions->login($_POST['user'], $_POST['pass']);
        if ($login_req == false) {
            $alerts .= "<div class='alert alert-danger'><strong>Stop!</strong> Your email or password is incorrect, please type again!</div>";
        }
    } else {
        $alerts .= "<div class='alert alert-warning'><strong>Woops, Too Slow!</strong> Session expired! Please try again. This is for your own security.</div>";
    }
}




$_random_key = hash("md5", rand(99, 9999).'_signup' . $functions->secret_key);
$_SESSION['_signup_temp'] = md5($_random_key);
setcookie('request_number', $_random_key, time() + 20);
$_toss = hash("adler32", $_random_key . '_toss_signup');

?>




    <div
        style="width: 400px; position: relative; margin-left: -150px; left: 50%; margin-top: 30px; margin-bottom: 30px;">
        <?php echo $alerts; ?>
        <div class="panel panel-default">
            <div class="panel-body"> Register</div>
            <div class="panel-footer">
                <form method="post">
                    <input type="hidden" name="_toss" value="<?php echo $_toss; ?>">

                    <table class="table">
                        <tr>
                            <td>Full Name</td>
                            <td><input type="text" class="form-control" name="name"></td>
                        </tr>

                        <tr>
                            <td>Email</td>
                            <td><input type="email" class="form-control" name="email"></td>
                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="text" class="form-control" name="pass"></td>
                        </tr>

                        <tr>
                            <td>Re-type Password</td>
                            <td><input type="text" class="form-control" name="repass"></td>
                        </tr>

                    </table>


                    <button class="btn btn-primary">Signin</button>
                </form>
            </div>
        </div>
    </div>



<?php return ob_get_clean(); ?>