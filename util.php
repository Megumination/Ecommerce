<?php
    function conecta($params = "")
    {
        $pdo = new PDO("pgsql: host=localhost; port=5432; dbname=eq2.ini2b;user=postgres;password=postgres");

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

    
?>