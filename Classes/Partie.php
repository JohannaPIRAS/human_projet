<?php

namespace Classes;

class Partie
{
    /**
     * Date de la partie réalisé
     *
     * @var timestamp without time zone
     */
    protected $_idPartie;

    public function __construct()
    {
        $pdo = new Database();

        $connect = $pdo->getPdo();

        $req = $connect->prepare('INSERT INTO partie (date_partie) VALUES (CURRENT_TIMESTAMP)');
        $req->execute();

        $req_recup_id = $connect->prepare('SELECT MAX (id_partie) AS "id_partie" FROM partie');
        $req_recup_id->execute();
        $result = $req_recup_id->FETCH();

        $result['id_partie'];
        $this->_idPartie = $result['id_partie'];
    }

    public function getIdPartie()
    {
        return $this->_idPartie;
    }

    public function statisticAll()
    {
        $pdo = new Database();

        $connect = $pdo->getPdo();

        $req_stat_partie = $connect->prepare('SELECT AVG(lifespan) AS lifespan, AVG(growth) as growth, AVG(birthsize) as birthsize
        FROM personnage');

        $req_stat_partie->execute();
        $result_stat = $req_stat_partie->FETCH();
        $tableau = [];
        $tableau['lifespan'] = round ($result_stat['lifespan'], 2);
        $tableau['growth'] = round ($result_stat['growth'],2);
        $tableau['birthsize'] = round ($result_stat['birthsize'],2);
      

        return $tableau;

    }

    public function statisticPartie()
    {
        $pdo = new Database();

        $connect = $pdo->getPdo();

        $req_stat_partie = $connect->prepare('SELECT AVG(lifespan) AS lifespan, AVG(growth) as growth, AVG(birthsize) as birthsize
        FROM personnage
        INNER JOIN partie_perso ON personnage.id_perso = partie_perso.id_perso
        WHERE id_partie = ?');

        $req_stat_partie->bindParam(1, $this->_idPartie);

        $req_stat_partie->execute();
        $result_stat = $req_stat_partie->FETCH();
        

        return $result_stat;

    }

    public function getSexRatioAll(){

        $pdo = new Database();

        $connect = $pdo->getPdo();

        $reqSexRatio = $connect->prepare('WITH
        Request1 AS (select count(*) as result1 from personnage where men =           FALSE),
        Request2 AS (select count(*) as result2 from personnage)
        SELECT CAST(Request1.result1 AS FLOAT) / CAST(Request2.result2 AS             FLOAT) * 100 as SexRatio
        FROM Request1, Request2');


        $reqSexRatio -> execute();
        $row = $reqSexRatio -> fetch();
        $ratio = $row[0];
        $roundedResult = round($ratio,2);
        return $roundedResult;
    }


    public function getSexRatio(){

        $pdo = new Database();

        $connect = $pdo->getPdo();

        $reqSexRatio = $connect->prepare('WITH
        Request1 AS (select count(*) as result1 from personnage 
        inner join partie_perso
        on personnage.id_perso = partie_perso.id_perso
        where men = FALSE and id_partie = ?),
        Request2 AS (select count(*) as result2 from personnage
        inner join partie_perso
        on personnage.id_perso = partie_perso.id_perso
        where id_partie = ?)
        SELECT CAST(Request1.result1 AS FLOAT) / CAST(Request2.result2 AS FLOAT) * 100 as SexRatio
        FROM Request1, Request2');

        $reqSexRatio->bindParam(1, $this->_idPartie);
        $reqSexRatio->bindParam(2, $this->_idPartie);


        $reqSexRatio -> execute();
        $row = $reqSexRatio -> fetch();
        $ratio = $row[0];
        $roundedResult = round($ratio,2);
        return $roundedResult;
    }
}
