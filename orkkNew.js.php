//<script>
    
    
function importScript(
        id,
        path
){
    let rnd = Math.random();
    let script = document.createElement("script");
    script.setAttribute("type","text/javascript");
    script.setAttribute("src",path+"?rnd="+rnd);
    script.setAttribute("id",id+"_script");
    document.getElementsByTagName("head")[0].appendChild(script);
}    
    
    
function importCss(
        id,
        path
){
    let link = document.createElement("link");
    let rnd = Math.random();
    link.setAttribute("rel","stylesheet");
    link.setAttribute("id",id+"_link");
    link.setAttribute("href",path+"?rnd="+rnd);
    document.getElementsByTagName("head")[0].appendChild(link);
}

    
{
    importScript("main","/_modules/orkkNew/main.js");
    importScript("supportjs","/_modules/orkkNew/support.js");
    importScript("main_comp","/_modules/orkkNew/components/Main/js/script.js");
    importScript("phonebook","/_modules/orkkNew/components/Phonebook/js/script.js");
    importScript("admin","/_modules/orkkNew/components/Admin/js/script.js");
    importScript("salary","/_modules/orkkNew/components/Salary/js/script.js");
    importScript("client","/_modules/orkkNew/components/Client/js/script.js");
    importScript("register","/_modules/orkkNew/components/Register/js/script.js");
    importScript("debtor","/_modules/orkkNew/components/Debtor/js/script.js");
    importScript("account","/_modules/orkkNew/components/Account/js/script.js");
    importScript("csv","/_modules/orkkNew/components/Csv/js/script.js");
    importScript("treeexplorer","/_modules/orkkNew/TreeExplorer/js/script.js");
    importCss("treeexplorercss","/_modules/orkkNew/TreeExplorer/css/style.css");
    importCss("supportcss","/_modules/orkkNew/support.css");
    importScript("support","/_modules/orkkNew/components/Support/js/script.js");
    importScript("report","/_modules/orkkNew/components/Report/js/script.js");
    
    importScript("clientList","/_modules/orkkNew/components/ClientList/js/script.js");
    
}



/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/   
/*---------------------------------------------------------------------------*/    
    
    function clickOn($tabname, $module){
        var randID=Math.floor(Math.random() * Math.floor(100000));
        getById('workfield').innerHTML='<div id="wf_'+randID+'" style="width: 100%;"></div>';
        include_dom('/_ui/html2var.js.php?var=wf_'+randID+'&url=/_modules/'+$module+'/html/'+$tabname+'.html');
        var btnList=getById('sidebarButtons').getElementsByClassName('tabBtn_a');
        for ($i=0; $i<btnList.length; $i++){
            getById(btnList[$i].id).className="tabBtn";
        }
        getById('listBtn_'+$tabname).className="tabBtn_a";
    }
    
    function orkScreenUnlock(){
        var $ls=getById('orkLockScreenDiv');
        if ($ls!=undefined){
            document.body.removeChild($ls);
        }
    }

    function orkScreenLock(){
        orkScreenUnlock();
        var $lockDiv=document.createElement("div");
        $lockDiv.id='orkLockScreenDiv';
        $lockDiv.style.display='table';
        $lockDiv.style.backgroundColor='rgba(0, 0, 0, 0.7)';
        $lockDiv.style.position='absolute';
        $lockDiv.style.top='0px';
        $lockDiv.style.left='0px';
        $lockDiv.style.width='100%';
        $lockDiv.style.height='100%';
        $lockDiv.style.zIndex='9999';
        var $topRowDiv=document.createElement("div");
        $topRowDiv.style.display='table-row';
        $topRowDiv.style.height='33%';

        var $cell1=document.createElement("div");
        $cell1.style.display='table-cell';
        var $cell2=document.createElement("div");
        $cell2.style.display='table-cell';
        var $cell3=document.createElement("div");
        $cell3.style.display='table-cell';
        $topRowDiv.appendChild($cell1);
        $topRowDiv.appendChild($cell2);
        $topRowDiv.appendChild($cell3);

        var $middleRowDiv=document.createElement("div");
        $middleRowDiv.style.display='table-row';
        $middleRowDiv.style.height='33%';
        var $cell4=document.createElement("div");
        $cell4.style.display='table-cell';
        $cell4.style.width='33%';
        var $cell5=document.createElement("div");
        $cell5.style.display='table-cell';
        $cell5.style.width='33%';
        $cell5.style.color='#fff';
        $cell5.style.fontSize='21px';
        $cell5.style.textAlign='center';
        $cell5.style.verticalAlign='middle';
        $cell5.innerHTML='<img src="/_modules/tv/img/pulse.gif"/><br/>Выполняется операция...';
        var $cell6=document.createElement("div");
        $cell6.style.display='table-cell';
        $cell6.style.width='33%';
        $middleRowDiv.appendChild($cell4);
        $middleRowDiv.appendChild($cell5);
        $middleRowDiv.appendChild($cell6);

        var $bottomRowDiv=document.createElement("div");
        $bottomRowDiv.style.display='table-row';
        $bottomRowDiv.style.height='33%';
        var $cell7=document.createElement("div");
        $cell7.style.display='table-cell';
        var $cell8=document.createElement("div");
        $cell8.style.display='table-cell';
        var $cell9=document.createElement("div");
        $cell9.style.display='table-cell';
        $bottomRowDiv.appendChild($cell7);
        $bottomRowDiv.appendChild($cell8);
        $bottomRowDiv.appendChild($cell9);

        $lockDiv.appendChild($topRowDiv);
        $lockDiv.appendChild($middleRowDiv);
        $lockDiv.appendChild($bottomRowDiv);

        document.body.appendChild($lockDiv);
    }
    
    
cls();
var wrapper = document.getElementById('wrapper');
var sidebar = document.createElement('div');
sidebar.className='sidebar';
sidebar.id='sidebar';
sidebar.style.float='left';
var workfield = document.createElement('div');
workfield.id='workfield';
workfield.className='workfield';
workfield.style.height='100%';
workfield.style.marginLeft='64px';
workfield.style.paddingLeft='10px';
workfield.style.paddingTop='48px';
workfield.style.boxSizing='border-box';
workfield.style.overflowY='auto';
workfield.style.position = "relative";
wrapper.appendChild(sidebar);
wrapper.appendChild(workfield);
    
var sbhttp=new XMLHttpRequest();
sbhttp.onreadystatechange=function()
{
    if (sbhttp.readyState==4 && sbhttp.status==200){
        if (document.getElementById('sidebar')!=undefined){
            document.getElementById('sidebar').innerHTML=sbhttp.responseText;
        }
    }
}
sbhttp.open("POST","/_modules/orkkNew/helpers/sidebarGen.php",true);
sbhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
sbhttp.send('');

include_dom('/_ui/html2var.js.php?var=workfield&url=/_modules/orkkNew/html/index.html');



/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/










