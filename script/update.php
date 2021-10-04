<?php

try {
    $conn = new PDO("pgsql:host=localhost;port=5432;dbname=emporiodb_centrale", 'emporiocentrale', 'emporiocentrale');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_addcolumns = "ALTER TABLE tipologie ADD COLUMN eta_min INT, ADD COLUMN eta_max INT;
                       ALTER TABLE centralized_users ADD COLUMN logged BOOL DEFAULT 'f', ADD COLUMN last_login TIMESTAMP";
    $count = $conn->exec($sql_addcolumns);

    $sql_listaempori = "SELECT * FROM lista_empori";
    $ris = $conn->query($sql_listaempori);

    foreach($ris as $key=>$val) {
        try {
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_addcolumns = "ALTER TABLE tipologie ADD COLUMN eta_min INT, ADD COLUMN eta_max INT;
                               ALTER TABLE utenti ADD COLUMN logged BOOL DEFAULT 'f', ADD COLUMN last_login TIMESTAMP;
                               ALTER TABLE centralized_users ADD COLUMN logged BOOL DEFAULT 'f', ADD COLUMN last_login TIMESTAMP;
                               ALTER TABLE users ALTER COLUM login SET NOT NULL;
                               ALTER TABLE users ALTER COLUM password SET NOT NULL;
                               ALTER TABLE users ALTER COLUM ruolo SET NOT NULL;
                                ";
            $count = $conn_loc->exec($sql_addcolumns);

            $sql_addtables = "CREATE TABLE tipologie_local (
                                tipologia VARCHAR(50),
                                warning_qta_minima INT,
                                danger_qta_minima INT,
                                eta_min INT,
                                eta_max INT
                              );

                            CREATE TABLE messaggi (
                                id_conversazione VARCHAR(100),
                                peer VARCHAR(50),
                                id_messaggio SERIAL, 
                                tipo VARCHAR(100), 
                                sender VARCHAR(50),
                                receiver VARCHAR(50),
                                msg TEXT, 
                                data TIMESTAMP, 
                                letto BOOLEAN DEFAULT false,

                                PRIMARY KEY(id_conversazione, id_messaggio)
                            );
                            ";
            $count = $conn_loc->exec($sql_addtables);
        }

        catch(PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }
    }
}

catch(PDOException $e) {
    echo "Error : " . $e->getMessage() . "<br/>";
    die();
}

?>
