/*---------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*
             * fMsgBlock
             * 
             * ---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/




            function fUnMsgBlock(
                    $blockId = 'msgbboxdiv',
            ){
                $obj=document.getElementById($blockId);
                if ($obj!=undefined){
                    let onclose = $obj.onMsgClose;
                    if (onclose){
                        onclose();
                    }
                    document.body.removeChild($obj);
                }
                removeJsCss($blockId);
                
            }

            function fMsgBlock(
                    title, 
                    content, 
                    height,
                    width,
                    blockId = 'fmsgbboxdiv',
                    onclose = null,
                    caller = null
            ){

                let msgBack = document.createElement("div");
                msgBack.style = `
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        left: 0;
                        top: 0;
                        background-color: rgba(255, 255, 255, 0.7);
                        z-index: 4000;
                `;
                msgBack.setAttribute("id",blockId);
                msgBack.onMsgClose = onclose;
                msgBack.msgCaller = caller;
                msgBack.classList.add("msgBack");
//                msgBack.setAttribute("onclick","fUnMsgBlock("+'"'+blockId+'"'+")");
                let msgBox = document.createElement("div");
                msgBox.style = `
                        position: absolute;
                        box-sizing: border-box;
                        z-index: 4050;
                        top: 50%;
                        left: 50%;
                        display: flex;
                        justify-content: flex-start;
                        flex-direction: column;
                        background-color: var(--modBGColor);
                        border: 2px solid var(--modColor_dark);

                `;
                msgBox.style.width = width+"px";
                msgBox.style.height = height+"px";
                msgBox.style["margin-left"] = Math.floor(-width/2)+"px";
                msgBox.style["margin-top"] = Math.floor(-height/2)+"px";
            

                let msgBoxHeader = document.createElement("div");
                msgBoxHeader.style = `
                        display: flex;
                        height: 35px;
                        justify-content: space-between;
                        background-color: var(--modColor);
                        color: var(--modBGColor);
                        vertical-align: middle;
                `;
                let msgHeaderText = document.createElement("div");
                msgHeaderText.style = `
                        display: flex;
                        justify-content: center;
                        padding: 0px 20px;
                        align-items: center;
                        font-size: 22px;
                `;
                msgHeaderText.textContent = title;
                let msgCloseButton = document.createElement("div");
                msgCloseButton.style = `
                        width: 50px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        box-sizing: border-box;
                        font-size: 20px;
                        font-family: "Lato";
                        text-align: center;
                        height: 33px;
                        cursor: pointer;
                `;
                msgCloseButton.setAttribute("onclick","fUnMsgBlock("+'"'+blockId+'"'+")");
//                msgCloseButton.addEventListener("click",function(){
//                    fUnMsgBlock(blockId);
//                });
                msgCloseButton.textContent = "X";
                msgCloseButton.setAttribute("id",blockId+"_closeButton");
                let msgData = document.createElement("div");
                msgData.style = `
                        width: 100%;
                        height: calc(100% - 35px);
                `;
                msgData.classList.add("fmsgBlock_data");
                msgData.innerHTML = content;
                msgBoxHeader.appendChild(msgHeaderText);
                msgBoxHeader.appendChild(msgCloseButton);
                msgBox.appendChild(msgBoxHeader);
                msgBox.appendChild(msgData);
                msgBack.appendChild(msgBox);
                document.body.appendChild(msgBack);
                document.getElementById(blockId+"_closeButton").addEventListener("mouseover", (event) =>{
                    event.target.style["background-color"] = "red";
                });
                document.getElementById(blockId+"_closeButton").addEventListener("mouseout", (event) =>{
                    event.target.style["background-color"] = "inherit";
                });

            }


            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/
            /*---------------------------------------------------------------------------*/




/*---------------------------------------------------------------------------*/

function getXhr(
        params
//        component
//        body
){  
    let body = toBody(params);
    return new Promise(function(callback){
        let xhr = new XMLHttpRequest();
        xhr.onload = function(){
            if (xhr.status == 200){
                orkScreenUnlock();
                callback(xhr.responseText);
            }
        };
        xhr.open("GET","/_modules/orkkNew/components/router.php" + "?" + body);
        xhr.send();
        orkScreenLock();
    });
}

/*---------------------------------------------------------------------------*/

function postXhr(
        params
//        component
//        body
){  
    let body = toBody(params);
    return new Promise(function(callback){
        let xhr = new XMLHttpRequest();
        xhr.onload = function(){
            if (xhr.status == 200){
                orkScreenUnlock();
                callback(xhr.responseText);
            }
        };
        xhr.open("POST","/_modules/orkkNew/components/router.php");
        xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhr.send(body);
        orkScreenLock();
    });
}

/*---------------------------------------------------------------------------*/

