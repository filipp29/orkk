const Salary = {
    
    showSalaryTable(){
        getXhr({
            action : "getSalaryTable"
        }).then((html) => {
            newPage(html,true,function(page){
                let editBlockList = Array.from(page.querySelectorAll(".editBlock"));
                for(let el of editBlockList){
                    swapDisableAll(el);
                    allInputAreaAutosize(el);
                }
            });
            
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    reloadSalaryTable(
            button
    ){
        let page = button.closest(".page");
        let month = getVarFromContainer(page,"updateMonth");
        let year = getVarFromContainer(page,"updateYear");
        getXhr({
            action : "getSalaryTable",
            month : month,
            year : year
        }).then((html) => {
            page.innerHTML = html;
            let editBlockList = Array.from(page.querySelectorAll(".editBlock"));
            for(let el of editBlockList){
                swapDisableAll(el);
                allInputAreaAutosize(el);
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    getDocList(
            container
    ){
        let paramBlockList = Array.from(container.querySelectorAll(".docParamBlock"));
        let result = {};
        for(let paramBlock of paramBlockList){
            let docId = getVarFromContainer(paramBlock,"docId");
            let payManager = getVarFromContainer(paramBlock,"manager");
            let valueBlock = paramBlock.querySelector(".docParamValue");;
            let paramId = valueBlock.id;
            let paramValue = getValue(valueBlock);
            if (result[payManager] == undefined){
                result[payManager] = {};
            }
            if (result[payManager][docId] == undefined){
                result[payManager][docId] = {};
            }
            result[payManager][docId][paramId] = paramValue;
        }
        return result;
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveDoc(
            button,
            containerSelector,
            cellClose = false
    ){
        let container = button.closest(containerSelector);
        let page = button.closest(".page");
        let month = getVarFromContainer(page,"month");
        let year = getVarFromContainer(page,"year");
        let result = this.getDocList(container);
        getXhr({
            action : "saveSalaryDoc",
            salary : JSON.stringify(result),
            month : month,
            year : year
        }).then((html) => {
            if (cellClose){
                tableCellEditClose(button,true);
//                debug(html);
            }
        });
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    getParamList(
            container
    ){  
        let paramBlockList = Array.from(container.querySelectorAll(".salaryParamBlock"));
        let result = {};
        for(let paramBlock of paramBlockList){
            let manager = getVarFromContainer(paramBlock,"manager");
            let valueBlock = paramBlock.querySelector(".salaryParamValue");;
            let paramId = valueBlock.id;
            let paramValue = getValue(valueBlock);
            if (result[manager] == undefined){
                result[manager] = {};
            }
            result[manager][paramId] = paramValue;
        }
        return result;
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveParams(
            button,
            containerSelector,
            cellClose = false
    ){
        let container = button.closest(containerSelector);
        let page = button.closest(".page");
        let month = getVarFromContainer(page,"month");
        let year = getVarFromContainer(page,"year");
        let result = this.getParamList(container);
        getXhr({
            action : "saveSalaryParams",
            paramList : JSON.stringify(result),
            month : month,
            year : year
        }).then((html) => {
            if (cellClose){
                tableCellEditClose(button,true);
//                debug(html);
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    getBonusList(
            container
    ){
        let paramBlockList = Array.from(container.querySelectorAll(".salaryBonusBlock"));
        let result = {};
        for(let paramBlock of paramBlockList){
            let manager = getVarFromContainer(paramBlock,"manager");
            let key = getVarFromContainer(paramBlock,"bonusKey");
            let value = getVarFromContainer(paramBlock,"bonusValue");
            if (result[manager] == undefined){
                result[manager] = {};
            }
            result[manager][key] = value;
        }
        return result;
    },
    
    /*---------------------------------------------------------------------------*/
    
    getBonusSumList(
            container
    ){
        let elemList = Array.from(container.querySelectorAll(".bonusSumBlock"));
        let result = {};
        for(let el of elemList){
            let bonusSum = getVarFromContainer(el,"bonusSum");
            let manager = getVarFromContainer(el,"manager");
            result[manager] = bonusSum;
        }
        return result;
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveBonus(
            button,
            containerSelector,
            cellClose = false
    ){
        let container = button.closest(containerSelector);
        let page = button.closest(".page");
        let month = getVarFromContainer(page,"month");
        let year = getVarFromContainer(page,"year");
        let result = this.getBonusList(container);
        getXhr({
            action : "saveSalaryBonus",
            bonusList : JSON.stringify(result),
            month : month,
            year : year
        }).then((html) => {
            if (cellClose){
                tableCellEditClose(button,true);
//                debug(html);
            }
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveAll(
            button,
            containerSelector
    ){
        let container = button.closest(containerSelector);
        let page = button.closest(".page");
        let month = getVarFromContainer(page,"month");
        let year = getVarFromContainer(page,"year");
        let paramList = this.getParamList(container);
        let bonusList = this.getBonusList(container);
        let bonusSumList = this.getBonusSumList(container);
        let docBlockList = Array.from(container.querySelectorAll(".docParamList"));
        let docList = {};
        for(let block of docBlockList){
            let varList = Array.from(block.querySelectorAll(".var"));
            let buf = {};
            for(let v of varList){
                buf[v.id] = getValue(v);
            }
            if (docList[buf["payManager"]] == undefined){
                docList[buf["payManager"]] = {};
            }
            docList[buf["payManager"]][buf["docId"]] = buf;
        }
//        for(let profile in paramList){
//            paramList[profile]["closed"] = "1";
//        }
        let params = {
            docList : docList,
            paramList : paramList,
            bonusList : bonusList,
            bonusSumList : bonusSumList
        };
        console.log(params);
        let paramsData = JSON.stringify(params);
        console.log(paramsData);
        postXhr({
            action : "saveSalaryAll",
            year : year,
            month : month,
            params : JSON.stringify(params)
        }).then((html) => {
//            debug(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    removeManager(
            button
    ){
        let tr = button.closest("tr");
        let tbody = button.closest("tbody");
        let docParamList = tr.querySelector(".docParamList");
        let dnum = getVarFromContainer(docParamList,"dnum");
        let docId = getVarFromContainer(docParamList,"docId");
        getXhr({
            action : "changePayManager",
            dnum : dnum,
            docId : docId,
            payManager : ""
        }).then((html) => {
            tbody.removeChild(tr);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    addForPayment(
            button
    ){
        let tr = button.closest("tr");
        let tbody = button.closest("tbody");
        let docParamList = tr.querySelector(".docParamList");
        let dnum = getVarFromContainer(docParamList,"dnum");
        let docId = getVarFromContainer(docParamList,"docId");
        getXhr({
            action : "changeForPayment",
            dnum : dnum,
            docId : docId,
            forPayment : "1"
        }).then((html) => {
            tbody.removeChild(tr);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    showChangeManagerForm(
            button
    ){
        let tr = button.closest("tr");
        let tbody = button.closest("tbody");
        let docParamList = tr.querySelector(".docParamList");
        let dnum = getVarFromContainer(docParamList,"dnum");
        let docId = getVarFromContainer(docParamList,"docId");
        getXhr({
            action : "changePayManagerForm",
            dnum : dnum,
            docId : docId
        }).then((html) => {
            fMsgBlock("",html,180,400,"changePayManager");
            let msg = document.getElementById("changePayManager");
            msg.row = tr;
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    changeAttract(
            button,
            containerSelector = "tr"
    ){
        let container = button.closest(containerSelector);
        let docParamBlock = container.querySelector(".docParamList");
        let dnum = getVarFromContainer(docParamBlock,"dnum");
        let docId = getVarFromContainer(docParamBlock,"docId");
        let attractType = getVarFromContainer(container,"attractValue");
        getXhr({
            action : "changeDocumentAttractType",
            dnum : dnum,
            docId : docId,
            attractType : attractType
        }).then((html) => {
            tableCellEditClose(button,true);
            debug(html);
        });
        
    }
    
    /*---------------------------------------------------------------------------*/
    
};
const Timesheet = {
    show(
            year = "",
            month = ""
    ){
        getXhr({
            action : "getTimesheetPage",
            year : year,
            month : month
        }).then((html) => {
            newPage(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    reload(
            button
    ){
        let container = button.closest(".updateBlock");
        let month = getVarFromContainer(container,"updateMonth");
        let year = getVarFromContainer(container,"updateYear");
        let page = button.closest(".page");
        getXhr({
            action : "getTimesheetPage",
            year : year,
            month : month
        }).then((html) => {
            page.innerHTML = html;
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    changeCalendarDay(
            button
    ){
        td = button.closest("td");
        let id = td.id;
        let type = getVarFromContainer(td,"type");
        let year = getVarFromContainer(td,"year");
        let month = getVarFromContainer(td,"month");
        let day = getVarFromContainer(td,"day");
        let newType = (type == "h") ? "w" : "h";
        setVarToContainer(td,"type",newType);
        let tbody = button.closest("tbody");
        let callback = function(){
            for(let row of tbody.rows){
                if (id == ""){
                    continue;
                }
                let cell = row.querySelector("#"+id);
                if (cell){
                    cell.style.backgroundColor = (newType == "h") ? "#FFFF00" : "";
                }
            }
        };
        let data = {
            year : year,
            month : month,
            day : day,
            type : newType
        };
        getXhr({
            action : "saveTimesheetCalendarDay",
            data : JSON.stringify(data)
        }).then((html) => {
            callback();
//            debug(html);
        });
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    showProfileDayForm(
            button
    ){
        let container = button.closest("td");
        let profile = getVarFromContainer(container,"profile");
        let year = getVarFromContainer(container,"year");
        let month = getVarFromContainer(container,"month");
        let day = getVarFromContainer(container,"day");
        let data = {
            year : year,
            month : month,
            day : day,
            profile : profile
        };
        getXhr({
            action : "getTimesheetProfileDayForm",
            data : JSON.stringify(data)
        }).then((html) => {
            fMsgBlock("",html,130,500,"profileDayForm");
            let msgBack = document.getElementById("profileDayForm");
            msgBack.caller = button;
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    saveProfileDay(
            button
    ){
        let container = button.closest(".valueBlock");
        let keyList = [
            "value",
            "profile",
            "year",
            "month",
            "day"
        ];
        let data = {};
        for(let key of keyList){
            data[key] = getVarFromContainer(container,key);
        }
        if (data["value"] === ""){
            data["value"] = "0";
        }
        getXhr({
            action : "saveTimesheetProfileDay",
            data : JSON.stringify(data)
        }).then((html) => {
            let msgBack = button.closest(".msgBack");
            let caller = msgBack.caller;
            if (caller){
                let valueBlock = caller.querySelector(".valueBlock");
                valueBlock.innerHTML = data["value"];
            }
            fUnMsgBlock("profileDayForm");
//            debug(html);
        });
    }
    
    /*---------------------------------------------------------------------------*/
    
};













