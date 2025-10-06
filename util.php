<?php
    function conecta($params = "")
    {
        $pdo = new PDO("pgsql: host=projetoscti.com.br; port=54432; dbname=eq2.ini2b;user=eq2.ini2b;password=eq22b594");

        return $pdo;
    }

    function valorsql ($paramConn, $paramSQL,$paramParams)
    {

        $select = $paramConn -> prepare($paramSQL);
        foreach($paramParams as $param){
            $select -> bindParam(":".$param[0],$param[1]);
        }
        $select -> execute();
        $linha = $select -> fetch();
        return $linha[0];

    }
    function ExecutaSQL($conn, $sql) {
    return $conn->query($sql);
    }
    
?>