function saveFileFromRouter(
        params
){
    let form = document.createElement("form");
    form.action = "/_modules/orkkNew/components/router.php";
    form.method = "POST";
    for(let key in params){
        form.innerHTML += '<input type=text name="' + key + '" value=' + "'" + params[key] + "'" + '>';
    }
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

/*---------------------------------------------------------------------------*/

function getNewTab(
        params
//        component
//        body
){  
    let body = toBody(params);
    let url = "/_modules/orkkNew/components/router.php" + "?" + body;
    let print = window.open(url);
    print.print();
}

/*---------------------------------------------------------------------------*/

function old_postXhr(
        params
//        component
//        body
){  
    let form = document.createElement("form");
    form.setAttribute("enctype","multipart/form-data");
    form.setAttribute("method","POST");
    for(let key in params){
        if (key != "file"){
            let buf = document.createElement("input");
            buf.setAttribute("value",params[key]);
            buf.setAttribute("name",key);
            form.appendChild(buf);
        }
        else{
            let buf = params[key].cloneNode(true);
            form.appendChild(buf);
        }
    }
    let body = new FormData(form);
    return new Promise(function(callback){
        let xhr = new XMLHttpRequest();
        xhr.onload = function(){
            if (xhr.status == 200){
                orkScreenUnlock();
                callback(xhr.responseText);
            }
        };
        
//        xhr.open("POST","/_modules/orkkNew/components/router.php",true);
        xhr.open("POST", "/_modules/orkkNew/components/router.php",true);
//        xhr.setRequestHeader("Content-Type", "application/json;charset=utf-8");
        xhr.send(body);
        orkScreenLock();
    });
}

/*---------------------------------------------------------------------------*/


function addJs(
        name
){
    let js = document.getElementById("script_"+name);
    if (!js){
        return;
    }
    js = js.textContent.trim();
    let script = document.createElement("script");
    script.setAttribute("type","text/javascript");
    script.setAttribute("src",js);
    script.setAttribute("id",name+"_script");
    document.getElementsByTagName("head")[0].appendChild(script);
}

/*---------------------------------------------------------------------------*/

function addCss(
        name
){
    let js = document.getElementById("style_"+name).textContent.trim();
    let link = document.createElement("link");
    link.setAttribute("rel","stylesheet");
    link.setAttribute("id",name+"_link");
    link.setAttribute("href",css);
    document.getElementsByTagName("head")[0].appendChild(link);
}

/*---------------------------------------------------------------------------*/

function removeJsCss(
        id

){
    let elem = document.getElementById(id+"_script");
    if (elem){
        document.getElementsByTagName("head")[0].removeChild(elem);
    }
    elem = document.getElementById(id+"_link");
    if (elem){
        document.getElementsByTagName("head")[0].removeChild(elem);
    }
}

/*---------------------------------------------------------------------------*/

function inputCheckboxToggle(
        checkbox
){
    if (checkbox.classList.contains("readonly")){
        return;
    }
    checkbox.classList.toggle("inputCheckbox_checked");
}

/*---------------------------------------------------------------------------*/

function inputRadioToggle(
        radio
){
    let container = radio.closest(".inputRadio");
    if (container.classList.contains("readonly")){
        return;
    }
    let radioList = Array.from(container.querySelectorAll(".inputCheckbox"));
    for(let el of radioList){
        el.classList.remove("inputCheckbox_checked");
    }
    radio.classList.add("inputCheckbox_checked");
}

/*---------------------------------------------------------------------------*/


function toBody(
        ar
){
    let result = "";
    for(let key in ar){
        result += key + "=" + ar[key] + "&";
    }
    return result;
}




/*---------------------------------------------------------------------------*/

function initIndex(){
    getXhr({
        action : "getIndexPage"
    }).then((html) => {
       newPage(html,false); 
    });
}

/*---------------------------------------------------------------------------*/

function reloadIndex(
        button
){
    let name = "Client";
    getXhr({
        action : "getClientListPage"
    }).then((html) => {
        reloadPage(button,html,function(container){
            let trList = Array.from(container.querySelectorAll("tbody tr"));
            for(let tr of trList){
                let params = JSON.parse(getVarFromContainer(tr,"paramsJson"));
                tr.filterParams = params;
            }
            clientListFilter(container.querySelector("tbody"));
        });
    });
}

/*---------------------------------------------------------------------------*/

function initStat(){
    let name = "Client";
    getXhr({
        action : "getClientListPage"
    }).then((html) => {
        newPage(html,false,function(container){
            let trList = Array.from(container.querySelectorAll("tbody tr"));
            for(let tr of trList){
                let params = JSON.parse(getVarFromContainer(tr,"paramsJson"));
                tr.filterParams = params;
            }
        });
    });
}

/*---------------------------------------------------------------------------*/

function inputPhoneFocus(
        inp
){
//    let str = inp.value;
//    let reg = /_/;
//    let pos = str.search(reg);
//    inp.setSelectionRange(pos,pos);
}

/*---------------------------------------------------------------------------*/

function inputPhoneKeyDown(
        event
){
    if (event.keyCode != 9){
        
        if (event.keyCode == 46){
            event.preventDefault();
            event.target.value = "+7 (___) ___-__-__";
            let str = event.target.value;
            let reg = /_/;
            let pos = str.search(reg);
            
            event.target.setSelectionRange(pos,pos);
            return;
        }
        
        let str = event.target.value;
        let pos = str.length;
        if (event.keyCode == 8){
            event.preventDefault();
            let ar = str.split("").reverse();
            let flag = true;
            let reg = /[0-9]/;
            for(let index in ar){
                if ((ar[index].match(reg)) && (index < 14) && (flag)){
                    ar[index] = "_";
                    flag = false;
                }
            }
            str = ar.reverse().join("");
            reg = /_/;
            pos = str.search(reg);
            event.target.value = str;
            event.target.setSelectionRange(pos,pos);
        }
        
    }
}

/*---------------------------------------------------------------------------*/

function inputPhoneInput(
        event
){
    let pattern = "+7(___) ___-__-__";
//    if (((event.keyCode >= 48) && (event.keyCode <= 57))||((event.keyCode >= 96) && (event.keyCode <= 105))){
//        let reg = /_/;
//
//        str = str.replace(reg,event.key);
//    }
    let buf = event.target.value;
    let result = [];
    let value = [];
    for(let i = 2; i < buf.length; i++){
        let keyCode = buf.charCodeAt(i);
        if ((keyCode >= 48) && (keyCode <= 57)){
            value.push(buf[i]);
        }
        
    }
    let index = 0;
    let pos = 2;
    for(let i = 0; i < pattern.length; i++){
        if (pattern[i] != "_"){
            result[i] = pattern[i];
        }
        else{
            if (index < value.length){
                result[i] = value[index];
                index++;
            }
            else{
                result[i] = pattern[i];
            }
        }
    }
    
    event.target.value = result.join("");
    reg = /_/;
    pos = event.target.value.search(reg);
    event.target.setSelectionRange(pos,pos);
    
}

/*---------------------------------------------------------------------------*/

function inputFileChange(
        file
){
    let container = file.closest(".inputFileContainer");
    let text = container.querySelector(".textNormal");
    if (file.files[0].name){
        text.textContent = "Файл выбран";
    }
    else{
        text.textContent = "Файл не выбран";
    }
}

/*---------------------------------------------------------------------------*/

function setValue(
        el,
        value
){

    let getDate = function(timeStamp){
        let date = new Date(Number(timeStamp) * 1000);
        let year = date.getFullYear();
        let month = String((date.getMonth() + 1));
        let day = String(date.getDate());
        if (month.length < 2){
            month = "0" + month;
        }
        if (day.length < 2){
            day = "0" + day;
        }
        return `${year}-${month}-${day}`;
    };


    if (!el){
        return "";
    }
    if(el.classList.contains("inputText")){
        el.value = value;
    }
    else if(el.classList.contains("inputDate")){
        if (value){
            el.value = getDate(value);
        }
        else{
            el.value = "";
        }
    }
    else if (el.classList.contains("filePath")){
        el.textContent = value;
    }
    else if (el.classList.contains("textNormal")){
        el.textContent = value;
    }
    else if (el.classList.contains("var")){
        el.textContent = value;
    }
    else if (el.classList.contains("inputCheckbox")){
        if (el.classList.contains("inputCheckbox_checked")){
            return true;
        }
        else{
            return false;
        }
        if (value){
            el.classList.add("inputCheckbox_checked");
        }
        else{
            el.classList.remove("inputCheckbox_checked");
        }
    }
    else if (el.classList.contains("inputArea")){
        el.value = value;
    }
}

/*---------------------------------------------------------------------------*/

function getValue(
        el
){
    if (!el){
        return "";
    }
    if (el.classList.contains("inputPhone")){
        let reg = /[0-9]/;
        let result = "";
        let buf = el.value;
        for(let i = 0; i < buf.length; i++){
            let ch = buf[i];
            if (reg.test(ch)){
                result += ch;
            }
        }
        return result;
    }
    else if(el.classList.contains("inputText")){
        return el.value;
    }
    else if (el.classList.contains("inputSelect")){
        if (el.selectedIndex == -1){
            return "";
        }
        else{
            return el.options[el.selectedIndex].value;
        }
    }
    else if (el.classList.contains("inputRadio")){
        let buf = el.querySelector(".inputCheckbox_checked");
        if (buf){
            return buf.getAttribute("data_value");
        }
        else{
            return "";
        }
    }
    else if (el.classList.contains("filePath")){
        return el.textContent.trim();
    }
    else if (el.classList.contains("textNormal")){
        return el.textContent.trim();
    }
    else if (el.classList.contains("var")){
        return el.textContent.trim();
    }
    else if (el.classList.contains("inputDate")){
        if (el.value == ""){
            return "";
        }
        let date = new Date(el.value);
        return Math.floor(date.getTime()/1000);
    }
    else if (el.classList.contains("inputDate")){
        if (el.value == ""){
            return "";
        }
        let date = new Date(el.value);
        return Math.floor(date.getTime()/1000 - 60*60*6);
    }
    else if (el.classList.contains("inputCheckbox")){
        if (el.classList.contains("inputCheckbox_checked")){
            return true;
        }
        else{
            return false;
        }
    }
    else if (el.classList.contains("inputArea")){
        return el.value.trim();
    }
}

/*---------------------------------------------------------------------------*/

function getShow(
        el
){
    if (!el){
        return null;
    }
    if (el.classList.contains("inputPhone")){
        let reg = /[0-9]/;
        let result = "";
        let buf = el.value;
        for(let i = 0; i < buf.length; i++){
            let ch = buf[i];
            if (reg.test(ch)){
                result += ch;
            }
        }
        return result;
    }
    else if(el.classList.contains("inputText")){
        return el.value.trim();
    }
    else if (el.classList.contains("inputSelect")){
        if (el.selectedIndex == -1){
            return "";
        }
        else{
            return el.options[el.selectedIndex].textContent.trim();
        }
    }
    
    else if (el.classList.contains("inputDate")){
        return el.value.trim();
    }
    else if (el.classList.contains("inputArea")){
        return el.value.trim();
    }
    else if (el.classList.contains("inputRadio")){
        let buf = el.querySelector(".inputCheckbox_checked");
        if (buf){
            return buf.textContent.trim();
        }
        else{
            return "";
        }
    }
    
}


/*---------------------------------------------------------------------------*/

function swapDisable(
        el
){
    let buf = document.createElement("div");
    if (!el){
        return null;
    }
    if(el.classList.contains("noSwapDisable")){
        return null;
    }
    
    /*---------------------------------------------------------------------------*/
    
    if (el.classList.contains("inputTemplate")){
        el.classList.toggle("hidden");
        return;
    }
    if (el.classList.contains("hiddenInput")){
        let container = el.closest(".hiddenInputContainer");
        container.classList.toggle("hidden");
        return;
    }
    if (el.classList.contains("inputPhone")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
            el.removeAttribute("readonly");
        }
        else{
            el.classList.add("readonly");
            el.setAttribute("readonly","readonly");
        }
    }
    else if(el.classList.contains("inputText")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
            el.removeAttribute("readonly");
        }
        else{
            el.classList.add("readonly");
            el.setAttribute("readonly","readonly");
        }
    }
    else if (el.classList.contains("inputSelect")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
            el.removeAttribute("disabled");
        }
        else{
            el.classList.add("readonly");
            el.setAttribute("disabled","disabled");
        }
    }
    else if (el.classList.contains("inputRadio")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
        }
        else{
            el.classList.add("readonly");
        }
    }
    
   
    else if (el.classList.contains("inputDate")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
            el.removeAttribute("readonly");
        }
        else{
            el.classList.add("readonly");
            el.setAttribute("readonly","readonly");
        }
    }
    else if (el.classList.contains("inputCheckbox")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
        }
        else{
            el.classList.add("readonly");
        }
    }
    else if (el.classList.contains("inputArea")){
        if (el.classList.contains("readonly")){
            el.classList.remove("readonly");
            el.removeAttribute("readonly");
        }
        else{
            el.classList.add("readonly");
            el.setAttribute("readonly","readonly");
        }
    }
    
    if (el.classList.contains("nullableInput")){
        let container = el.closest(".nullableDiv");
        let readonly;
        let value_null;
        if (el.classList.contains("readonly")){
            readonly = true;
        } 
        else{
            readonly = false;
        }
        if (getValue(el).trim() == el.getAttribute("data_null")){
            value_null = true;
        }
        else{
            value_null = false;
        }
        
        if (container){
            if (readonly && value_null){
                container.classList.add("hidden");
            }
            else{
                container.classList.remove("hidden");
            }
        }
    }
    if (el.classList.contains("stretch")){
        inputStretch(el);
    }
}

