function infoSwitch($tab){
    if (getById('infoDiv_common')!=undefined){getById('infoDiv_common').style.display='none';}
    if (getById('infoDiv_status')!=undefined){getById('infoDiv_status').style.display='none';}
    if (getById('infoDiv_payment')!=undefined){getById('infoDiv_payment').style.display='none';}
    if (getById('infoDiv_diag')!=undefined){getById('infoDiv_diag').style.display='none';}
    if (getById('infoDiv_iptv')!=undefined){getById('infoDiv_iptv').style.display='none';}
    if (getById('infoDiv_routers')!=undefined){getById('infoDiv_routers').style.display='none';}
    
    //userInfo_common
    if (getById('userInfo_common')!=undefined){getById('userInfo_common').className='infoWaiting';}
    if (getById('userInfo_status')!=undefined){getById('userInfo_status').className='infoWaiting';}
    if (getById('userInfo_payment')!=undefined){getById('userInfo_payment').className='infoWaiting';}
    if (getById('userInfo_diag')!=undefined){getById('userInfo_diag').className='infoWaiting';}
    if (getById('userInfo_iptv')!=undefined){getById('userInfo_iptv').className='infoWaiting';}
    if (getById('userInfo_routers')!=undefined){getById('userInfo_routers').className='infoWaiting';}
    
        
    $tname='infoDiv_'+$tab;
    $hname='userInfo_'+$tab;
    
    if (getById($tname)!=undefined){
        getById($tname).style.display='block';
    }
    
    if (getById($hname)!=undefined){
        getById($hname).className='infoSelected';
    }
}

function loadIPTV_data(){};
function supLoadRouters(){};

function orkk_supSelectExec($profile, $ename){
    $elems=getById('supExecSelector').getElementsByClassName('selectTile_h');
    for ($i=0; $i<$elems.length; $i++){
        $elems[$i].className='selectTile';
    }

    $elems=getById('supExecSelector').getElementsByClassName('selectTile');
    for ($i=0; $i<$elems.length; $i++){
        if ($elems[$i].id=='selector_'+$profile){
            $elems[$i].className='selectTile_h';
        }
    }
    
    getById('supSelectedExec').innerHTML='Выбранный исполнитель: <span style="font-weight: lighter;">'+$ename+'</span>.';
    getById('selSupActiveEx').value=$profile;
}

function orkk_supStartTask(){
    if (getById('selSupActiveID')!=undefined){
        $ticket=getById('selSupActiveID').value;
        $ticket=$ticket.trim();
        $eid=getById('selSupActiveEx').value;
        if ($eid!=''&&$ticket!=''){
            include_dom('/_modules/support/helpers/startSup.php?ticket='+$ticket+'&exec='+$eid);
            unmsgbox();
        }
    }
}

function orkk_supStopTask(){
    if (getById('supStop_close')!=undefined){
        if (getById('supStop_close').checked==true){
            if (getById('selSupActiveID')!=undefined){
                $ticket=getById('selSupActiveID').value;
                if (getById('supCloseText')!=undefined){
                    $text=getById('supCloseText').value;
                    $text=$text.trim();
                    if ($text!=''){
                        include_dom('/_modules/support/helpers/closeSup.php?ticket='+$ticket+'&text='+$text);
                        unmsgbox();                            
                    }
                }
            }            
        }
        else
        {
            if (getById('selSupActiveID')!=undefined){
                $ticket=getById('selSupActiveID').value;
                include_dom('/_modules/support/helpers/resetSup.php?ticket='+$ticket);
                unmsgbox();                            
            }
        }
    }    
}

function orkk_editSup($ticket){
    xmlhttps=new XMLHttpRequest();
    xmlhttps.onreadystatechange=function()
    {
        if (xmlhttps.readyState==4 && xmlhttps.status==200){
            msgbox('Редактирование заявки', xmlhttps.responseText, 'supEditTask(\''+$ticket+'\')', 'unmsgbox()');
            setTimeout(function() { createEditPicker() }, 600);
        }
    }
    xmlhttps.open("POST","/_modules/support/helpers/genSupEdit.php",true);
    xmlhttps.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    $pline='ticket='+$ticket;
    xmlhttps.send($pline);
}

function orkk_supEditTask($ticket){
    getById('ns_notify').innerHTML='&nbsp;';
    if (getById('ns_notime').checked==true){
        $stime='0';
    }
    if (getById('ns_scheduled').checked==true){
        $stime=$('#datetimepicker').datetimepicker('getValue').getTime();
        $stime=Math.floor($stime/1000);
        $stime=$stime-($stime-Math.floor($stime/60)*60);
    }
    
    $prior='s';
    
    if (getById('ns_lowprior').checked==true){
        $prior='l';
    }
    
    if (getById('ns_highprior').checked==true){
        $prior='h';
    }    
    
    $text=getById('ns_comment').value;
    $text=$text.trim();

    if ($text==''){
        getById('ns_notify').innerHTML='Отсутствует описание заявки...';
        return false;
    }

    $sphone=getById('ns_sphone').value;
    $mphone=getById('ns_mphone').value;

    xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            loadSupList('0', '0');
        }
    }
    xmlhttp.open("POST","/_modules/support/helpers/editPost.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    $pline='time='+$stime+'&prior='+$prior+'&ticket='+$ticket+'&sphone='+$sphone+'&mphone='+$mphone+'&text='+$text;
    xmlhttp.send($pline);          
        
    unmsgbox();
    //alert ($dnum+' at '+$stime+' with '+$prior+': '+$text);
}

