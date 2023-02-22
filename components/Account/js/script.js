function changeDocPlacement(
        sel,
        containerSelector = ".chronologyDocContainer"
){
    let docContainer = sel.closest(containerSelector);
    let clientDoc = getVarFromContainer(docContainer,"chr_clientDoc");
    let clientId = getVarFromContainer(docContainer,"chr_clientId");
    let dnum = getValue(docContainer.querySelector("#chr_dnum"));
    let docId = getValue(docContainer.querySelector("#chr_docId"));
    let docPlacement = sel.options[sel.selectedIndex].value;
    getXhr({
        action : "changeDocPlacement",
        dnum : dnum,
        docId : docId,
        docPlacement : docPlacement,
        clientDoc : clientDoc ? "1" : "",
        clientId : clientId
    }).then((html) => {
        
    });
}

/*---------------------------------------------------------------------------*/

function changeForPayment(
        sel,
        containerSelector = ".chronologyDocContainer",
        prefix = "chr"
){
    let container = sel.closest(containerSelector);
    let dnum = getVarFromContainer(container,`${prefix}_dnum`);
    let docId = getVarFromContainer(container,`${prefix}_docId`);
    let payManager = getVarFromContainer(container,`${prefix}_payManager`);
    let profile = getCookie("login");
    let result = "";
//    if (!payManager){
//        setVarToContainer(container,`${prefix}_payManager`,profile);
//        result = profile;
//    }
//    else{
//        if (payManager == profile){
//            setVarToContainer(container,`${prefix}_payManager`,"");
//            result = "";
//        }
//        else{
//            fMsgBlock("Ошибка","<h2 style='width:100%;text-align:center;'>Доступ запрещен</h2>",120,380,"errorMsg");
//            return;
//        }
//    }
//    
//    inputCheckboxToggle(sel);
    getXhr({
        action : "changePayManagerForm",
        dnum : dnum,
        docId : docId
//        payManager : result
    }).then((html) => {
        fMsgBlock("",html,180,400,"changePayManager");
        let msg = document.getElementById("changePayManager");
        msg.caller = sel;
//        debug(html);
    });
}

/*---------------------------------------------------------------------------*/

