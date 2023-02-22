<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php');
    //error_reporting(E_ALL); 
    set_time_limit(600);
    //ini_set('display_errors', 1);
    ini_set('memory_limit', '512M');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    function cnvNum($num){
        $num=str_replace(',', '.', $num);
        $num=str_replace(' ', '', $num);
        return 1*(trim($num));
    }
    
    function getAccount($uid){
        $glob_dbname="billing";              //Database name
        $glob_sqlserv="192.168.1.112";          //MySQL Server name
        $glob_sqlname="root";         //MySQL Username
        $glob_sqlpass="Argtc8Sk0";           //MySQL Password   
        
        $db=mysql_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass);
        mysql_select_db($glob_dbname,$db);

        $sql = "SET NAMES 'utf8';";
        $result = mysql_query($sql,$db);  
        $sql = 'SET CHARACTER SET "utf8"';
        $result = mysql_query($sql,$db);  

        $sql = "SELECT * FROM `agreements` WHERE `uid`='".$uid."' AND `archive`='0';";
        $result = mysql_query($sql,$db);
        
        $ret='';
        
        if (mysql_num_rows($result)==0){
            return $ret;
        }       
        
        if (mysql_num_rows($result)!=1){
            return $ret;
        }               
        
        $row=mysql_fetch_array($result);
        return $row["number"];           
    }
    
    function getIINList(){
        $bins=array();
        
        $glob_dbname="billing";              //Database name
        $glob_sqlserv="192.168.1.112";          //MySQL Server name
        $glob_sqlname="root";         //MySQL Username
        $glob_sqlpass="Argtc8Sk0";           //MySQL Password   
        
        $db=mysql_connect($glob_sqlserv,$glob_sqlname,$glob_sqlpass);
        mysql_select_db($glob_dbname,$db);

        $sql = "SET NAMES 'utf8';";
        $result = mysql_query($sql,$db);  
        $sql = 'SET CHARACTER SET "utf8"';
        $result = mysql_query($sql,$db);  

        $sql = "SELECT * FROM `accounts` WHERE `archive`='0';";
        $result = mysql_query($sql,$db);
        
        $cnt=0;
        
        while ($row=mysql_fetch_array($result)){
            $iin=$row["inn"];
            if ($iin!='091140000180'){
                if (isset($bins[$iin])){$parr=$bins[$iin];}else{$parr=array();}
                $acc=getAccount($row["uid"]);
                if ($acc!=''){
                    if (!in_array($acc, $parr)){array_push($parr, $acc);}
                    $bins[$iin]=$parr;
                }
            }
        }            
        
        //print 'TOTAL BINS: '.count($bins).'<br/>';
        //print 'TOTAL CNT: '.$cnt.'<br/>';
        //print ($bins['_180240037962']).'<-- <br/>';
        return $bins;
    }
    
$csv=file_get_contents($_FILES["uploaded_file"]["tmp_name"]);
$csv=iconv('UTF-8', 'cp1251//TRANSLIT', $csv);
//$csv=str_replace("\n", ' ', $csv);
//$csv=str_replace("\r", "\n", $csv);
$arr=explode("\n", $csv);
$bins=getIINList();
?>
<div style="display: table; width: 100%; border: 1px var(--modColor) solid; font-size: 12px;">
    <div style="display: table-row; background-color: var(--modColor); color: #fff;">
        <div style="display: table-cell; width: 30px;">
            <input type="checkbox" id="orkPaymentsSelAll" onclick="orkPaymentsSelAll()"/>
        </div>
        <div style="display: table-cell; width: 120px;">
            № договора:
        </div>
        <div style="display: table-cell; width: 150px;">
            Дата:
        </div>
        <div style="display: table-cell; width: 100px;">
            Сумма:
        </div>
        <div style="display: table-cell; width: 150px;">
            БИН/ИИН:
        </div>
        <div style="display: table-cell; width: 400px;">
            Наименование:
        </div>
        <div style="display: table-cell;">
            Комментарий:
        </div>
    </div>
<?php
$odd=false;
foreach ($arr as $item){
    $item=trim($item);
    $cline=explode(';', $item);
    //print '<br/>';
    //print '<pre>';
    //print_r($cline);
    //print '</pre>';
    if (count($cline)>25){
        $date=$cline[1];
        $summ=cnvNum($cline[3]);
        $iin=trim($cline[12]);
        $name=$cline[13];
        $comment=$cline[19];
        if (isset($bins[$iin])){
            $hash=md5($date.$summ.$iin.$name.$comment);
            $clr='';
            if ($odd){
                $clr='background-color: var(--modColor_lightest);';
            }
            $odd=!$odd;
            ?>
<div style="display: table-row; border-bottom: 1px var(--modColor) dotted; margin-top: 20px;<?=$clr?>" class="uplBizPayLine">
    <div style="display: table-cell; height: 26px; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
        <input type="hidden" class="uplBizPayLine_md5" value="<?=$hash?>"/>
        <?php
            $chk='';
            if (!objCheckExist('/paylog/sber/'.$hash, 'raw')){
                $chk=' checked ';
            }
        ?>
        <input type="checkbox" <?=$chk?> class="uplBizPayLine_check" onclick="if (this.checked==false){getById('orkPaymentsSelAll').checked=false;}"/>
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
<?php
    if (count($bins[$iin])==1){
        ?>
        <select disabled class="uplBizPayLine_user" style="width: 100px; opacity: 0.7;">
            <option selected value="<?=$bins[$iin][0]?>"><?=$bins[$iin][0]?></option>
        </select>
        <?php
    }
    else
    {
        $isSel=false;
        ?>
        <select class="uplBizPayLine_user" style="width: 100px;">
            <?php
            foreach ($bins[$iin] as $oiin){
                $seltag='';
                if (!$isSel){
                    $seltag=' selected ';
                    $isSel=true;
                }
                ?>
            <option <?=$seltag?> value="<?=$oiin?>"><?=$oiin?></option>
                <?php
            }
            ?>
        </select>        
        <?php
    }
?>
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
        <?=$date?>
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
        <input type="text" value="<?=$summ?>" style="width: 70px; padding: 0px; border: 1px #ccc solid;" class="uplBizPayLine_sum"/> тг.
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
        <?=$iin?>
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle;">
        <?=$name?>
    </div>
    <div style="display: table-cell; border-top: 1px var(--modColor) dotted; vertical-align: middle; font-size: 10px;">
        <?=$comment?>
    </div>
</div>
            <?php
            /*
            print 'Date='.$date."\n";
            print 'Sum='.$summ."\n";
            print 'IIN='.$iin."\n";
            print 'Name='.$name."\n";
            print 'Cmnt='.$comment."\n";
            print 'MD5='.$hash."\n"; 
            print '============================================================'."\n \n";
            if (count($bins[$iin])>1){
                print "\n";
                print '<b>DOUBLER</b>';
                print "\n";
                print_r($bins[$iin]);
                print "\n";
            }
            */
        }
    }
    //print 'COUNT='.count($cline)."\n";
}
?>
</div>
<div style="width: 100%; text-align: center;">
    <button onclick="orkProcessPayments()">Провести</button>
</div>