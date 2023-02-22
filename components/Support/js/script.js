function showMonthSupportTable(){
    let date = new Date();
    getXhr({
        action : "getMonthSupportTable",
        year : date.getFullYear(),
        month : date.getMonth() + 1
    }).then((html) => {
        newPage(html,true,function(page){
            let container = page.querySelector(".supportTable");
            swapDisableAll(container);
            allInputAreaAutosize(container);
        });
    });
    
}

/*---------------------------------------------------------------------------*/

function downloadMonthSupportTable(
        button
){
    let page = button.closest(".page");
    let month = getVarFromContainer(page,"month");
    let year = getVarFromContainer(page,"year");
    saveFileFromRouter({
        action : "downloadMonthSupportTable",
        year : year,
        month : month
    });
}

/*---------------------------------------------------------------------------*/

function reloadMonthSupportTable(
        button
){
    let page = button.closest(".page");
    let month = getVarFromContainer(page,"month");
    let year = getVarFromContainer(page,"year");
    getXhr({
        action : "getMonthSupportTable",
        year : year,
        month : month
    }).then((html) => {
        page.innerHTML = html;
        swapDisableAll(page.querySelector(".supportTable"));
        allInputAreaAutosize(page);
    });
    
}

/*---------------------------------------------------------------------------*/

function supportRowEdit(
        button
){
    let row = button.closest("tr");
    swapDisableAll(row);
    row.querySelector(".editButton").classList.add("hidden");
    row.querySelector(".acceptBlock").classList.remove("hidden");
}

/*---------------------------------------------------------------------------*/

function saveAccountSupport(
        button,
        containerSelector = ".page"
){
    let container = button.closest(containerSelector);
    let dnum = getVarFromContainer(container,"tr_dnum");
    let rate = getVarFromContainer(container,"rate");
    let comment = getVarFromContainer(container,"comment");
    let support = getVarFromContainer(container,"tr_support");
    let callDate = getVarFromContainer(container,"callDate");
    let params = {
        action : "saveAccountSupport",
        dnum : dnum,
        rate : rate,
        comment : comment,
        name : support,
        callDate : callDate
    };
    getXhr(params).then((html) => {
        reloadMonthSupportTable(button);
    });
}

/*---------------------------------------------------------------------------*/

function textKeyPress(
        event,
){
    let el = event.target;
    let key = event.key;
}

/*---------------------------------------------------------------------------*/