/*---------------------------------------------------------------------------*/

function inputStretch(
        el
){
    let fontSize = window.getComputedStyle(el).fontSize.replace(/[^0-9.]/g,"");
    if (!el){
        return null;
    }
    let flag = false;
    let tail = "";
    let tailNeg = "";
    let buf = document.createElement("div");
    if (el.classList.contains("inputPhone")){
        flag = true;
        tail = 10;
        tailNeg = 0;
    }
    else if(el.classList.contains("inputText")){
        flag = true;
        tail = Number(fontSize) - 2;
        tailNeg = 8;
    }
    else if (el.classList.contains("inputSelect")){
        flag = true;
        tail = Number(fontSize) + 5;
        tailNeg = 2;
    }
    
    else if (el.classList.contains("inputDate")){
        flag = true;
        tail = 10;
        tailNeg = 0;
    }
    else if (el.classList.contains("inputArea")){
        flag = true;
        tail = 20;
        tailNeg = 0;
    }
    
    if (flag){
        let value = getShow(el);
        let count = value.split("/").length - 1;
        buf.style.fontSize = window.getComputedStyle(el).fontSize;
        buf.textContent = value;
        buf.style.fontWeight = window.getComputedStyle(el).fontWeight;
        buf.style.paddingRight = "8px";
        buf.style.width = "fit-content";
        let main = document.getElementById("mainContainer");
        main.appendChild(buf);
        let width = window.getComputedStyle(buf).width.replace(/[^0-9.]/g,"");
        let minWidth = el.style.minWidth.replace(/[^0-9.]/g,"");
        let maxWidth = el.style.maxWidth.replace(/[^0-9.]/g,"");
        if ((minWidth) && (Number(width) < Number(minWidth))){
            width = minWidth;
        }
        if ((maxWidth) && (Number(width) > Number(maxWidth))){
            width = maxWidth;
        }
        if (el.classList.contains("readonly")){
            el.style.width = String(Number(width) - (Number(tailNeg) - (count * 4))) + "px";
        }
        else{
            el.style.width = String(Number(width) + Number(tail)) + "px";
        }
        main.removeChild(buf);
    }
}