function orkk_supPin($ticket){
    include_dom('/_modules/support/helpers/supPin.php?ticket='+$ticket);
}

function orkk_supLoadStatus(){
    //userCardID
    xmlhttpa=new XMLHttpRequest();
    xmlhttpa.onreadystatechange=function()
    {
        if (xmlhttpa.readyState==4 && xmlhttpa.status==200){
            if (getById('userCardID')!=undefined){
                if (getById('userCardID').value==$cval){
                    getById('infoDiv_status').innerHTML=xmlhttpa.responseText;
                    setTimeout(function() { orkk_supCheckSwitch() }, 600);
                }
                else
                {
                    getById('infoDiv_status').innerHTML='WRCVAL';
                }
            }
            else
            {
                getById('infoDiv_status').innerHTML='UNDEF';
            }
        }
    }
    $rnd=Math.random(9999);

    xmlhttpa.open("POST","/_modules/orkkNew/support/getAuthLog.php?rnd="+$rnd,true);
    xmlhttpa.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    $cval=getById('userCardID').value;
    $pline='id='+$cval;
    xmlhttpa.send($pline);    
}

function orkk_supLoadPayment(){
    //userCardID
    xmlhttppay=new XMLHttpRequest();
    xmlhttppay.onreadystatechange=function()
    {
        if (xmlhttppay.readyState==4 && xmlhttppay.status==200){
            if (getById('userCardID')!=undefined){
                if (getById('userCardID').value==$cval){
                    getById('infoDiv_payment').innerHTML=xmlhttppay.responseText;
                    setTimeout(function() { orkk_supCheckSwitch() }, 600);
                }
                else
                {
                    getById('infoDiv_payment').innerHTML='WRCVAL';
                }
            }
            else
            {
                getById('infoDiv_payment').innerHTML='UNDEF';
            }
        }
    }
    $rnd=Math.random(9999);

    xmlhttppay.open("POST","/_modules/support/helpers/getPayLog.php?rnd="+$rnd,true);
    xmlhttppay.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    $cval=getById('userCardID').value;
    $pline='id='+$cval;
    xmlhttppay.send($pline);    
}

function orkk_supCheckSwitch(){
    orkk_supCheckUserIP();
    if (getById('switchIP')!=undefined){
        $ip=getById('switchIP').value;
        if ($ip!=''){
            xmlhttpc=new XMLHttpRequest();
            xmlhttpc.onreadystatechange=function()
            {
                if (xmlhttpc.readyState==4 && xmlhttpc.status==200){
                    if (getById('switchIP')!=undefined){
                        if (getById('switchIP').value==$ip){
                            $swname=getById('switchName').value;
                            $ret=xmlhttpc.responseText;
                            $rett=$ret;
                            $isOnline = ($rett.match(/сети/g) || []).length;
                            getById('sw_'+$swname+'_status').innerHTML=xmlhttpc.responseText;
                            if ($isOnline==1){
                                orkk_supCheckPort();
                                orkk_supCheckPortErrors();
                            }
                            else
                            {
                                $swport=getById('switchPort').value;
                                getById('sw_'+$swname+'__'+$swport+'_status').innerHTML='N/A';
                            }
                        }
                    }
                }
            }
            xmlhttpc.open("POST","/_modules/support/helpers/swCheck.php",true);
            xmlhttpc.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            $pline='ip='+$ip;
            xmlhttpc.send($pline);             
        }
    }
}

function orkk_supCheckPort(){
    if (getById('switchIP')!=undefined){
        $ip=getById('switchIP').value;
        $swport=getById('switchPort').value;
        $swname=getById('switchName').value;
        if ($ip!=''){
            xmlhttpcc=new XMLHttpRequest();
            xmlhttpcc.onreadystatechange=function()
            {
                if (xmlhttpcc.readyState==4 && xmlhttpcc.status==200){
                    if (getById('switchIP')!=undefined){
                        if (getById('switchIP').value==$ip){
                            $swname=getById('switchName').value;
                            getById('sw_'+$swname+'__'+$swport+'_status').innerHTML=xmlhttpcc.responseText;
                        }
                    }
                }
            }
            xmlhttpcc.open("POST","/_modules/support/helpers/swPortCheck.php",true);
            xmlhttpcc.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            $pline='swname='+$swname+'&port='+$swport;
            xmlhttpcc.send($pline);             
        }
    }    
}

