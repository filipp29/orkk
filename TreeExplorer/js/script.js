
function getXhrTree(
        params
){  
    return new Promise(function(callback){
        let xhr = new XMLHttpRequest();
        xhr.onload = function(){
            if (xhr.status == 200){
                orkScreenUnlock();
                callback(xhr.responseText);
            }
        };
        if (params["method"] == "GET"){
            xhr.open(params["method"],params["url"] + "?" + params["body"]);
            xhr.send();
            orkScreenLock();
        }
        else if (params["method"] == "POST"){
            xhr.open("POST",params["url"]);
            xhr.send(params["body"]);
            orkScreenLock();
        }
    });
}



/*---------------------------------------------------------------------------*/

function addJsCss(
        js,
        css,
        id

){
    let link = document.createElement("link");
    let rnd = Math.random();
    link.setAttribute("rel","stylesheet");
    link.setAttribute("id",id+"_link");
    link.setAttribute("href",css+"?rnd="+rnd);
    document.getElementsByTagName("head")[0].appendChild(link);
    let script = document.createElement("script");
    script.setAttribute("type","text/javascript");
    script.setAttribute("src",js+"?rnd="+rnd);
    script.setAttribute("id",id+"_script");
    document.getElementsByTagName("head")[0].appendChild(script);
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


function openFolder(
        id
){
    let path = document.getElementById("explorerPath").getAttribute("data_path") ? (document.getElementById("explorerPath").getAttribute("data_path")+"/") : "";
    fUnMsgBlock("treeExplorer");
    showTreeExplorer(path + id);
    
}

/*---------------------------------------------------------------------------*/

function back(){
    let path = document.getElementById("explorerPath").getAttribute("data_path");
    if (path != ""){
        let buf = path.split("/");
        let result = [];
        for(let i = 0 ; i < buf.length - 1; i ++){
            result.push(buf[i]);
        }
//        console.log(result.join("/"));
        fUnMsgBlock("treeExplorer");
        showTreeExplorer(result.join("/"));
    }
}

/*---------------------------------------------------------------------------*/

function createFolderForm(){
    let path = document.getElementById("explorerPath").getAttribute("data_path");
//    window.location = "/_modules/warehouse/php/components/TreeExplorer/controller.php?action=getCreateFolderForm&path="+ path;
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "GET",
        body : "action=getCreateFolderForm&path="+ path
        
    }).then((html) => {
        fMsgBlock("Новая папка",html, 200,500, "createFolder");
    });
}

/*---------------------------------------------------------------------------*/

function createFolder(
        button
){
    let form = button.closest(".createFolderForm");
    let input = form.querySelector("#newFolderName");
    let path = input.getAttribute("data_path");
    let re = /\+/gi;
    let name = input.value.trim().replace(re,'%2B');;
    if (name == ""){
        fMsgBlock("Ошибка","<h1 style='text-align: center'>Введите имя</h1>",250,600,"ERROR");
        return;
    }
    let body = "action=createFolder" + 
            "&path=" + path + 
            "&name=" + name;
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "GET",
        body : body
    }).then((html) => {
        w_name = "treeExplorer";
        fUnMsgBlock("createFolder");
        fUnMsgBlock("treeExplorer");
        fMsgBlock("Обозреватель",html, 700,1000, w_name);
        let js = document.getElementById(w_name + "_js").textContent;
        let css = document.getElementById(w_name + "_css").textContent;
        addJsCss(js,css,w_name);
    });
}

/*---------------------------------------------------------------------------*/

function selectTreeElement(
        elem
){
    let list = Array.from(document.getElementsByClassName("selectedTreeElement"));
    list.forEach(el => {
        el.classList.remove("selectedTreeElement");
    });
    elem.closest(".contentBlock").classList.add("selectedTreeElement");
}

/*---------------------------------------------------------------------------*/

function fileOpen(
        file
){
    document.forms[0].name.value = file.files[0].name;
}

/*---------------------------------------------------------------------------*/