/*---------------------------------------------------------------------------*/

function areaStretchAutosize(
        el
){
    inputStretch(el);
    inputAreaAutoSize(el);
}

/*---------------------------------------------------------------------------*/

function inputStretchAll(
        container
){
    let list = Array.from(container.querySelectorAll(".inp.stretch"));
    for(let el of list){
        if (el.classList.contains("inputArea")){
            inputAreaAutoSize(el);
        }
        inputStretch(el);
    } 
}

function checkAllNull(
        container
){
    let nullList = Array.from(container.querySelectorAll(".nullableInput"));
    for(let el of nullList){
        let container = el.closest(".nullableDiv");
        let readonly;
        let value_null;
        if (el.classList.contains("readonly")){
            readonly = true;
        } 
        else{
            readonly = false;
        }
        if (getValue(el).trim() == el.getAttribute("data_null")){
            value_null = true;
        }
        else{
            value_null = false;
        }
        
        if (container){
            if (readonly && value_null){
                container.classList.add("hidden");
            }
            else{
                container.classList.remove("hidden");
            }
        }
    }
}

/*---------------------------------------------------------------------------*/

function swapDisableAll(
        container
){
    let list = Array.from(container.querySelectorAll(".inp"));
    let buf = getVarFromContainer(container,"_banList_");
    let role = getCookie("orkkrole");
    let banList = {};
    if (buf){
        banList = JSON.parse(buf);
    }
//    console.log(banList);
    for(let el of list){
        let id = el.id;
        if (banList[id]){
            if (banList[id].includes(role)){
                break;
            }
        }
        swapDisable(el);
    }
}