function orkk_supCheckUserSession($ip, $id){
    if (getById('usersess_'+$id)!=undefined){
        var xmlhttpcus=new XMLHttpRequest();
        xmlhttpcus.onreadystatechange=function()
        {
            if (xmlhttpcus.readyState==4 && xmlhttpcus.status==200){
                if (getById('usersess_'+$id)!=undefined){
                    $usersess=xmlhttpcus.responseText;
                    getById('usersess_'+$id).innerHTML=$usersess;
                    getById('transitBar10s').classList.add("transit10s");
                    getById('transitBar10s').classList.add("transit10s0");
                    setTimeout(function() { orkk_supCheckUserSession($ip, $id) }, 10000);
                }
            }
        }
        xmlhttpcus.open("POST","/_modules/support/helpers/getUserSession.php",true);
        xmlhttpcus.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        $pline='ip='+$ip;
        xmlhttpcus.send($pline);        
    }
}

function orkk_supCheckUserIP(){
    if (getById('userIPID')!=undefined){
        $id=getById('userIPID').value;
        if ($id!=''){
            xmlhttpccui=new XMLHttpRequest();
            xmlhttpccui.onreadystatechange=function()
            {
                if (xmlhttpccui.readyState==4 && xmlhttpccui.status==200){
                    if (getById('userIPID')!=undefined){
                        if (getById('userIPID').value==$id){
                            $userip=xmlhttpccui.responseText;
                            getById('sw_'+$id+'_userip').innerHTML=$userip;
                            orkk_supCheckUserSession($userip, $id);
                            //getById('sw_'+$id+'_userip').innerHTML='Coming soon...';
                        }
                    }
                }
            }            
            xmlhttpccui.open("POST","/_modules/support/helpers/getUserIP.php",true);
            xmlhttpccui.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            $pline='id='+$id;
            xmlhttpccui.send($pline);
        }
    }     
}
    

function orkk_supCheckPortErrors(){
    if (getById('switchIP')!=undefined){
        $ip=getById('switchIP').value;
        $swport=getById('switchPort').value;
        $swname=getById('switchName').value;
        if ($ip!=''){
            xmlhttpe=new XMLHttpRequest();
            xmlhttpe.onreadystatechange=function()
            {
                if (xmlhttpe.readyState==4 && xmlhttpe.status==200){
                    if (getById('switchIP')!=undefined){
                        if (getById('switchIP').value==$ip){
                            $swname=getById('switchName').value;
                            getById('sw_'+$swname+'__'+$swport+'_errors').innerHTML=xmlhttpe.responseText;
                        }
                    }
                }
            }
            xmlhttpe.open("POST","/_modules/support/helpers/swPortCheckErrors.php",true);
            xmlhttpe.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            $pline='swname='+$swname+'&port='+$swport;
            xmlhttpe.send($pline);             
        }
    }        
}

function orkk_supLoadCallLog(){
    if (getById('calllog')!=undefined){
        xmlhttpcl=new XMLHttpRequest();
        xmlhttpcl.onreadystatechange=function()
        {
            if (xmlhttpcl.readyState==4 && xmlhttpcl.status==200){
                if (getById('calllog')!=undefined){
                    getById('calllog').innerHTML=xmlhttpcl.responseText;
                }
            }
        }
        xmlhttpcl.open("POST","/_modules/support/helpers/loadCallLog.php",true);
        xmlhttpcl.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttpcl.send($pline);    
        setTimeout(function() { supLoadCallLog() }, 2000);
    }       
}

function orkk_supRunFromLog($dnum, $uname, $addr, $textnon, $ticket){
    unmsgblock();
    initSupStart($dnum, $uname, $addr, $textnon, $ticket);
}

function orkk_supPreviewTicket($ticket){
    msgblock('Предпросмотр заявки', '<input type="hidden" id="userCardID" value="'+$ticket+'"/><div class="smallinfodiv" id="userPreviewBlockFrame" style="width: 100%; display: block;"><div class="smallheader">Карточка абонента:</div><div id="userPreviewBlock" style="width: 100%;"></div></div>' );
    setTimeout(function() { include_dom('/_modules/support/helpers/getAuthLogByTicket.php?ticket='+$ticket); }, 500);    
}

function orkk_supMonitorTrunks(){
    if (getById('supTrunkList')!=undefined){
        xmlhttpmt=new XMLHttpRequest();
        xmlhttpmt.onreadystatechange=function()
        {
            if (xmlhttpmt.readyState==4 && xmlhttpmt.status==200){
                if (getById('supTrunkList')!=undefined){
                    getById('supTrunkList').innerHTML=xmlhttpmt.responseText;
                }
            }
        }
        xmlhttpmt.open("POST","/_modules/support/helpers/loadTrunkList.php",true);
        xmlhttpmt.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttpmt.send($pline);    
    }    
}

function orkk_supMonSwitch($id){
    if (getById('container')!=undefined){
        $elems=getById('container').getElementsByClassName('graphContainer');
        for ($i=0; $i<$elems.length; $i++){
            if ($elems[$i].id==$id){
                $elems[$i].style.display='block';
            }
            else
            {
                $elems[$i].style.display='none';
            }
        }
    }
}