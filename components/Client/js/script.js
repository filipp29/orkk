function extraServiceFlagToggle(
        sel
){
    let document = sel.closest(".page");
    let val = sel.options[sel.selectedIndex].value;
    let list = Array.from(document.querySelectorAll(".extraService"));
    for(let el of list){
        if (val == 1){
            el.classList.remove("hidden");
        }
        else if(val == 0){
            el.classList.add("hidden");
            inputRadioToggle(el.querySelector(".inputRadio").children[0]);
        }
    }
}

/*---------------------------------------------------------------------------*/

function addContact(
        button
){
    let document = button.closest(".page");
    let bottom = document.querySelector("#contactsBottom");
    let temp = document.querySelector("#contactContainerTemplate").cloneNode(true);
    temp.removeAttribute("id");
    temp.classList.remove("hidden");
    temp.classList.add("contactContainer");
    bottom.parentElement.insertBefore(temp,bottom);
}

/*---------------------------------------------------------------------------*/

function removeContact(
        button
){
    let document = button.closest(".page");
    let container = button.closest(".contactContainer");
    container.parentElement.removeChild(container);
}

/*---------------------------------------------------------------------------*/

function createClientPage(){
    getXhr({
        action: "getClientCreatePage"
    }).then((html) => {
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function showSecondPage(
        id
){
    getXhr({
        action : "getSecondPage",
        id : id
    }).then((html) => {
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function showClientCard(
        id
){
    getXhr({
        action: "getClientCard",
        id: id
    }).then((html) => {
        newPage(html);
    });
}



/*---------------------------------------------------------------------------*/

function saveClient(
        button,
        contactFlag = false,
        onClose = null,
        getDoc = null,
        container = "page"
){
    
    let document = button.closest(`.${container}`);
    let paramKeys = _globalSettings["Client"]["paramKeys"];
    let params = {};
    for (let key in paramKeys){
        let el = document.querySelector("#" + key);
        if (el){
            params[key] = getValue(el);
        }
    }
//    let extraServiceKeys = _globalSettings["Main"]["extraService"];
//    let extraService = {};
//    for (let key in extraServiceKeys){
//        extraService[key] = getValue(document.querySelector("#" + key));
//    }
    
    if (contactFlag){
        let contacts = [];
        let contactList = Array.from(document.querySelectorAll(".contactContainer"));
        for (let el of contactList){
            let keys = [
                "phone",
                "name",
                "role",
                "main",
                "lpr",
                "eavr"
            ];
            let buf = {};
            for(let key of keys){
                buf[key] = getValue(el.querySelector("#contact_" + key));
            }
            contacts.push(buf);
        }

        var data = {
            params: params,
            contacts: contacts,
        };
    }
    else{
        var data = {
            params: params,
        };
    }
    if (document.querySelector("#comment")){
        let comment = {
            author : getCookie("login"),
            type : "fixed",
            text : document.querySelector("#comment").value,
            filePath : params["filePath"],
            fileName : params["fileName"],
            timeStamp : Math.floor(Date.now()/1000)
        };
        
        data["comment"] = comment;
    }
    if (getDoc !== null){
        data["doc"] = getDoc(button);
    }
    let _g = function(id){
        return getVarFromContainer(document,id);
    };
    if (getVarFromContainer(document,"renewFlag") == "1"){
        data["renewData"] = {
            clientId : _g("oldId"),
            data :{
                date : _g("renew_date"),
                dnum : _g("dnum"),
                fileName : _g("renew_fileName"),
                filePath : _g("renew_filePath"),
                renewType : _g("renew_type"),
                comment : _g("renew_comment")
            }
        };
    }
    
    getXhr({
        action: "saveClient",
        data: JSON.stringify(data)
    }).then((html) => {
        if ((onClose !== null)){
            onClose(html);
            debug(html);
        }
    });
    
}

/*---------------------------------------------------------------------------*/

function saveClientWithFile(
        button,
        contactFlag = false,
        onClose = null,
        getDoc = null
){
    let page = button.closest(".page");
    if (page.querySelector("#filePath").textContent.trim() == ""){
        fMsgBlock("","<h2 style='text-align: center; color: var(--modColor_darkest);'>Выберите файл!</h2>",130,500);
        return;
    }
    
    saveClient(button,contactFlag,onClose,getDoc);
}

/*---------------------------------------------------------------------------*/

function saveClientWithoutComment(
        button,
        contactFlag = false,
        onClose = null,
        getDoc = null
){
    let page = button.closest(".page");
    let comment = page.querySelector("#comment");
    if (comment){
        comment.setAttribute("id","comment_old");
    }
    
    saveClient(button,contactFlag,onClose,getDoc);
}

/*---------------------------------------------------------------------------*/

function onCreateClient(
        button
){
    return function(html){
        closeTab(button);
        let clientId = html.trim();
        showClientCard(clientId);
    };
}

/*---------------------------------------------------------------------------*/

function onCloseTab(
        button
){
    return function(html){
//        newPage(html);
        closeTab(button);
    };
}

/*---------------------------------------------------------------------------*/

function createSecondPage(
        button,
        newPoint = false
){
    let page = button.closest(".page");
    let firstPage = page.querySelector(".createFirstPage");
    let secondPage = page.querySelector(".createSecondPage");
    secondPage.querySelector("#nameOld").value = firstPage.querySelector("#name").value;
    secondPage.classList.remove("hidden");
    firstPage.classList.add("hidden");
    if (!newPoint){
        swapId("name","nameOld",page);
        swapId("clientStatus","clientStatusOld",page);
        let paramKeys = _globalSettings["Client"]["paramKeys"];
        for (let key in paramKeys){
            let el = secondPage.querySelector("#" + key);
            if ((el) && (el.classList.contains("inputSelect"))){
                el.selectedIndex = 0;
            }
        }
    }
    checkClientType(page);
}

/*---------------------------------------------------------------------------*/

function cancelSecondPage(
        button,
        newPoint = false
){
    let page = button.closest(".page");
    let firstPage = page.querySelector(".createFirstPage");
    let secondPage = page.querySelector(".createSecondPage");
    secondPage.classList.add("hidden");
    firstPage.classList.remove("hidden");
    if (!newPoint){
        let paramKeys = _globalSettings["Client"]["paramKeys"];
        let params = {};
        for (let key in paramKeys){
            if (["clientStatus","name"].includes(key)){
                continue;
            }
            let el = secondPage.querySelector("#" + key);
            if (el){
                setNullValue(el);
            }
        }
        swapId("name","nameOld",page);
        swapId("clientStatus","clientStatusOld",page);
    }
    
}

/*---------------------------------------------------------------------------*/

function createThirdPage(
        button,
        newPoint = false
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
        return `${day}.${month}.${year}`;
    };
    let page = button.closest(".page");
    let firstPage = page.querySelector(".createFirstPage");
    let secondPage = page.querySelector(".createSecondPage");
    let thirdPage = page.querySelector(".createThirdPage");
    if (!getVarFromContainer(secondPage,"dnum")){
        fMsgBlock("Ошибка","<h2 style='width : 100%; text-align:center'>Введите номер договора</h2>",115,350,"errorMsg");
        return;
    }
    secondPage.classList.add("hidden");
    firstPage.classList.add("hidden");
    thirdPage.classList.remove("hidden");
    let city = page.querySelector("#city");
    let manager = page.querySelector("#manager");
    let payType = page.querySelector("#paytype");
    let connectSum = page.querySelector("#connectSum");
    let hardware = page.querySelector("#hardware");
    let staticIp = page.querySelector("#staticIp");
    let renewFlag = getVarFromContainer(page,"renewFlag");
    let oldDnum = getVarFromContainer(page,"oldDnum");
    let oldName = getVarFromContainer(page,"oldName");
    let oldClientType = getVarFromContainer(page,"oldClientType");
    let newClientType = getVarFromContainer(page,"clientType");
    let newName = getVarFromContainer(page,"name");
    let newDnum = getVarFromContainer(page,"dnum");
    let dnum = getVarFromContainer(page,"dnum");
    let clientType = getVarFromContainer(page,"clientType");
    let buf = getVarFromContainer(page,"renew_date");
    let date = new Date(Number(buf) * 1000);
    let year = date.getFullYear();
    let month = String((date.getMonth() + 1));
    let day = String(date.getDate());
    let contractDate = getDate(getVarFromContainer(page,"contractDate"));
    let amount = getVarFromContainer(page,"amount");
    let speed = getVarFromContainer(page,"speed");
    let gosDnum = getVarFromContainer(page,"gosDnum");
    let gosSum = getVarFromContainer(page,"gosSum");
    let serviceList = {};
    serviceList.internet_service = getVarFromContainer(page,"internet_service");
    serviceList.cou_service = getVarFromContainer(page,"cou_service");
    serviceList.esdi_service = getVarFromContainer(page,"esdi_service");
    serviceList.channel_service = getVarFromContainer(page,"channel_service");
    serviceList.lan_service = getVarFromContainer(page,"lan_service");
    if (month.length < 2){
        month = "0" + month;
    }
    if (day.length < 2){
        day = "0" + day;
    }
    let renewDate = `${day}.${month}.${year}`;
    let renewType = getVarFromContainer(page,"renew_type");
    city = city.options[city.selectedIndex].value;
    manager = manager.options[manager.selectedIndex].textContent.trim();
    if (payType.selectedIndex != -1){
        payType = payType.options[payType.selectedIndex].textContent.trim();
    }
    else{
        payType = "";
    }
    let additional = "";
    let tarif = "";
    if (newPoint){
        let additionalName = getVarFromContainer(page,"additionalName");
        let city = getVarFromContainer(page,"city");
        let buildingType = getVarFromContainer(page,"buildingType");
        let building = getVarFromContainer(page,"building");
        let streetType = getVarFromContainer(page,"streetType");
        let street = getVarFromContainer(page,"street");
        let flatType = getVarFromContainer(page,"flatType");
        let flat = getVarFromContainer(page,"flat");
        let address = city;
        if (street){
            address += `, ${streetType} ${street}`;
        }
        
        if (building){
            address += `, ${buildingType} ${building}`;
        }
        if (flat){
            address += `, ${flatType} ${flat}`;
        }
//        if(contractDate){
//            let buf = new Date(Number(contractDate) * 1000);
//            let day = String(buf.getDate());
//            if (day.length < 2){
//                day = "0" + day;
//            }
//            let month = String(buf.getMonth() + 1);
//            if (month.length < 2){
//                month = "0" + month;
//            }
//            let year = (buf.getFullYear());
//            contractDate = `${day}.${month}.${year}`;
//        }
        additional = `
${additionalName} от ${contractDate}
В связи с подключением дополнительной точки
по адресу ${address}`;
        tarif = `
Тариф на точке: ${amount} тг. ${speed} мбит/с`;
    }
    connectSum = connectSum.value.trim();
    hardware = hardware.value.trim();
    if (staticIp.selectedIndex != -1){
        staticIp = staticIp.options[staticIp.selectedIndex].textContent.trim();
    }
    else{
        staticIp = "";
    }
    let comment;
    if (clientType == "ГУ"){
        let buf = [];
        let keyList = [
            "internet_service",
            "cou_service",
            "esdi_service",
            "channel_service",
            "lan_service"
        ];
        let valueList = {
            internet_service : "интернет",
            cou_service : "ЦОУ",
            esdi_service : "ЕШДИ",
            channel_service : "аренда канала",
            lan_service : "обслуживание лвс"
        };
        for(let el of keyList){
            if (serviceList[el]){
                buf.push(valueList[el]);
            }
        }
        let serviceText = buf.join(", ");
        comment = city + ", " + manager + "\n" + `Договор о государственных закупках услуг № ${gosDnum} от ${contractDate}
по биллингу ${dnum}
Срок оказания: 
Годовая сумма с НДС: ${gosSum}
Предварительная ежемесячная сумма: ${amount}
Виды услуг: ${serviceText} 
Скорость: ${speed} мбит/с`;
    }
    else{
        comment = city + ", " + manager + ", " + payType + additional + "\n" + "Подключение: " + connectSum + "\nОборудование: " + hardware + "\nСтатический IP: " + staticIp + tarif ;
    }
    comment += (newPoint) ? "\nОбщий ежемесячный тариф изменится с на " : "";
    if (renewFlag){
        comment += "\nРанее № " + oldDnum;
        let renewTypeWord;
        if (renewType == "Точка"){
            renewTypeWord = "точки";
        }
        else{
            renewTypeWord = "договора";
        }
        let buf = city + ", " + manager + ", " + payType + "\nПереоформление " + renewTypeWord + "\nРанее " + oldClientType + " " + oldName + " № " + oldDnum + "\nна " + 
                newClientType + " " + newName + " № " + newDnum + "\nЕжемесячный тариф изменится\nс на \nс " + renewDate;
        page.querySelector("#renew_comment").value = buf;
        page.querySelector(".renewCommentContainer").classList.remove("hidden");
    }
    page.querySelector("#comment").value = comment;
    if ((!newPoint) && (page.querySelector("#fileNameOld"))){
        swapId("fileName","fileNameOld",page);
        swapId("filePath","filePathOld",page);
    }
}

/*---------------------------------------------------------------------------*/



/*---------------------------------------------------------------------------*/

function cancelCreateThird(
        button,
        newPoint = false
){
    let page = button.closest(".page");
    let firstPage = page.querySelector(".createFirstPage");
    let secondPage = page.querySelector(".createSecondPage");
    let thirdPage = page.querySelector(".createThirdPage");
    secondPage.classList.remove("hidden");
    firstPage.classList.add("hidden");
    thirdPage.classList.add("hidden");
    if (!newPoint){
        page.querySelector("#comment").value = "";
        let fileContainer = thirdPage.querySelector(".inputFileContainer");
//        if (page.querySelector("#fileName").textContent.trim() != "Файл не выбран"){
//            selectFile(fileContainer.querySelector(".divButton"));
//        }
        swapId("fileName","fileNameOld",page);
        swapId("filePath","filePathOld",page);
    }
}

/*---------------------------------------------------------------------------*/

function showNewPointForm(
        button,
){
    let msgBlock = button.closest(".fmsgBlock_data");
    let dnum = getValue(msgBlock.querySelector("#dnum"));
    let docId = getVarFromContainer(msgBlock,"docId");
    getXhr({
        action : "getNewPointForm",
        dnum : dnum,
        docId : docId
    }).then((html) => {
        fUnMsgBlock("newDocForm");
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function showNewSpecificationForm(
        button,
){
    let container = button.closest(".chronologyDocContainer");
    let dnum = getVarFromContainer(container,"chr_dnum");
    let docId = getVarFromContainer(container,"chr_docId");
    getXhr({
        action : "getNewPointForm",
        dnum : dnum,
        docId : docId
    }).then((html) => {
//        fUnMsgBlock("newDocForm");
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function openChronologyComment(
        button
){
    let commentBox = button.closest(".chronologyCommentBox");
    commentBox.querySelector(".chronologyComment").classList.toggle("hidden");
}

/*---------------------------------------------------------------------------*/

function modifyClient(
        button
){
    let container = button.closest(".editedContainer");
    swapDisableAll(container);
    container.querySelector(".acceptBlock").classList.remove("hidden");
    button.classList.add("hidden");
}



/*---------------------------------------------------------------------------*/

function modifyClientCancel(
        button
){
    let container = button.closest(".editedContainer");
    let clientId = getValue(container.querySelector("#id"));
    closeTab(button);
    showClientCard(clientId);
}

/*---------------------------------------------------------------------------*/

function modifyClientAccept(
        button
){
    let container = button.closest(".editedContainer");
    let clientId = getVarFromContainer(container,"id");
    let onClose = function(){
        closeTab(button);
        showClientCard(clientId);
    };
    saveClient(button,false,onClose,null,"editedContainer");
}

/*---------------------------------------------------------------------------*/

function modifyDoc(
        button
){
    let container = button.closest(".docContainer");
    swapDisableAll(container);
    let buttonBlock = container.querySelector(".buttonBlock");
    let buttonList = Array.from(buttonBlock.children);
    buttonList.forEach((el) => {
        el.classList.add("hidden");
    });
    container.querySelector(".acceptBlock").classList.remove("hidden");
    
}

/*---------------------------------------------------------------------------*/

function getAddress(
        container,
        prefix = ""
){
    if (prefix){
        prefix = prefix + "_";
    }
    let keys = [
        "street",
        "building",
        "flat"
    ];
    let result = getVarFromContainer(container,prefix + "city");
    for(let key of keys){
        let buf = getVarFromContainer(container,prefix + key);
        if (buf){
            let type = getVarFromContainer(container,prefix + key + "Type");
            result += " " + type + ": " + buf;
        }
    }
    return result;
}

/*---------------------------------------------------------------------------*/

function modifyDocComment(
        button
){
    let container = button.closest(".docContainer");
    let page = button.closest(".page");
    let prevAddress = getAddress(page);
    let nextAddress = getAddress(container,"param");
    let comment = container.querySelector("#comment_text");
    container.querySelector(".acceptBlock").classList.add("hidden");
    container.querySelector(".buttonBlock").classList.add("hidden");
    container.querySelector(".paramsContainer").classList.add("hidden");
    container.querySelector(".commentContainer").classList.remove("hidden");
    let type = getVarFromContainer(container,"doc_posType");
    let paramKeys = _globalSettings["Account"]["posTypeParamList"][type];
    let manager = page.querySelector("#manager");
    let payType = page.querySelector("#payType");
    let doc = {};
    for(let el of paramKeys){
        let param = getVarFromContainer(container,"param_" + el);
        doc[el] = param;
    }
    
    
    
    if (doc["contractDate"]){
        let buf = new Date(Number(doc["contractDate"]) * 1000);
        let day = String(buf.getDate());
        if (day.length < 2){
            day = "0" + day;
        }
        let month = String(buf.getMonth() + 1);
        if (month.length < 2){
            month = "0" + month;
        }
        let year = (buf.getFullYear());
        doc["contractDate"] = `${day}.${month}.${year}`;
    }
    
    if (doc["activateDate"]){
        let buf = new Date(Number(doc["activateDate"]) * 1000);
        let day = String(buf.getDate());
        if (day.length < 2){
            day = "0" + day;
        }
        let month = String(buf.getMonth() + 1);
        if (month.length < 2){
            month = "0" + month;
        }
        let year = (buf.getFullYear());
        doc["activateDate"] = `${day}.${month}.${year}`;
    }
    let city = getVarFromContainer(page,"city");
    let buildingType = getVarFromContainer(page,"buildingType");
    let building = getVarFromContainer(page,"building");
    let streetType = getVarFromContainer(page,"streetType");
    let street = getVarFromContainer(page,"street");
    let flatType = getVarFromContainer(page,"flatType");
    let flat = getVarFromContainer(page,"flat");
    let address = city;
    if (street){
        address += `, ${streetType} ${street}`;
    }
    if (building){
        address += `, ${buildingType} ${building}`;
    }
    if (flat){
        address += `, ${flatType} ${flat}`;
    }
    doc["docName"] = getVarFromContainer(container,"doc_docName");
    doc["posType"] = getVarFromContainer(container,"doc_posType");
    doc["payType"] = payType.options[payType.selectedIndex].textContent.trim();
    doc["city"] = getVarFromContainer(page,"city");
    doc["prevAddress"] = prevAddress;
    doc["nextAddress"] = nextAddress;
    doc["manager"] = manager.options[manager.selectedIndex].textContent.trim();
    doc["address"] = address;
    comment.value = commentTemplates(doc["posType"],doc);
}

/*---------------------------------------------------------------------------*/

function modifyDocCancel(
        button
){
    let container = button.closest(".page");
    let clientId = getValue(container.querySelector("#cur_clientId"));
    closeTab(button);
    showClientCard(clientId);
}

/*---------------------------------------------------------------------------*/

function saveDoc(
        button,
){
    let container = button.closest(".docContainer");
    if (!getVarFromContainer(container,"comment_filePath")){
        errorMessage("Выберите файл");
        return;
    }
    let page = button.closest(".page");
    let comment = container.querySelector("#comment_text");
    container.querySelector(".acceptBlock").classList.add("hidden");
    container.querySelector(".buttonBlock").classList.add("hidden");
    container.querySelector(".paramsContainer").classList.add("hidden");
    container.querySelector(".commentContainer").classList.remove("hidden");
    let type = getVarFromContainer(container,"doc_posType");
    let paramKeys = _globalSettings["Account"]["posTypeParamList"][type];
    let manager = page.querySelector("#manager");
    let doc = {};
    for(let el of paramKeys){
        let param = getVarFromContainer(container,"param_" + el);
        doc["param_" + el] = param;
    }
    
    let keys = {
        dnum : "doc_dnum",
        posId : "doc_posId",
        docId : "doc_docId",
        filePath : "comment_filePath",
        fileName : "comment_fileName",
        clientId : "doc_clientId"
    };
    for(let key in keys){
        doc[key] = getVarFromContainer(container,keys[key]);
    }
    doc["comment"] = comment.value;
    doc["commentAuthor"] = getCookie("login");
    doc["commentTimeStamp"] = Math.floor(Date.now()/1000);
    let data = {
        doc : doc,
        params : {
            dnum : doc["dnum"],
            clientId : doc["clientId"]
        }
    };
    
//    if (commentType != "none"){
//        data["comment"] = {
//            author : getCookie("login"),
//            type : commentType,
//            text : comment.value,
//            filePath : doc["filePath"],
//            fileName : doc["fileName"],
//            timeStamp : Math.floor(Date.now()/1000)
//        };
//    }
    
    
    
    getXhr({
        action: "saveAdditional",
        data : JSON.stringify(data)
    }).then((html) => {
        closeTab(button);
        showClientCard(getVarFromContainer(container,"doc_clientId"));
//        debug(html);
    });
    
}

/*---------------------------------------------------------------------------*/

function deleteDoc(
        button
){
    let container = button.closest(".docContainer");
    let config = {
        docId : getVarFromContainer(container,"doc_docId"),
        posId : getVarFromContainer(container,"doc_posId"),
        dnum : getVarFromContainer(container,"doc_dnum"),
        action : "deleteDoc"
    };
    getXhr(config).then((html) => {
        closeTab(button);
        showClientCard(getVarFromContainer(container,"doc_clientId"));
    });
}

/*---------------------------------------------------------------------------*/

function debug(
        html
){
    if(getCookie("login") == "filipp"){
        newPage(html);
    }
}

/*---------------------------------------------------------------------------*/

function toggleDocCard(
        button
){
    let container = button.closest(".docContainer");
    let docBody = container.querySelector(".docBody");
    docBody.classList.toggle("hidden");
}

/*---------------------------------------------------------------------------*/

function deleteClient(
        button
){
    let page = button.closest(".page");
    let clientId = getValue(page.querySelector("#cur_clientId"));
    getXhr({
        action : "deleteClient",
        clientId : clientId
    }).then((html) => {
        closeTab(button);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function getCommentFile(
        button
){
    let container = button.closest(".commentContainer");
    let fullPath = getVarFromContainer(container,"comment_filePath");
    getFile(fullPath);
}

/*---------------------------------------------------------------------------*/

function getDocFile(
        button
){
    let container = button.closest(".chronologyPosContainer");
    let fullPath = getVarFromContainer(container,"chr_posFilePath");
    if (fullPath){
        getFile(fullPath);
    }
}

/*---------------------------------------------------------------------------*/

function saveComment(
        button,
        type
){
    let cont = button.closest(".commentContainer");
    let clientId = getVarFromContainer(cont,"comment_clientId");
    let text = getVarFromContainer(cont,"comment_text");
    let fileName = getVarFromContainer(cont,"comment_fileName");
    let filePath = getVarFromContainer(cont,"comment_filePath");
    let fileNameEnd = getVarFromContainer(cont,"comment_fileNameEnd");
    let filePathEnd = getVarFromContainer(cont,"comment_filePathEnd");
    let timeStamp = cont.querySelector("#comment_timeStamp");
    let author = cont.querySelector("#comment_author");
    let eventDate = getVarFromContainer(cont,"comment_eventDate");
    let dnum = getVarFromContainer(cont,"comment_dnum");
    let docId = getVarFromContainer(cont,"comment_docId");
    let posId = getVarFromContainer(cont,"comment_posId");
    let date = getVarFromContainer(cont,"comment_date");
    data = {
        text : text,
        fileName : fileName,
        filePath : filePath,
        fileNameEnd : fileNameEnd,
        filePathEnd : filePathEnd,
        type : type
    };
    if (timeStamp != undefined){
        data["timeStamp"] = getValue(timeStamp);
        data["changeTime"] = Math.floor(Date.now()/1000);
    }
    else{
        data["timeStamp"] = Math.floor(Date.now()/1000);
    }
    
    if (author != undefined){
        data["author"] = getValue(author);
        data["changeAuthor"] = getCookie("login");
    }
    else{
        data["author"] = getCookie("login");
    }
    if (type == "block"){
        data["blockType"] = getVarFromContainer(cont,"comment_blockType");
        data["comment"] = data["text"];
        data["blockStart"] = getVarFromContainer(cont,"comment_blockStart");
        data["blockEnd"] = getVarFromContainer(cont,"comment_blockEnd");
    }
    if (type == "event"){
        if (!eventDate){
            fMsgBlock("","<h2 style='text-align: center;color : var(--modColor_darkest)'>Введите дату</h2>",120,500,"dateError");
            return;
        }
        else{
            data["eventDate"] = eventDate;
        }
    }
    if (type == "fixed"){
        data["dnum"] = dnum;
        data["docId"] = docId;
        data["posId"] = posId;
    }
    if (type == "clientDoc"){
        data["docId"] = docId;
        data["date"] = date;
    }
    
    getXhr({
        action : "saveComment",
        clientId : clientId,
        data : JSON.stringify(data)
    }).then((html) => {
        closeTab(button);
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function deleteComment(
        button
){
    let container = button.closest(".commentContainer");
    let clientId = getVarFromContainer(container,"comment_clientId");
    let commentId = getVarFromContainer(container,"comment_timeStamp");
    getXhr({
        action : "deleteComment",
        clientId : clientId,
        commentId : commentId
    }).then((html) => {
        closeTab(button);
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function modifyContactsForm(
        button
){
    button.classList.add("hidden");
    let container = button.closest(".contactsForm");
    container.querySelector("#contactAcceptBlock").classList.remove("hidden");
    swapDisableAll(container);
    let buttonList = Array.from(container.querySelectorAll(".phoneButton"));
    for(let el of buttonList){
        el.classList.remove("hidden");
    }
    let imageList = Array.from(container.querySelectorAll(".phoneImage"));
    for(let el of imageList){
        el.classList.add("hidden");
    }
}

/*---------------------------------------------------------------------------*/

function modifyContactsFormCancel(
        button
){
    let container = button.closest(".contactsForm");
    let clientId = getValue(container.querySelector("#id"));
    closeTab(button);
    showClientCard(clientId);
}

/*---------------------------------------------------------------------------*/

function modifyContactsFormAccept(
        button
){
    let container = button.closest(".contactsForm");
    let clientId = getVarFromContainer(container,"id");
    let onClose = function(){
        closeTab(button);
        showClientCard(clientId);
    };
    saveClient(button,true,onClose,null,"contactsForm");
}

/*---------------------------------------------------------------------------*/

function showContractForm(
        button
){
    let container = button.closest(".page");
    let clientId = getVarFromContainer(container,"cur_clientId");
    getXhr({
        action : "getContractForm",
        clientId : clientId
    }).then((html) => {
        closeTab(button);
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function checkClientType(
        container
){
    let clientType = getVarFromContainer(container,"clientType");
    let binType = container.querySelector("#binType");
    let iban = container.querySelector("#iban");
    let kbe = container.querySelector("#kbe");
    let bik = container.querySelector("#bik");
    let bank = container.querySelector("#bank");
    let gosSum = container.querySelector("#gosSum");
    let gosDnum = container.querySelector("#gosDnum");
    let udoNumber = container.querySelector("#udoNumber");
    if (clientType){
        if (clientType == "ГУ"){
            if (gosSum){
                gosSum.closest(".rowContainer").classList.remove("hidden");
            }
            if (gosDnum){
                gosDnum.closest(".rowContainer").classList.remove("hidden");
            }
        }
        else{
            if (gosSum){
                gosSum.closest(".rowContainer").classList.add("hidden");
            }
            if (gosDnum){
                gosDnum.closest(".rowContainer").classList.add("hidden");
            }
        }
        if ((clientType == "ИП")||(clientType == "ФЛ")){
            if (binType){
                binType.textContent = "ИИН";
            }
            if (udoNumber){
                udoNumber.closest(".rowContainer").classList.remove("hidden");
            }
        }
        else{
            if (binType){
                binType.textContent = "БИН";
            }
            if (udoNumber){
                udoNumber.closest(".rowContainer").classList.add("hidden");
            }
        }
        if (clientType == "ИП"){
            if (kbe){
                kbe.value = 19;
            }
        }
        if (clientType == "ТОО"){
            if (kbe){
                kbe.value = 17;
            }
        }
        if (clientType == "ФЛ"){
            if (iban){
                iban.closest(".rowContainer").classList.add("hidden");
            }
            if (kbe){
                kbe.closest(".rowContainer").classList.add("hidden");
            }
            if (bik){
                bik.closest(".rowContainer").classList.add("hidden");
            }
            if (bank){
                bank.closest(".rowContainer").classList.add("hidden");
            }
        }
        else{
            if (iban){
                iban.closest(".rowContainer").classList.remove("hidden");
            }
            if (kbe){
                kbe.closest(".rowContainer").classList.remove("hidden");
            }
            if (bik){
                bik.closest(".rowContainer").classList.remove("hidden");
            }
            if (bank){
                bank.closest(".rowContainer").classList.remove("hidden");
            }
        }
    }
}

/*---------------------------------------------------------------------------*/

function checkUdoNumber(
        el
){
    let bin = getValue(el);
    let flag = false;
    let reg = new RegExp("^[0-9]");
    if (bin.length != 9){
        flag = true;
    }
    for(let i = 0; i < bin.length; i++){
        if (!reg.test(bin[i])){
            flag = true;
        }
    }
    if (flag){
        el.style.color = "red";
        
    }
    else{
        el.style.color = "var(--modColor_darkest)";
    }
}

/*---------------------------------------------------------------------------*/

function checkBin(
        el
){
    let bin = getValue(el);
    let flag = false;
    let reg = new RegExp("^[0-9]");
    if (bin.length != 12){
        flag = true;
    }
    for(let i = 0; i < bin.length; i++){
        if (!reg.test(bin[i])){
            flag = true;
        }
    }
    if (flag){
        el.style.color = "red";
        
    }
    else{
        el.style.color = "var(--modColor_darkest)";
    }
}

/*---------------------------------------------------------------------------*/

function checkIban(
        el
){
    let bin = getValue(el);
    let flag = false;
    let reg = new RegExp("^[0-9a-zA-Z]");
    if (bin.length != 20){
        flag = true;
    }
    for(let i = 0; i < bin.length; i++){
        if (!reg.test(bin[i])){
            flag = true;
        }
    }
    if (flag){
        el.style.color = "red";
        
    }
    else{
        el.style.color = "var(--modColor_darkest)";
    }
}



/*---------------------------------------------------------------------------*/

function eventButtonClick(
        button
){
    let container = button.closest(".buttonContainer");
    container.querySelector("#buttonBlock").classList.add("hidden");
    container.querySelector("#dateBlock").classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function eventButtonCancel(
        button
){
    let container = button.closest(".buttonContainer");
    container.querySelector("#buttonBlock").classList.remove("hidden");
    container.querySelector("#dateBlock").classList.add("hidden");
}

/*---------------------------------------------------------------------------*/

function editComment(
        button
){
    let role = getCookie("orkkrole");
    let container = button.closest(".commentContainer");
    let buf = getVarFromContainer(container,"_banList_");
    let banList = {};
    if (buf){
        banList = JSON.parse(buf);
    }
    console.log(banList["comment_text"]);
    console.log(document.cookie);
    console.log("!!!");
    let text = container.querySelector("#comment_text");
    let fileInputContainer = container.querySelector("#commentFileInputContainer");
    let fileBlock = container.querySelector("#commentFileBlock");
    let fileBlockEnd = container.querySelector("#commentFileBlockEnd");
    let acceptButton = container.querySelector("#commentEditAcceptButton");
    let deleteButton = container.querySelector("#commentDeleteButton");
    let buttonBlock = container.querySelector("#eventButtonBlock");
    if (buttonBlock){
        buttonBlock.classList.add("hidden");
    }
    if (!banList.includes("commentDeleteButton")){
        deleteButton.classList.remove("hidden");
    }
    fileInputContainer.classList.remove("hidden");
    fileBlock.classList.add("hidden");
    if (fileBlockEnd){
        fileBlockEnd.classList.add("hidden");
    }
    if (!banList.includes("comment_text")){
        text.style.backgroundColor = "var(--modBGColor)";
        text.removeAttribute("readonly");
        text.style.minHeight = "170px";
        text.style.overflow = "auto";
        text.style.padding = "10px";
    }
    button.classList.add("hidden");
    acceptButton.classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function editCommentAccept(
        button
){
    let container = button.closest(".commentContainer");
    let type = getVarFromContainer(container,"comment_type");
    saveComment(button,type);
    
}

/*---------------------------------------------------------------------------*/

function moveEvent(
        button
){
    let container = button.closest(".commentContainer");
    container.querySelector("#eventButtonBlock").classList.add("hidden");
    container.querySelector("#eventDateBlock").classList.remove("hidden");
    swapId("comment_eventDate","comment_eventDate_old",container);
}

/*---------------------------------------------------------------------------*/

function eventMoveCancel(
        button
){
    let container = button.closest(".commentContainer");
    container.querySelector("#eventButtonBlock").classList.remove("hidden");
    container.querySelector("#eventDateBlock").classList.add("hidden");
    swapId("comment_eventDate","comment_eventDate_old",container);
    
}    

/*---------------------------------------------------------------------------*/

function closeEvent(
        button
){
    let container = button.closest(".commentContainer");
    container.querySelector("#eventButtonBlock").classList.add("hidden");
    container.querySelector("#eventCloseBlock").classList.remove("hidden");
    
    let text = container.querySelector("#comment_text");
    let fileInputContainer = container.querySelector("#commentFileInputContainer");
    let fileBlock = container.querySelector("#commentFileBlock");
    fileInputContainer.classList.remove("hidden");
    fileBlock.classList.add("hidden");
    text.style.backgroundColor = "var(--modBGColor)";
    text.value = text.value + "(Завершено)\n\n";
    text.removeAttribute("readonly");
    text.style.minHeight = "170px";
    text.style.overFlow = "auto";
    text.style.padding = "10px";
    container.querySelector("#commentEditButton").classList.add("hidden");
    
    swapId("comment_type","comment_type_old",container);
}

/*---------------------------------------------------------------------------*/

function closeEventCancel(
        button
){
    let container = button.closest(".commentContainer");
    let clientId = getValue(container.querySelector("#comment_clientId"));
    closeTab(button);
    showClientCard(clientId);
}

/*---------------------------------------------------------------------------*/

function closeEventAccept(
        button
){
    let container = button.closest(".commentContainer");
    let text = container.querySelector("#comment_text");
    let title = container.querySelector("#commentTitle").textContent.trim();
    text.value = title + "\n\n" + text.value;
    saveComment(button,"normal");
}

/*---------------------------------------------------------------------------*/

function bikSelect(
        select,
        containerClass,
        bikId = "bik",
        bankId = "bank"
){
    let container = select.closest("." + containerClass);
    let buf = select.options[select.selectedIndex].textContent.trim().split(" - ");
    container.querySelector("#"+bikId).value = buf[0];
    container.querySelector("#"+bankId).value = buf[1];
    
}

/*---------------------------------------------------------------------------*/

function hiddenHistoryClick(
        button
){
    let container = button.closest(".hiddenPanel");
    let textList = Array.from(container.querySelectorAll(".hiddenTextContainer"));
    for(let el of textList){
        el.querySelector("#hiddenText").classList.toggle("hidden");
    }
}

/*---------------------------------------------------------------------------*/

function removeContract(
        button
){
    let container = button.closest(".editedContainer");
    let clientId = getVarFromContainer(container,"id");
    
    getXhr({
        action : "removeClientContract",
        clientId : clientId
    }).then((html) => {
        closeTab(button);
        showClientCard(clientId);
//        debug(html);
    });
    
}

/*---------------------------------------------------------------------------*/

function showClientBlockForm(
        button
){
    let container = button.closest(".editedContainer");
    let id = getVarFromContainer(container,"id");
    
    buf_func = function(){
        closeTab(button);
        showClientCard(id);
    };
    getXhr({
        action : "getClientBlockForm",
        clientId : id
    }).then(function(html){
        fMsgBlock("Заблокировать",html,365,700,"blockMsg",buf_func,button);
    });
} 

/*---------------------------------------------------------------------------*/

function saveClientBlock(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"block_clientId");
    let params = {
        action : "saveClientBlock",
        clientId : getVarFromContainer(container,"block_clientId"),
        data : JSON.stringify({
            comment : getVarFromContainer(container,"blockComment"),
            blockStart : getVarFromContainer(container,"blockStart"),
            blockEnd : getVarFromContainer(container,"blockEnd"),
            filePath : getVarFromContainer(container,"blockFilePath"),
            fileName : getVarFromContainer(container,"blockFileName"),
            filePathEnd : getVarFromContainer(container,"blockFilePathEnd"),
            fileNameEnd : getVarFromContainer(container,"blockFileNameEnd"),
            timeStamp : getVarFromContainer(container,"blockTimeStamp"),
            twoDocs : getVarFromContainer(container,"blockTwoDocs") ? "1" : "",
            blockType : "current"
        })
    };
    getXhr(params).then((html) => {
        let caller = document.getElementById("blockMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("blockMsg");
        showClientCard(clientId);
    });
}

/*---------------------------------------------------------------------------*/

function deleteClientBlock(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"block_clientId");
    getXhr({
        action : "deleteClientBlock",
        clientId : clientId
    }).then((html) => {
        let caller = document.getElementById("blockMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("blockMsg");
        showClientCard(clientId);
    });
}

/*---------------------------------------------------------------------------*/

function closeClientBlock(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"block_clientId");
    getXhr({
        action : "closeClientBlock",
        clientId : clientId
    }).then((html) => {
        let caller = document.getElementById("blockMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("blockMsg");
        newPage(html);
    });
} 

/*---------------------------------------------------------------------------*/

function showRenewClientForm(
        button
){
    let container = button.closest(".editedContainer");
    let id = getVarFromContainer(container,"id");
    
    
    getXhr({
        action : "getRenewClientForm",
        clientId : id
    }).then(function(html){
        fMsgBlock("Переоформить",html,340,700,"renewMsg",null,button);
    });
}

/*---------------------------------------------------------------------------*/

function renewClient(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"renew_clientId");
    let params = {
        action : "renewClient",
        clientId : clientId,
        data : JSON.stringify({
            dnum : getVarFromContainer(container,"renew_dnum"),
            docType : getVarFromContainer(container,"renew_docType"),
            date : getVarFromContainer(container,"renew_date"),
            filePath : getVarFromContainer(container,"renew_filePath"),
            fileName : getVarFromContainer(container,"renew_fileName"),
            renewType : getVarFromContainer(container,"renewType")
        })
    };
    getXhr(params).then((html) => {
        let caller = document.getElementById("renewMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("renewMsg");
        newPage(html);
    });
}

/*---------------------------------------------------------------------------*/

function renewClientLock(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"renew_clientId");
    getXhr({
        action : "renewClientLock",
        clientId : clientId
    }).then((html) => {
        let caller = document.getElementById("renewMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("renewMsg");
        showClientCard(clientId);
    });
} 

/*---------------------------------------------------------------------------*/

function showConnectClientForm(
        button
){
    let container = button.closest(".editedContainer");
    let id = getVarFromContainer(container,"id");
    
    
    getXhr({
        action : "getConnectClientForm",
        clientId : id
    }).then(function(html){
        fMsgBlock("Подключить",html,175,700,"connectMsg",null,button);
    });
}

/*---------------------------------------------------------------------------*/

function showChangeDnumForm(
        button
){
    let container = button.closest(".editedContainer");
    let id = getVarFromContainer(container,"id");
    
    
    getXhr({
        action : "getChangeDnumForm",
        clientId : id
    }).then(function(html){
        fMsgBlock("Изменить номер договора",html,175,700,"changeDnumMsg",null,button);
    });
}

/*---------------------------------------------------------------------------*/

function connectClient(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"connect_clientId");
    let activateDate = getVarFromContainer(container,"connect_activateDate");
    getXhr({
        action : "connectClient",
        clientId : clientId,
        activateDate : activateDate
    }).then((html) => {
        let caller = document.getElementById("connectMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("connectMsg");
        showClientCard(clientId);
        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function showDisconnectClientForm (
        button
){
    let container = button.closest(".editedContainer");
    let id = getVarFromContainer(container,"id");
    
    
    getXhr({
        action : "getDisconnectClientForm",
        clientId : id
    }).then(function(html){
        fMsgBlock("Отключить",html,450,700,"disconnectMsg",null,button);
        let element = document.getElementById("disconnectType");
        disconnectTypeChange(element);
    });
}

/*---------------------------------------------------------------------------*/

function disconnectTypeChange(
        element
) {
    let container = element.closest(".fmsgBlock_data");
    let typeSelect = container.querySelector("#disconnectType");
    let type = getValue(typeSelect);
    let prefix = "other";
    switch (type) {
        case "В одностороннем порядке":
            prefix = "unilaterally";
            break;
        case "По заявлению":
            prefix = "statement";
            break;
    }
    let reasonList = Array.from(container.querySelectorAll(".disconnectReasonSelect"));
    for(let el of reasonList){
        el.classList.add("hidden");
    }
    container.querySelector(`#${prefix}_disconnectReason`).classList.remove("hidden");
    disconnectReasonChange(element);
}

/*---------------------------------------------------------------------------*/

function disconnectReasonChange(
        element
){
    let container = element.closest(".fmsgBlock_data");
    let reasonSelect = container.querySelector(".disconnectReasonSelect:not(.hidden)");
    let reason = getValue(reasonSelect);
    let addressBlok = container.querySelector(".disconnectAddressBlock");
    let competitorBlock = container.querySelector(".disconnectCompetitorBlock");
    let descList = Array.from(container.querySelectorAll(".disconnectDescBlock"));
    for(let el of descList){
        el.classList.add("hidden");
    }
    if (reason == "Переезд вне МЁ"){
        addressBlok.classList.remove("hidden");
    }
    if (reason == "Уход к конкуренту"){
        competitorBlock.classList.remove("hidden");
    }
    
    
} 

/*---------------------------------------------------------------------------*/

function saveClientDisconnect(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let type = getVarFromContainer(container,"disconnectType");
    let prefix = "other";
    switch (type) {
        case "В одностороннем порядке":
            prefix = "unilaterally";
            break;
        case "По заявлению":
            prefix = "statement";
            break;
    }
    let reasonDesc = "";
    let reason = getVarFromContainer(container,prefix + "_disconnectReason");
    if (reason == "Переезд вне МЁ"){
        reasonDesc = getVarFromContainer(container,"disconnectAddress");
    }
    if (reason == "Уход к конкуренту"){
        reasonDesc = getVarFromContainer(container,"disconnectCompetitor");
    }
    let clientId = getVarFromContainer(container,"disconnect_clientId");
    let params = {
        action : "saveClientDisconnect",
        data : JSON.stringify({
            disconnectType : type,
            disconnectReason : reason,
            disconnectReasonDesc : reasonDesc,
            disconnectDate : getVarFromContainer(container,"disconnectDate"),
            disconnectComment : getVarFromContainer(container,"disconnectComment"),
            disconnectFilePath : getVarFromContainer(container,"disconnectFilePath"),
            disconnectFileName : getVarFromContainer(container,"disconnectFileName"),
            disconnectMethod : getVarFromContainer(container,"disconnectMethod")
        }),
        clientId : clientId
    };
    getXhr(params).then((html)=>{
        let caller = document.getElementById("disconnectMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("disconnectMsg");
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function deleteClientDisconnect(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"disconnect_clientId");
    let params = {
        action : "saveClientDisconnect",
        data : JSON.stringify({
            disconnectType : "",
            disconnectReason : "",
            disconnectReasonDesc : "",
            disconnectDate : "",
            disconnectComment : "",
            disconnectFilePath : "",
            disconnectFileName : ""
        }),
        clientId : clientId
    };
    getXhr(params).then((html)=>{
        let caller = document.getElementById("disconnectMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("disconnectMsg");
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function lockClientDisconnect(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"disconnect_clientId");
    let params = {
        action : "lockClientDisconnect",
        clientId : clientId
    };
    getXhr(params).then((html)=>{
        let caller = document.getElementById("disconnectMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("disconnectMsg");
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function showNewClientDocForm(
        button
){
    let container = button.closest(".page");
    let clientId = getVarFromContainer(container,"cur_clientId");
    getXhr({
        action : "getCreateClientDocForm",
        clientId : clientId
    }).then((html) => {
        fMsgBlock("Создать документ",html,515,700,"createDocMsg",null,button);
    });
}

/*---------------------------------------------------------------------------*/

function createClientDoc(
        button
){
    let get = getVarFromContainer;
    let container = button.closest(".fmsgBlock_data");
    let filePath = get(container,"clientDocFilePath");
    if (!filePath){
        errorMessage("Выберите файл!");
        return;
    }
    let docName = get(container,"clientDocName");
    let clientId = get(container,"clientDoc_clientId");
    let commentText = docName + "\n\n" + get(container,"clientDocComment");
    let params = {
        action : "saveClientDoc",
        clientId : clientId,
        data : JSON.stringify({
            docType : get(container,"clientDocType"),
            docName : docName,
            comment : commentText,
            filePath : get(container,"clientDocFilePath"),
            fileName : get(container,"clientDocFileName"),
            placement : get(container,"clientDocPlacement"),
            register : get(container,"clientDocRegister"),
            date : get(container,"clientDocDate")
        })
    };
    getXhr(params).then((html) => {
        let caller = document.getElementById("createDocMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("createDocMsg");
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function changeDnum(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let clientId = getVarFromContainer(container,"changeDnum_clientId");
    let dnum = getVarFromContainer(container,"changeDnum_dnum");
    getXhr({
        action : "changeDnum",
        clientId : clientId,
        dnum : dnum
    }).then((html) => {
        let caller = document.getElementById("changeDnumMsg").msgCaller;
        closeTab(caller);
        fUnMsgBlock("changeDnumMsg");
        showClientCard(clientId);
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

function generateNewNumber(
        button,
        id
){
    let inp = button.closest(".page").querySelector("#" + id);
    getXhr({
        action : "getNewNumber"
    }).then((number) => {
        inp.value = number;
    });
}

/*---------------------------------------------------------------------------*/

function changeClientDocRegister(
        sel
){
    let docContainer = sel.closest(".chronologyDocContainer");
    let clientId = getValue(docContainer.querySelector("#chr_clientId"));
    let docId = getValue(docContainer.querySelector("#chr_docId"));
    inputCheckboxToggle(sel);
    let register = getValue(sel) ? "1" : "0";
    getXhr({
        action : "changeClientDocRegister",
        clientId : clientId,
        docId : docId,
        register : register
    }).then((html) => {
        
    });
}

/*---------------------------------------------------------------------------*/

function showUserCard(
        button,
        containerSelector = ".page",
        dnumId = "dnum"
){
    let container = button.closest(containerSelector);
    let dnum = getVarFromContainer(container,dnumId);
    fMsgBlock("Карточка абонента","<div id='userCardFrame'><div id='userCard' style='height: 100%; overflow-y: auto;padding: 0px 15px'></div></div>",700,1000,"userCardMsg");
    include_dom("/_modules/orkkNew/support/getUserCard.php?user=" + dnum + "&obj=userCard");
    
}

/*---------------------------------------------------------------------------*/

function showClientCardFromTableRow(
        element
){
    let container = element.closest("tr");
    let clientId = getVarFromContainer(container,"tr_clientId");
    if (clientId){
        showClientCard(clientId);
    }
}

/*---------------------------------------------------------------------------*/

function saveClientSupportInfo(
        element,
        containerSelector = ".page"
){
    let container = element.closest(containerSelector);
    let clientId = getVarFromContainer(container,"sup_clientId");
    let keyList = [
        "remark",
        "loginList"
    ];
    let params = {};
    for(let key of keyList){
        let block = container.querySelector("#" + key);
        if (block){
            params[key] = getVarFromContainer(container,key);
        }
    }
    
    getXhr({
        action : "saveClientSupportInfo",
        clientId : clientId,
        params : JSON.stringify(params)
    }).then((html) => {
        tableCellEditClose(element,true);
//        debug(html);
    });
} 

/*---------------------------------------------------------------------------*/

function showServiceTypeForm(
        button
){
    let container = button.closest(".page");
    let clientId = getVarFromContainer(container,"cur_clientId");
    getXhr({
        action : "getServiceTypeForm",
        clientId : clientId
    }).then((html) => {
        fMsgBlock("Виды услуг",html,335,700,"serviceType",null,button);
    });
} 

/*---------------------------------------------------------------------------*/

function saveServiceType(
        button
){
    let onClose = function(){
        fUnMsgBlock("serviceType");
    };
    saveClient(button,false,onClose,null,"fmsgBlock_data");
}

/*---------------------------------------------------------------------------*/

function blockFormTwoDocsClick(
        el
){
    inputCheckboxToggle(el);
    let container = el.closest(".fmsgBlock_data");
    let value = getValue(el);
    let fileEnd = container.querySelector(".blockFileEnd");
    if (value){
        fileEnd.classList.remove("hidden");
    }
    else{
        setVarToContainer(container,"blockFilePathEnd","");
        setVarToContainer(container,"blockFileNameEnd","");
        fileEnd.classList.add("hidden");
    }
} 

/*---------------------------------------------------------------------------*/

function showDocumentPlacementForm(
        button,
        containerSelector = ".chronologyDocContainer",
        prefix = "chr"
){
    let container = button.closest(containerSelector);
    let dnum = getVarFromContainer(container,`${prefix}_dnum`);
    let docId = getVarFromContainer(container,`${prefix}_docId`);
    let callback = function(){
        console.log("!!!!");
        let keyList = [
            "safekeepingPlacement",
            "transferActPlacement",
            "disclaimerPlacement"
        ];
        let fmsg = document.getElementById("documentPlacementForm");
        let additionalBlock = button.closest(".additionalBlock");
        let msgDoc = fmsg.querySelector("#docPlacement_block").querySelector("#button_text");
        let addDoc = additionalBlock.querySelector("#button_text");
        addDoc.textContent = msgDoc.textContent;
        addDoc.style.backgroundColor = msgDoc.style.backgroundColor;
        addDoc.style.color = msgDoc.style.color;
        for(let key of keyList){
            let msgButton = fmsg.querySelector(`#${key}_block`).querySelector("#button_text");
            let addButton = additionalBlock.querySelector(`#${key}`);
            addButton.style.backgroundColor = msgButton.style.backgroundColor;
        }
    };
    getXhr({
        action : "getDocumentPlacementForm",
        dnum : dnum,
        docId : docId
    }).then((html) => {
        fMsgBlock("Местоположения",html,255,400,"documentPlacementForm",callback);
    });
}

/*---------------------------------------------------------------------------*/