/*---------------------------------------------------------------------------*/

function setNullValue(
        el
){
    if (!el){
        return null;
    }
    if (el.classList.contains("inputPhone")){
        el.value =  "+7 (___) ___-__-__";
    }
    else if(el.classList.contains("inputText")){
        el.value = "";
    }
    else if (el.classList.contains("inputSelect")){
        el.selectedIndex = -1;
    }
    else if (el.classList.contains("inputRadio")){
        let buf = el.querySelector(".inputCheckbox_checked");
        if (buf){
            buf.classList.remove("inputCheckbox_checked");
        }
    }
    else if (el.classList.contains("filePath")){
        el.textContent = "";
    }
    else if (el.classList.contains("textNormal")){
        el.textContent = "";
    }
    else if (el.classList.contains("var")){
        el.textContent = "";
    }
    else if(el.classList.contains("inputDate")){
        el.value = "";
    }
}

/*---------------------------------------------------------------------------*/

function newPage(
        content,
//        title = "",
        close = true,
        onload = null
){
    let tab = document.createElement("div");
    let page = document.createElement("div");
    page.innerHTML = content;
//    if (!title){
//        let el = page.querySelector("#title");
//        if (el){
//            title = el.textContent.trim();
//        }
//    }
    let tabTitle = "###";
    {
        let el = page.querySelector("#tabTitle");
        if (el){
            tabTitle = el.textContent.trim();
        }
    }
    let container = document.getElementById("mainContainer");
    let tabPanel = document.getElementById("tabPanel");
    let id = String(Math.floor(Math.random() * 8000) + 1000);
   
    let closeButton = document.createElement("div");
    closeButton.style.height = "30px";
    closeButton.setAttribute("onclick","closeTabClick(this)");
    closeButton.innerHTML = '<img src="/_modules/orkkNew/img/button_close.png" alt="alt" style="height: 17px; width: auto">';
    closeButton.classList.add("divButton");
    page.innerHTML = content;
    page.classList.add("page");
    page.setAttribute("id","page_" + id);
    tab.textContent = tabTitle;
    tab.classList.add("tab");
    tab.setAttribute("onclick","openTab(this)");
    tab.setAttribute("id","tab_" + id);
    let tabContainer = document.createElement("div");
    tabContainer.style.display = "flex";
    tabContainer.appendChild(tab);
    tabContainer.style.marginRight = "15px";
    tabContainer.classList.add("tabContainer");
    if (close){
        tabContainer.appendChild(closeButton);
    }
    container.appendChild(page);
    tabPanel.appendChild(tabContainer);
    openTab(tab);
    inputStretchAll(page);
    checkAllNull(page);
    allInputAreaAutosize(page);
    if (onload){
        onload(page);
    }
}