function createFile(
        form
){
    event.preventDefault();
    let re = /\+/gi;
    form.elements["name"].value = form.elements["name"].value.trim().replace(re,'%2B');
    if (!form.elements["file"].files[0]){
        fMsgBlock("Ошибка","<h1 style='text-align: center'>Выберите файл</h1>",250,600,"ERROR");
        return;
    }
    if (!form.elements["name"].value){
        fMsgBlock("Ошибка","<h1 style='text-align: center'>Введите имя</h1>",250,600,"ERROR");
        return;
    }
    
    let body = new FormData(document.forms[0]);
    for(var pair of body.entries()) {
         console.log(pair[0]+ ', '+ pair[1]); 
    }
//    console.log("/_modules/warehouse/php/components/TreeExplorer/controller.php?"+body);
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "POST",
        body : body
    }).then((html) => {
        w_name = "treeExplorer";
        fUnMsgBlock("createFile");
        fUnMsgBlock("treeExplorer");
        fMsgBlock("Обозреватель",html, 700,1000, w_name);
//        let js = document.getElementById(w_name + "_js").textContent;
//        let css = document.getElementById(w_name + "_css").textContent;
//        addJsCss(js,css,w_name);
    });
    
    
    
    
}

/*---------------------------------------------------------------------------*/

function createFileForm(){
    let path = document.getElementById("explorerPath").getAttribute("data_path");
//    window.location = "/_modules/warehouse/php/components/TreeExplorer/controller.php?action=getCreateFolderForm&path="+ path;
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "GET",
        body : "action=getCreateFileForm&path="+ path
        
    }).then((html) => {
        w_name = "createFile";
        fMsgBlock("Новый файл",html, 250,500,w_name );
//        let js = "";
//        let css = "";
//        addJsCss(js,css,w_name);
    });
}

/*---------------------------------------------------------------------------*/

function deleteTreeElement(){
    let row = document.getElementsByClassName("selectedTreeElement")[0];
    let elem = row.getElementsByClassName("treeElementBlock")[0];
    let id = elem.getAttribute("data_id");
    let type = row.getAttribute("data_type");
    let path = document.getElementById("explorerPath").getAttribute("data_path");
    let body = "action=delete&path="+ path + 
            "&type=" + type +
            "&id=" + id;
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "GET",
        body : body
    }).then((html) => {
        w_name = "treeExplorer";
        fUnMsgBlock("treeExplorer");
        fMsgBlock("Обозреватель",html, 700,1000, w_name);
        let js = document.getElementById(w_name + "_js").textContent;
        let css = document.getElementById(w_name + "_css").textContent;
        addJsCss(js,css,w_name);
    });
}

/*---------------------------------------------------------------------------*/

function openFile(){
    let row = document.getElementsByClassName("selectedTreeElement")[0];
    let type = row.getAttribute("data_type");
    if (type != "file"){
        return;
    }
    let elem = row.getElementsByClassName("treeElementBlock")[0];
    let id = elem.getAttribute("data_id");
    let path = document.getElementById("explorerPath").getAttribute("data_path");
    let form = document.createElement("form");
    form.action = "/_modules/orkkNew/TreeExplorer/controller.php";
    form.method = "POST";
    form.innerHTML = '<input type="text" name="action" value="getFile">';
    form.innerHTML += '<input type=text name="id" value="' + id + '">';
    form.innerHTML += '<input type=text name="path" value="' + path + '">';
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
//{
//    let target = document.getElementById("createFileLink");
//    let dependId = target.getAttribute("data_depend_dir");
//    let config = {
//        attributes: true,
//        childList: true,
//        subtree: false
//    };
//    let callback = function(mutationList, observer){
//        document.getElementById("createFileName").value = target.textContent;
//    };
//    let observer = new MutationObserver(callback);
//
//    observer.observe(target,config);
//}

/*---------------------------------------------------------------------------*/

function submitFile(
        id,
        name = "UNKNOWN"
){
    let path = document.querySelector("#explorerPath").textContent.trim();
    if (path.trim().length != 1){
        path += "/";
    }
    if (typeof window["_GetFile"] == 'function' ){
        window["_GetFile"](id,path + name);
        delete(window["_GetFile"]);
    }
    fUnMsgBlock("treeExplorer");
}

/*---------------------------------------------------------------------------*/

function showTreeExplorer(
        path = ""
){
    let name = "treeExplorer";
    let body = "action=get&path=" + path;
    getXhrTree({
        url : "/_modules/orkkNew/TreeExplorer/controller.php",
        method : "GET",
        body : body
    }).then(function(html){
        fMsgBlock("Обозреватель",html, 700,1000, name);
        let js = document.getElementById(name + "_js").textContent;
        let css = document.getElementById(name + "_css").textContent;
        addJsCss(js,css,name);
    });
}