const changePayment = {
    
    checkRow(
            container,
            row
    ){
        let managerCont = getVarFromContainer(container,"manager");
        let yearCont = getVarFromContainer(container,"year");
        let monthCont = getVarFromContainer(container,"month");
        let managerRow = getVarFromContainer(row,"manager");
        let page = row.closest(".page");
        let yearRow = getVarFromContainer(container,"year");
        let monthRow = getVarFromContainer(page,"month");
        return (
                (managerCont != managerRow) ||
                (yearCont != yearRow) ||
                (monthCont != monthRow)
        );
    },
    
    /*---------------------------------------------------------------------------*/
    
    lock(
            button,
            containerSelector = ".msgBack"
    ){
        let container = button.closest(containerSelector);
        let data = {};
        let caller = container.caller;
        let row = container.row;
        data["year"] = "";
        data["month"] = "";
        data["payManager"] = "";
        data["forPayment"] = "1";
        let dnum = getVarFromContainer(container,"dnum");
        let docId = getVarFromContainer(container,"docId");
        getXhr({
            action : "changePayment",
            data : JSON.stringify(data),
            dnum : dnum,
            docId : docId
        }).then((html) => {
            if (caller != undefined){
                caller.style.backgroundColor = "#FF4500";
                caller.style.color = "var(--modBGColor)";
                caller.style.border = "none";
            }
            if (row != undefined){
                row.parentNode.removeChild(row);
            }
            fUnMsgBlock("changePayManager");
//            debug(html);
        });
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    save(
            button,
            containerSelector = ".msgBack"
    ){
        let container = button.closest(containerSelector);
        let data = {};
        let caller = container.caller;
        let row = container.row;
        data["year"] = getVarFromContainer(container,"year");
        data["month"] = getVarFromContainer(container,"month");
        data["payManager"] = getVarFromContainer(container,"manager");
        data["forPayment"] = "";
        let dnum = getVarFromContainer(container,"dnum");
        let docId = getVarFromContainer(container,"docId");
        getXhr({
            action : "changePayment",
            data : JSON.stringify(data),
            dnum : dnum,
            docId : docId
        }).then((html) => {
            if (caller != undefined){
                caller.style.backgroundColor = "green";
                caller.style.color = "var(--modBGColor)";
                caller.style.border = "1px solid green";
            }   
            if (row != undefined){
                if (changePayment.checkRow(container,row)){
                    row.parentNode.removeChild(row);
                };
            }
            fUnMsgBlock("changePayManager");
//            debug(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    delete(
            button,
            containerSelector = ".msgBack"
    ){
        let container = button.closest(containerSelector);
        let data = {};
        let caller = container.caller;
        let row = container.row;
        data["year"] = "";
        data["month"] = "";
        data["payManager"] = "";
        data["forPayment"] = "";
        let dnum = getVarFromContainer(container,"dnum");
        let docId = getVarFromContainer(container,"docId");
        getXhr({
            action : "changePayment",
            data : JSON.stringify(data),
            dnum : dnum,
            docId : docId
        }).then((html) => {
            if (caller != undefined){
                caller.style.backgroundColor = "var(--modBGColor)";
                caller.style.color = "green";
                caller.style.border = "1px solid green";
            }
            if (row != undefined){
                row.parentNode.removeChild(row);
            }
            fUnMsgBlock("changePayManager");
//            debug(html);
        });
    }
    
    /*---------------------------------------------------------------------------*/
    
};

/*---------------------------------------------------------------------------*/

function showNewDocForm(
        button,
        docId = null
){
    let page = button.closest(".page");
    let dnum = getValue(page.querySelector("#cur_dnum"));
    if (docId == null){
        getXhr({
            action : "getNewDocForm",
            dnum : dnum
        }).then((html) => {
            fMsgBlock("",html,170,300,"newDocForm");
        });
    }
    else{
        getXhr({
            action : "getNewDocForm",
            dnum : dnum,
            docId : docId
        }).then((html) => {
            fMsgBlock("",html,170,300,"newDocForm");
        });
    }
}

/*---------------------------------------------------------------------------*/

function showNewPosForm(
        button
){
    
}

/*---------------------------------------------------------------------------*/

function getDnumFromPage(
        el
){
    let page = el.closest(".page");
    let dnum = getValue(page.querySelector("#dnum"));
    return dnum;
}

/*---------------------------------------------------------------------------*/

function getAdditionalNameFromPage(
        el
){
    let page = el.closest(".page");
    let name = getValue(page.querySelector("#additionalName"));
    return name;
}

/*---------------------------------------------------------------------------*/

function getNewSpecificationDoc(
        button
){
    let page = button.closest(".page");
    let comment = page.querySelector("#comment");
    let commentAuthor;
    let commentTimeStamp;
    if (comment){
        comment = comment.value;
        commentAuthor = getCookie("login");
        commentTimeStamp = Math.floor(Date.now()/1000);
    }
    else{
        comment = "";
    }
    let clientId = page.querySelector("#clientId");
    if (clientId){
        clientId = getValue(clientId);
    }
    else{
        clientId = "";
    }
    let doc = {
        docId : "",
        posId : "",
        clientId : clientId,
        posType : "Спецификация",
        docType : "specification",
        docName : "Договор. Спецификация",
        comment : comment,
        commentAuthor : commentAuthor,
        commentTimeStamp : commentTimeStamp,
        docPlacement : getValue(page.querySelector("#docPlacement")),
        filePath : getValue(page.querySelector("#filePath")),
        fileName : getValue(page.querySelector("#fileName")),
        timeStamp : Math.floor(Date.now()/1000)
    };
    return doc;
}

/*---------------------------------------------------------------------------*/

function getNewSpecificationPos(
        docId,
        dnum
){
    return function (button){
        let page = button.closest(".page");
        let comment = page.querySelector("#comment");
        let commentAuthor;
        let commentTimeStamp;
        if (comment){
            comment = comment.value;
            commentAuthor = getCookie("login");
            commentTimeStamp = Math.floor(Date.now()/1000);
        }
        else{
            comment = "";
        }
        let clientId = page.querySelector("#clientId");
        if (clientId){
            clientId = getValue(clientId);
        }
        else{
            clientId = "";
        }
        let doc = {
            dnum : dnum,
            docId : docId,
            posId : "",
            clientId : clientId,
            posType : "Спецификация",
            docType : "specification",
            docName : "Договор. Спецификация",
            comment : comment,
            commentAuthor : commentAuthor,
            commentTimeStamp : commentTimeStamp,
            docPlacement : getValue(page.querySelector("#docPlacement")),
            filePath : getValue(page.querySelector("#filePath")),
            fileName : getValue(page.querySelector("#fileName")),
            timeStamp : Math.floor(Date.now()/1000)
        };
        return doc;
    }
}

/*---------------------------------------------------------------------------*/

function getNewAdditionalAgreement(
        posType,
        dnum,
        name
){
    return function(button){
        let page = button.closest(".page");
        let comment = page.querySelector("#comment");
        let commentAuthor;
        let commentTimeStamp;
        let docIdV = page.querySelector("#docId");
        if (docIdV){
            docId = getValue(docIdV);
        }
        else{
            docId = "";
        }
        if (comment){
            comment = comment.value;
            commentAuthor = getCookie("login");
            commentTimeStamp = Math.floor(Date.now()/1000);
        }
        else{
            comment = "";
        }
        let doc = {
            dnum : dnum,
            docId : docId,
            posId : "",
            clientId : "",
            posType : posType,
            docType : "additional",
            docName : name,
            comment : comment,
            commentAuthor : commentAuthor,
            commentTimeStamp : commentTimeStamp,
            docPlacement : getValue(page.querySelector("#docPlacement")),
            filePath : getValue(page.querySelector("#filePath")),
            fileName : getValue(page.querySelector("#fileName")),
            timeStamp : Math.floor(Date.now()/1000)
        };
        return doc;
    }
}

/*---------------------------------------------------------------------------*/

function addAdditional(
        clientId,
        posType,
        docId = ""
){
    getXhr({
        action : "addNewAdditional",
        clientId : clientId,
        posType : posType,
        docId : docId
    }).then((html) => {
        showClientCard(clientId);
    });
}

/*---------------------------------------------------------------------------*/

function showAdditionalTypeList(
        clientId,
        docId
){
    getXhr({
        action : "getAdditionalTypeList"
    }).then((html) => {
        fUnMsgBlock("accountClientList");
        _globalSelect = function(row){
            additionalType = getValue(row.querySelector("#table_additionalType"));
            addAdditional(clientId,additionalType,docId);
        };
        fMsgBlock("",html,400,1000,"accountClientList");
    });
}

/*---------------------------------------------------------------------------*/

function showAccountClientList(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let dnum = getVarFromContainer(container,"dnum");
    let docId = getVarFromContainer(container,"docId");
    getXhr({
        action : "getAccountClientList",
        dnum : dnum,
        docId : docId
    }).then((html) => {
        fUnMsgBlock("newDocForm");
        _globalSelect = function(row){
            let clientId = getValue(row.querySelector("#table_clientId"));
            let docId = getValue(row.querySelector("#table_docId"))
            showAdditionalTypeList(clientId,docId);
        };
        fMsgBlock("",html,400,1000,"accountClientList");
    });
}

/*---------------------------------------------------------------------------*/

function selectClientFromTable(
        row
){
    let clientId = getVarFromContainer(row,"table_clientId");
    if (_globalSelect != null){
        _globalSelect(clientId);
    }
}

/*---------------------------------------------------------------------------*/