/*---------------------------------------------------------------------------*/

function reloadPage(
        el,
        content,
        func = null
){
    let page = el.closest(".page");
    if (page){
        page.innerHTML = content;
        inputStretchAll(page);
        if(func){
            func(page);
        }
    }
}

/*---------------------------------------------------------------------------*/

function closeTab(
        button
){
    let page = button.closest(".page");
    let id = page.getAttribute("id").split("_")[1];
    let tab = document.getElementById("tab_" + id);
    let tabContainer = tab.closest(".tabContainer");
    let newTab = tabContainer.previousSibling.querySelector(".tab");
    document.getElementById("mainContainer").removeChild(page);
    document.getElementById("tabPanel").removeChild(tabContainer);
    openTab(newTab);
}

/*---------------------------------------------------------------------------*/

function closeTabClick(
        button
){
    let container = button.closest(".tabContainer");
    let tab = container.querySelector(".tab");
    let id = tab.getAttribute("id").split("_")[1];
    let page = document.getElementById("page_" + id);
    let newTab = container.previousSibling.querySelector(".tab");
    document.getElementById("mainContainer").removeChild(page);
    document.getElementById("tabPanel").removeChild(container);
    openTab(newTab);
}

/*---------------------------------------------------------------------------*/

function openTab(
        button
){
    let container = button.closest(".tabContainer");
    
    let tabList = Array.from(document.getElementsByClassName("tabContainer"));
    let pageList = Array.from(document.getElementsByClassName("page"));
    for(let el of pageList){
        el.classList.add("hidden");
    }
    for(let tabCont of tabList){
        let tab = tabCont.querySelector(".tab");
        let closeButton = tabCont.querySelector(".divButton");
        tab.classList.remove("tab_selected");
        if (closeButton){
            closeButton.classList.add("hidden");
        }
    }
    button.classList.add("tab_selected");
    let closeButton = container.querySelector(".divButton");
    if (closeButton){
        closeButton.classList.remove("hidden");
    }
    let id = button.getAttribute("id");
    id = "page_" + id.split("_")[1];
    document.getElementById(id).classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function selectFile(
        button,
        background = false
){
    
    let container = button.closest(".inputFileContainer");
    let text = container.querySelector(".textNormal");
    let file = container.querySelector(".filePath");
    let fileName = container.querySelector(".fileName");
    
    if (text.textContent.trim() == "Файл не выбран"){
        window["_GetFile"] = function(id,name){
            file.textContent = id;
            fileName.textContent = name;
            text.textContent = "Файл выбран";
            let img = container.querySelector("img");
            if (background){
                img.setAttribute("src","/_modules/orkkNew/img/close_file_background.png"); 
            }
            else{
                img.setAttribute("src","/_modules/orkkNew/img/close_file.png");
            }
        };
        showTreeExplorer();
    }
    else{
        file.textContent = "";
        text.textContent = "Файл не выбран";
        let img = container.querySelector("img");
        if (background){
            img.setAttribute("src","/_modules/orkkNew/img/input_file_background.png"); 
        }
        else{
            img.setAttribute("src","/_modules/orkkNew/img/input_file.png");
        }
    }
}

/*---------------------------------------------------------------------------*/

function swapId(
        id_1,
        id_2,
        page
){
    page.querySelector("#" + id_1).setAttribute("id","__bufId");
    page.querySelector("#" + id_2).setAttribute("id",id_1);
    page.querySelector("#__bufId").setAttribute("id",id_2);
}

/*---------------------------------------------------------------------------*/

function getVarFromContainer(
        container,
        id
){
    return getValue(container.querySelector("#" + id));
}

/*---------------------------------------------------------------------------*/

function setVarToContainer(
        container,
        id,
        value
){
    setValue(container.querySelector("#" + id),value);
}

/*---------------------------------------------------------------------------*/

function selectItemFromTable(
        row
){
    let msgBack = row.closest(".msgBack");
    if (_globalSelect != null){
        _globalSelect(row);
        _globalSelect = null;
    }
    fUnMsgBlock(msgBack.getAttribute("id"));
}


/*---------------------------------------------------------------------------*/

function getFile(
        fullPath
){
    let buf = fullPath.split("/");
    
    let id = buf[buf.length -1];
    let path = buf.slice(0,buf.length-1).join("/");
    showTreeExplorer(path);

}

/*---------------------------------------------------------------------------*/

function inputAreaAutoSize(
        el
){
//    if(area.scrollHeight > 0){
//        area.style.height = (area.scrollHeight) + "px";
//    }
    let buf = document.createElement("textarea");
    let value = getShow(el);
    buf.style.fontSize = window.getComputedStyle(el).fontSize;
    buf.style.width = window.getComputedStyle(el).width;
    buf.style.height = "20px";
    buf.style.overflow = "hidden";
    buf.value = value;
    buf.style.paddingRight = "8px";
    buf.style.height = "fit-content";
    let main = document.getElementById("mainContainer");
    main.appendChild(buf);
//    let height = window.getComputedStyle(buf).height.replace(/[^0-9.]/g,"");
    let height = buf.scrollHeight;
//    el.style.height = String(Number(height) + 10) + "px";
    el.style.height = (buf.scrollHeight) + "px";
    el.style.overflow = "hidden";
    main.removeChild(buf);
}

/*---------------------------------------------------------------------------*/

function allInputAreaAutosize(
        container
){
    let areaList = Array.from(container.querySelectorAll(".inputArea"));
    for(let el of areaList){
        if (el.classList.contains("inputAreaAutosize")){
            inputAreaAutoSize(el);
        }
    }
}

/*---------------------------------------------------------------------------*/

function hiddenTextTilteClick(
        button,
        all = false,
        selector = ".page"
){
    if (!all){
        let container = button.closest(".hiddenTextContainer");
        container.querySelector("#hiddenText").classList.toggle("hidden");
    }
    else{
        let container = button.closest(selector);
        let hiddenList = Array.from(container.querySelectorAll(".hiddenTextContainer"));
        let hidden;
        if (button.closest(".hiddenTextContainer").querySelector("#hiddenText").classList.contains("hidden")){
            hidden = false;
        }
        else{
            hidden = true;
        }
        for(let el of hiddenList){
            let text = el.querySelector("#hiddenText");
            if (hidden){
                text.classList.add("hidden");
            }
            else{
                text.classList.remove("hidden");
            }
        }
    }
}

/*---------------------------------------------------------------------------*/

function addSelectToInput(
        container,
        select,
        inputId
){
    container.querySelector("#"+inputId).value = select.options[select.selectedIndex].value.trim();
}

/*---------------------------------------------------------------------------*/

function hiddenPanelToggle(
        button
){
    let page = button.closest(".hiddenPanel");
    page.querySelector("#hiddenPanelContent").classList.toggle("hidden");
    
}

/*---------------------------------------------------------------------------*/

function orkkDoCall(
        el,
        containerSel,
        phoneId = "phoneNumber"
){
    let container = el.closest(containerSel);
    let number = getVarFromContainer(container,phoneId);
    number = number.replace(/7/,"8");
    orkkDialNum(number);
}

/*---------------------------------------------------------------------------*/

function orkkDialNum($num){
    var xhr=new XMLHttpRequest();
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200){
            var ret=xhr.responseText;
            //alert(ret);
        }
    }

    xhr.open("POST","/_utils/dialer/dial.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.send('int=&ext='+encodeURIComponent($num)+'&via=routers');
}

