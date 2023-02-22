<?php
$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net';

class Billing {
    
    private $db;
    
    
    
    public function __construct(){
        $glob_dbname="";              //Database name
        $glob_sqlserv="";          //MySQL Server name
        $glob_sqlname="";         //MySQL Username
        $glob_sqlpass="";           //MySQL Password
        

        $this->db = mysqli_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass,$glob_dbname);
        $sql = "SET NAMES 'cp1251';";
        mysqli_query($this->db,$sql);
    }
    
    /*--------------------------------------------------*/
    
    public function getNumber(){
        $sql = "
            SELECT number
            FROM `agreements`
            WHERE number LIKE '6___'
            ORDER BY number DESC
            LIMIT 1 ";





        $query = mysqli_query($this->db,$sql);        
        $row = mysqli_fetch_array($query);
        if ($row){
            return ((int)$row["number"]) + 1; 
        }
    }
    
    /*--------------------------------------------------*/
    
    public function getServiceList(
            $year,
            $month
    ){
        
        $sql = "SELECT agreements.date, usbox_charge.charge_date, accounts.name, agreements.number, usbox_charge.amount, categories.descr, usbox_services.comment
        FROM accounts
        RIGHT OUTER JOIN agreements ON agreements.uid = accounts.uid
        RIGHT OUTER JOIN usbox_charge ON usbox_charge.agrm_id = agreements.agrm_id
        RIGHT OUTER JOIN usbox_services ON usbox_charge.serv_id = usbox_services.serv_id
        RIGHT OUTER JOIN vgroups ON vgroups.uid = accounts.uid
        RIGHT OUTER JOIN categories ON categories.cat_idx = usbox_services.cat_idx
        WHERE accounts.archive =0
        AND accounts.type =1
        AND categories.tar_id = '804'
        AND usbox_charge.charge_date LIKE '{$year}-{$month}%'
        GROUP BY usbox_charge.record_id";
        $query = mysqli_query($this->db,$sql);
        $result = [];
        while($row = mysqli_fetch_array($query))
        {
            $result[] = [
                 "date" => $row['date'],
                 "charge_date" => $row['charge_date'],
                 "name" => $row['name'],
                 "number" => $row['number'],
                 "amount" => $row['amount'],
                 "descr" => $row['descr'],
                 "comment" => $row['comment'],
             ];

        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getServiceListByPeriod(
            $start,
            $end
    ){
        $sql = "SELECT agreements.date, usbox_charge.charge_date, accounts.name, agreements.number, usbox_charge.amount, categories.descr, usbox_services.comment
        FROM accounts
        RIGHT OUTER JOIN agreements ON agreements.uid = accounts.uid
        RIGHT OUTER JOIN usbox_charge ON usbox_charge.agrm_id = agreements.agrm_id
        RIGHT OUTER JOIN usbox_services ON usbox_charge.serv_id = usbox_services.serv_id
        RIGHT OUTER JOIN vgroups ON vgroups.uid = accounts.uid
        RIGHT OUTER JOIN categories ON categories.cat_idx = usbox_services.cat_idx
        WHERE accounts.archive =0
        AND accounts.type =1
        AND categories.tar_id = '804'
        AND DATE(usbox_charge.charge_date) >= DATE('{$start}')
        AND DATE(usbox_charge.charge_date) <= DATE('{$end}')
        GROUP BY usbox_charge.record_id";
        $query = mysqli_query($this->db,$sql);
        $result = [];
        while($row = mysqli_fetch_array($query))
        {
            $result[] = [
                 "date" => $row['date'],
                 "charge_date" => $row['charge_date'],
                 "name" => $row['name'],
                 "number" => $row['number'],
                 "amount" => $row['amount'],
                 "descr" => $row['descr'],
                 "comment" => $row['comment'],
             ];

        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getFlServiceList(
            $year,
            $month
    ){
        
        $sql = "SELECT agreements.date, usbox_charge.charge_date, accounts.name, agreements.number, usbox_charge.amount, categories.descr, usbox_services.comment
	FROM accounts
	RIGHT OUTER JOIN agreements ON agreements.uid = accounts.uid
	RIGHT OUTER JOIN usbox_charge ON usbox_charge.agrm_id = agreements.agrm_id
	RIGHT OUTER JOIN usbox_services ON usbox_charge.serv_id = usbox_services.serv_id
	RIGHT OUTER JOIN vgroups ON vgroups.uid = accounts.uid
	RIGHT OUTER JOIN categories ON categories.cat_idx = usbox_services.cat_idx
	RIGHT OUTER JOIN usergroups_staff ON usergroups_staff.uid = accounts.uid
	WHERE accounts.type =2
	AND usergroups_staff.group_id =12
	AND accounts.archive =0
	AND categories.tar_id = '804'
	AND usbox_charge.charge_date LIKE '{$year}-{$month}%'
	GROUP BY usbox_charge.record_id";
        $query = mysqli_query($this->db,$sql);
        $result = [];
        while($row = mysqli_fetch_array($query))
        {
            $result[] = [
                 "date" => $row['date'],
                 "charge_date" => $row['charge_date'],
                 "name" => $row['name'],
                 "number" => $row['number'],
                 "amount" => $row['amount'],
                 "descr" => $row['descr'],
                 "comment" => $row['comment'],
             ];

        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getBalanceTable(){
        $sql = "SELECT *"
               ."FROM agreements";
        $query = mysqli_query($this->db, $sql);
        $result = [];
        while($row = mysqli_fetch_array($query)){
            if ($row["number"]){
                $result[$row["number"]] = $row["balance"];
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    public function getAmountList(){
        $date = date("Y-m-d 00:00:00",time());
        $sql = "SELECT agreements.number as num, buf.rent AS rent
FROM agreements 
INNER JOIN (
    SELECT vgroups.agrm_id, SUM(tarifs.rent) AS rent
    FROM vgroups
    INNER JOIN tarifs
    ON vgroups.tar_id = tarifs.tar_id
    WHERE vgroups.archive = 0
    AND NOT vgroups.acc_offdate BETWEEN '0000-00-00 00:00:01' AND '{$date}'
    GROUP BY vgroups.agrm_id
    ) AS buf
ON agreements.agrm_id = buf.agrm_id";
        $query = mysqli_query($this->db,$sql);
        $result = [];
        while($row = mysqli_fetch_array($query))
        {
            if ($row["num"]){
                $result[$row["num"]] = $row["rent"];
            }  
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
}



