 /*---------------------------------------------------------------------------*/


function showAgreementRegister(){
    let date = new Date();
    getXhr({
        action : "getAgreementRegister",
        year : date.getFullYear(),
        month : date.getMonth() + 1
    }).then((html) => {
        newPage(html,true,function(page){
            let containerList = page.querySelectorAll(".agreementTable");
            for(let container of containerList){
                swapDisableAll(container);
                allInputAreaAutosize(container);
            }
        });
        
    });
}

/*---------------------------------------------------------------------------*/

function reloadAgreementRegister(
        button
){
    let page = button.closest(".page");
    let month = getVarFromContainer(page,"month");
    let year = getVarFromContainer(page,"year");
    getXhr({
        action : "getAgreementRegister",
        year : year,
        month : month
    }).then((html) => {
        page.innerHTML = html;
        let containerList = page.querySelectorAll(".agreementTable");
        for(let container of containerList){
            swapDisableAll(container);
            allInputAreaAutosize(container);
        }
    });
}

/*---------------------------------------------------------------------------*/

function closeAgreementRegister(
        button
){
    let page = button.closest(".page");
    let month = getVarFromContainer(page,"month");
    let year = getVarFromContainer(page,"year");
    getXhr({
        action : "closeAgreementRegister",
        year : year,
        month : month
    }).then((html) => {
        page.innerHTML = html;
        let containerList = page.querySelectorAll(".agreementTable");
        for(let container of containerList){
            swapDisableAll(container);
            allInputAreaAutosize(container);
        }
    });
}

/*---------------------------------------------------------------------------*/

function printAgreementRegister(
        button
){
    let container = button.closest(".page");
    let year = getVarFromContainer(container,"current_year");
    let month = getVarFromContainer(container,"current_month");
    let fl = getVarFromContainer(container,"flPrint");
    getNewTab({
        action : "printAgreementRegister",
        year : year,
        month : month,
        fl : fl ? "1" : "0"
    });
}

/*---------------------------------------------------------------------------*/

function showSupportRegister(){
    getXhr({
        action : "getSupportRegister"
    }).then((html) => {
        newPage(html,true,function(page){
            let containerList = page.querySelectorAll(".supportTable");
            for(let container of containerList){
                swapDisableAll(container);
                allInputAreaAutosize(container);
            }
        });
    });
}

/*---------------------------------------------------------------------------*/

function reloadSupportRegister(
        button
){
    let page = button.closest(".page");
    getXhr({
        action : "getSupportRegister",
    }).then((html) => {
        page.innerHTML = html;
        let containerList = page.querySelectorAll(".supportTable");
        for(let container of containerList){
            swapDisableAll(container);
            allInputAreaAutosize(container);
        }
    });
}

/*---------------------------------------------------------------------------*/

function supportRegisterMenuSelect(
        el
){
    let menuBlock = el.closest(".menuBlock");
    let itemList = Array.from(menuBlock.querySelectorAll(".menuItem"));
    for(let menuItem of itemList){
        menuItem.classList.remove("menuItem_selected");
    }
    el.classList.add("menuItem_selected");
   supportRegisterTableSelect(el);
}

/*---------------------------------------------------------------------------*/