/*---------------------------------------------------------------------------*/

function tableCellEditClick(
        button,
        containerSelector = ".editBlock",
        acceptSelector = ".acceptBlock"
){
    let container = button.closest(containerSelector);
    let list = Array.from(container.querySelectorAll(".inp"));
    for(let el of list){
        el.oldValue = getValue(el);
        swapDisable(el);
    }
    button.classList.add("hidden");
    container.querySelector(acceptSelector).classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function tableCellEditClose(
        button,
        accept,
        containerSelector = ".editBlock",
        acceptSelector = ".acceptBlock",
        editButtonSelector = ".editButton"
){
    let container = button.closest(containerSelector);
    let list = Array.from(container.querySelectorAll(".inp"));
    for(let el of list){
        if (!accept){
            setValue(el,el.oldValue);
        }
        let val = getValue(el);
        let placeholder = el.getAttribute("placeholder");
        if (!val && placeholder){
            setValue(el,placeholder);
        }
        swapDisable(el);
    }
    container.querySelector(acceptSelector).classList.add("hidden");
    container.querySelector(editButtonSelector).classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function enterPress(
        event,
        func = null
){
    if (event.key == "Enter"){
        func(event.target);
    }
}

/*---------------------------------------------------------------------------*/

function acceptMsg(
        func,
        ...args
){
    let html = `
<div id="" class=" divRow" style="justify-content: center;margin-top: 15px;">
    <div class="divButton " style="margin-right: 10px;" onclick="fUnMsgBlock('acceptMsg')">
        <img src="/_modules/orkkNew/img/button_close_square.png" alt="alt" style="height: 100%; width: auto">
    </div>

    <div id="acceptMsgAcceptButton" class="divButton " style="">
        <img src="/_modules/orkkNew/img/button_accept_square.png" alt="alt" style="height: 100%; width: auto">
    </div>
</div>`;
    fMsgBlock("Вы уверены?",html,95,230,"acceptMsg");
    document.getElementById("acceptMsgAcceptButton").addEventListener("click",function(){
        func(...args);
        fUnMsgBlock("acceptMsg");
    });
}

/*---------------------------------------------------------------------------*/

function errorMessage(
        message
){
    fMsgBlock("","<h2 style='text-align: center; color: var(--modColor_darkest);'>" + message + "</h2>",130,500);
}

/*---------------------------------------------------------------------------*/

function checkAccess(
        action,
        func,
        ...params
){
    let role = getCookie("orkkrole");
    let banList = _globalSettings["actionBanList"];
    if (banList[action]){
        if (banList[action].includes(role)){
            errorMessage("Не достаточно прав");
            return;
        }
    }
    func(...params);
}

/*---------------------------------------------------------------------------*/

function commentTemplates(
        type,
        arg
){
    if (arg["amount1"] == undefined){
        arg["amount1"] = "";
    }
    if (arg["amount2"] == undefined){
        arg["amount2"] = "";
    }
    let result = {
        
        /*---------------------------------------------------------------------------*/
        
        "Дополнительная точка" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с подключением дополнительной точки
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Статический IP адрес: ${arg["staticIp"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
С ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Расширение ЛВС" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с расширением ЛВС
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный тариф без изменений`,
        
        /*---------------------------------------------------------------------------*/
        
        "Смена реквизитов" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи со сменой реквизитов
по адресу ${arg["address"]}
Общий ежемесячный тариф без изменений
Дата активации ${arg["activateDate"]}`,
        
        
        /*---------------------------------------------------------------------------*/
        
        "Смена местонахождения" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи со сменой местонахождения
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный тариф без изменений
Дата активации ${arg["activateDate"]}
Клиент переехал с ${arg["prevAddress"]}  
на ${arg["nextAddress"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Увеличение ширины канала" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с увеличением ширины канала
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
С ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Уменьшение ширины канала" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с уменьшением ширины канала
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
С ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Аренда серверной стойки" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с арендой серверной стойки
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
С ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Обслуживание ЛВС" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с обслуживанием ЛВС
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
С ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Подключение к сети" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с услугой подключения к сети
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
Дата активации ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Аренда статического IP адреса" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с арендой статического IP адреса
по адресу ${arg["address"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
Дата активации ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
        "Аренда канала" : 
`${arg["city"]}, ${arg["manager"]}, ${arg["payType"]}
${arg["docName"]} от ${arg["contractDate"]}
В связи с арендой канала
по адресу ${arg["address"]}
Подключение: ${arg["connectSum"]}
Оборудование: ${arg["hardware"]}
Общий ежемесячный платеж изменится с
${arg["amount1"]} на ${arg["amount2"]}
Дата активации ${arg["activateDate"]}`,
        
        /*---------------------------------------------------------------------------*/
        
    };
    
    if (result[type]){
        return result[type];
    }
}

/*---------------------------------------------------------------------------*/

var _globalSettings = {};
var _globalSelect = null;
var _globalVars = {};
var _commentTemplates = {};
var _glMsgOnClose = null;
/*---------------------------------------------------------------------------*/

function _init(){
    getXhr({
        action: "getSettings"
    }).then((settings) => {
        _globalSettings = JSON.parse(settings);
        let role = _globalSettings["role"];
        setCookie("orkkrole",role);
    });
}

_init();



/*---------------------------------------------------------------------------*/








