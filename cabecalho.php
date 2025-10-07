<?php 
        ///////////////////////////////////////////////////////////////
        session_start();
        include "util.php";
        
        $conn = conecta();

        if ( isset($_SESSION['statusConectado'] ) and 
           ( $_SESSION['statusConectado'] == true )) {
            
            echo "<br>Ola, ".$_SESSION['login']."<br>";
        
            $params = [ [ 'campo' => ':nome',
                          'valor' => $_SESSION['login'] ],
                        [ 'campo' => ':nome',
                          'valor' => $_SESSION['login'] ] ];

            $telefone = valorsql($conn, 
                "select telefone from usuario where nome = :nome", 
                 $params );

            echo $telefone;

            if ( $_SESSION['admin'] == true) {
                echo "<a href='produtos.php'>Produtos</a>
                <a href='usuarios.php'>Usuarios</a>";
            }
            echo "<a href='atend.php'>Atendimento</a>
                  <a href='missao.php'>Missao</a>
                  <a href='logout.php'>Sair</a>";

        } else {
            echo "<a href='login.php'>Login</a>";
        }
        echo "<hr>";
        ///////////////////////////////////////////////////////////
?> 