function supportRegisterTableSelect(
        el
){
    let page = el.closest(".page");
    let menuItem = page.querySelector(".menuItem.menuItem_selected");
    let containerList = Array.from(page.querySelectorAll(".tableContainer"));
    for(let container of containerList){
        container.classList.add("hidden");
    }
    let id = menuItem.id;
    page.querySelector("#" + id + "_table").classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function supportRegisterFilter(
        button
){
    let page = button.closest(".page");
    let tbodyList = Array.from(page.querySelectorAll(".tableContainer:not(#phonebook_table) tbody"));
    let text = getVarFromContainer(page,"filterText");
    let reg = new RegExp(text,"i");
    let i = 0;
    let hide = function(row){
        row.classList.add("hidden");
    };
    let show = function(row,i){
        row.classList.remove("hidden");
        row.classList.remove("odd");
        row.classList.remove("even");
        if (i % 2 == 0){
            row.classList.add("odd");
        }
        else{
            row.classList.add("even");
        }
    };
    let filter = function(tbody){
        let i = 0;
        let rows = Array.from(tbody.rows);
        for(let row of rows){
            if (Array.from(row.cells).length == 9){
                continue
            }
            let searchText = getVarFromContainer(row,"searchText");
            if (!searchText.match(reg)){
                hide(row);
                let index = row.rowIndex;
                while((rows.length > index)&& (Array.from(rows[index].cells).length == 9)){
                    hide(rows[index]);
                    index++;
                }
            }
            else{
                show(row,i);
                let index = row.rowIndex;
                while((rows.length > index)&& (Array.from(rows[index].cells).length == 9)){
                    show(rows[index],i);
                    index++;
                }
                i++;
            }
        }
    };
    for(let tbody of tbodyList){
        filter(tbody);
    }
} 

/*---------------------------------------------------------------------------*/

function saveAgreementUserComment(
        element,
        containerSelector = ".page"
){
    let container = element.closest(containerSelector);
    let tableKey = getVarFromContainer(container,"reg_tableKey");
    let tableType = getVarFromContainer(container,"reg_tableType");
    let dnum = getVarFromContainer(container,"reg_dnum");
    let registerComment = getVarFromContainer(container,"registerComment");
    let year = getVarFromContainer(container,"reg_year");
    let month = getVarFromContainer(container,"reg_month");
    let params = {
        action : "saveAgreementUserComment",
        tableType :tableType,
        dnum : dnum,
        tableKey : tableKey,
        registerComment : registerComment,
        year : year,
        month : month
    };
    getXhr(params).then((html) => {
        tableCellEditClose(element,true);
//        debug(html);
    });
} 

/*---------------------------------------------------------------------------*/

DocumentRegister = {
    showRegister(){
        getXhr({
            action : "getDocumentRegister"
        }).then((html) => {
            newPage(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    reloadRegister(
            button
    ){
        let page = button.closest(".page");
        getXhr({
            action : "getDocumentRegister"
        }).then((html) => {
            page.innerHTML = html;
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    placementButtonClick(
            button
    ){
        let get = function(
                id
        ){
            return getVarFromContainer(button,id);
        };
        let params = {
            action : "saveDocumentRegisterPlacement",
            dnum : get("doc_dnum"),
            docType : get("doc_docType"),
            docId : get("doc_docId"),
            clientId : get("doc_clientId")
        };
        let docId = get("doc_docId");
        getXhr({
            action : "getDocumentRegisterPlacementForm",
            docType : params["docType"]
        }).then((html) => {
            fMsgBlock("",html,90,350,"docPlacementForm");
            let msg = document.getElementById("docPlacementForm");
            let buttonList = Array.from(msg.querySelectorAll(".placementButton"));
            for(let el of buttonList){
                el.addEventListener("click",function(){
                    params["docPlacement"] = getVarFromContainer(el,"doc_docPlacement");
                    getXhr(params).then((html) => {
                        let buttonText = button.querySelector("#button_text");
                        let elText = el.querySelector("#button_text");
                        buttonText.style.backgroundColor = elText.style.backgroundColor;
                        buttonText.style.color = elText.style.color;
                        buttonText.textContent = elText.textContent;
                        let value = getVarFromContainer(el,"doc_docPlacement");
                        setVarToContainer(button,"doc_docPlacement",value);
                        fUnMsgBlock("docPlacementForm");
//                        debug(html);
                    });
                });
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    disconnectPlacementButtonClick(
            button
    ){
        let get = function(
                id
        ){
            return getVarFromContainer(button,id);
        };
        
        getXhr({
            action : "getDocumentRegisterPlacementForm",
            docType : get("doc_docType")
        }).then((html) => {
            fMsgBlock("",html,90,350,"docPlacementForm");
            let msg = document.getElementById("docPlacementForm");
            let buttonList = Array.from(msg.querySelectorAll(".placementButton"));
            for(let el of buttonList){
                el.addEventListener("click",function(){
                    let value = getVarFromContainer(el,"doc_docPlacement");
                    setVarToContainer(button,"disconnectRegisterDocPlacement",value);
                    let element = button.querySelector("#disconnectRegisterDocPlacement");
                    let callback = function(){
                        let buttonText = button.querySelector("#button_text");
                        let elText = el.querySelector("#button_text");
                        buttonText.style.backgroundColor = elText.style.backgroundColor;
                        buttonText.textContent = elText.textContent;
                        let value = getVarFromContainer(el,"doc_docPlacement");
                        setVarToContainer(button,"doc_docPlacement",value);
                        fUnMsgBlock("docPlacementForm");
                    };
                    saveClient(element,false,callback,null,"placementButton");
                });
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    showClientCard(
            button,
            containerSelector = "tr"
    ){
        let container = button.closest(containerSelector);
        let clientId = getVarFromContainer(container,"clientId");
        showClientCard(clientId);
    },
    
    /*---------------------------------------------------------------------------*/
    
    /*---------------------------------------------------------------------------*/
};

/*---------------------------------------------------------------------------*/

















