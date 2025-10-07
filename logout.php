<?php
    session_destroy();  //destroi a sessao
    setcookie("PHPSESSID", "", -1);

    // voce saiu da sessao ! 

    session_start();   // reinicia sessao
    session_regenerate_id();  // gera outro id para uma nova compra

    header('location: index.php');

    unset($_SESSION['statusConectado']);
    unset($_SESSION['login']);
    unset($_SESSION['admin']);


    ?>