function clientListFilter(
        button
){

    let getObj = function(){
        return{
            count : 0,
            sum : 0
        };
    };
    let active = {
        connected : new getObj(),
        connectedFl : new getObj(),
        connectedGu : new getObj()
    };
    let inactive = {
        disconnected : new getObj(),
        disconnectedFl : new getObj(),
        disconnectedGu : new getObj(),
        block : new getObj()
    };
    let activeDistrict = {
        kzbi : new getObj(),
        ksk : new getObj(),
        center : new getObj()
    };
    let inactiveDistrict = {
        kzbi : new getObj(),
        ksk : new getObj(),
        center : new getObj()
    };
    let districtKeyList = {
        "КЖБИ" : "kzbi",
        "КСК" : "ksk",
        "Центр" : "center"
    };
    let allClientsCount = 0;
    let hotStatusList = {
        activeClients : [
            "Подключен"
        ],
        inactiveClients : [
            "Отключен"
//            "Нет тех. возможности",
//            "Не соответствует ТС",
//            "Приостановлен",
//            "Отказ НЦ",
//            "Переоформлен"
        ],
        coldClients : [
            "Ведутся переговоры",
            "Ожидает подключение",
            "Обход/Диалог",
            "Повторное предложение"
        ],
        reofferClients : [
            "Повторное предложение"
        ]
    };
    let hotDistrictList = {
        kzbi : "КЖБИ",
        ksk : "КСК",
        center : "Центр"
    };
    let filterParams = {};
    let page = button.closest(".page");
    let tbody = page.querySelector("tbody");
    let trList = Array.from(tbody.rows);
    
    /*---------------------------------------------------------------------------*/
    
    
    let hotStatusParams = Array.from(page.querySelectorAll(".filterParamHotStatus"));
    for(let el of hotStatusParams){
        if (getValue(el)){
            let id = el.getAttribute("id");
            for(let buf of hotStatusList[id]){
                if (filterParams["hotStatus"] == undefined){
                    filterParams["hotStatus"] = [];
                }
                filterParams["hotStatus"].push(buf);
            }
        }
    }
    
    let hotProfileParams = Array.from(page.querySelectorAll(".filterParamHotProfile"));
    for(let el of hotProfileParams){
        if (getValue(el)){
            let profile = el.getAttribute("id").split("_")[1];
            if (filterParams["hotProfile"] == undefined){
                filterParams["hotProfile"] = [];
            }
            filterParams["hotProfile"].push(profile);
        }
    }
    let hotDistrictParams = Array.from(page.querySelectorAll(".filterParamHotDistrict"));
    for(let el of hotDistrictParams){
        if (getValue(el)){
            let district = el.getAttribute("id").split("_")[1];
            if (filterParams["hotDistrict"] == undefined){
                filterParams["hotDistrict"] = [];
            }
            filterParams["hotDistrict"].push(hotDistrictList[district]);
        }
    }
    
    let paramList = Array.from(page.querySelectorAll(".filterParam"));
    
    for(let el of paramList){
        let key = el.getAttribute("filter_param");
        let value = el.getAttribute("filter_value");
        if (filterParams[key] == undefined){
            filterParams[key] = [];
        }
        filterParams[key].push(value);
    }
    /*---------------------------------------------------------------------------*/
    
    let checkParam = function(
            param,
            type,
            values
    ){
        let reg;
        let result = false;
        for(let value of values){
            switch (type) {
                case "equal":
                    if (param == value){
                        result = true;
                    }
                    break;
                    
                case "notEqual":
                    if(param == value){
                        return false;
                    }
                    else{
                        result = true;
                    }
                    break;
                    
                case "greater":
                    if (Number(param) < Number(value)){
                        return false;
                    }
                    else{
                        result = true;
                    }
                    break;
                    
                case "less":
                    if (Number(param) > Number(value)){
                        return false;
                    }
                    else{
                        result = true;
                    }
                    break
                    
                case "contains":
                    reg = new RegExp(value,"i");
                    if (String(param).match(reg)){
                        result = true;
                    }
                    break
                    
                case "notContains":
                    reg = new RegExp(value,"i");
                    if (String(param).match(reg)){
                        return false;
                    }
                    else{
                        result = true;
                    }
                    break

                default:

                    break;
            }
        }
        return result;
    };
    
    /*---------------------------------------------------------------------------*/
    let hotKeys = [
        "hotStatus",
        "hotProfile",
        "hotDistrict"
    ];
    
    let i = 0;
    let averageLife = 0;
    if (Object.keys(filterParams).length > 0){
        for(let tr of trList){
            tr.classList.remove("even");
            tr.classList.remove("odd");
            let params = tr.filterParams;
            
            let visible = true;
            if (filterParams["hotStatus"] != undefined){
                if (!filterParams["hotStatus"].includes(params["clientStatus"])){
                    visible = false;
                }
            }
            if (filterParams["hotDistrict"] != undefined){
                if (!filterParams["hotDistrict"].includes(params["district"])){
                    visible = false;
                }
            }
            if (filterParams["hotProfile"] != undefined){
                if (!filterParams["hotProfile"].includes(params["manager"])){
                    visible = false;
                }
            }
            for(let key in filterParams){
                if (hotKeys.includes(key)){
                    continue;
                }
                let buf = key.split("_");
                if (!checkParam(params[buf[0]],buf[1],filterParams[key])){
                    visible = false;
                }
            }
            if (visible){
                i++;
                let clas = (i % 2 !== 0) ? "even" : "odd";
                tr.classList.add(clas);
                tr.classList.remove("hidden");
                averageLife += Number(params["averageLife"]);
                if (params["clientStatus"] == "Подключен"){
                    let type = params["clientType"];
                    if (type == "ФЛ"){
                        active.connectedFl.count++
                        active.connectedFl.sum += Number(params["amount"]);
                    }
                    else if (type == "ГУ"){
                        active.connectedGu.count++;
                        active.connectedGu.sum += Number(params["amount"]);
                    }
                    else{
                        active.connected.count++;
                        active.connected.sum += Number(params["amount"]);
                    }
                    let district = districtKeyList[params["district"]];
                    if (district){
                        activeDistrict[district].count++;
                        activeDistrict[district].sum += Number(params["amount"]);
                    }
                }
                if (params["clientStatus"] == "Отключен"){
                    let type = params["clientType"];
                    if (type == "ФЛ"){
                        inactive.disconnectedFl.count++;
                        inactive.disconnectedFl.sum += Number(params["amount"]);
                    }
                    else if (type == "ГУ"){
                        inactive.disconnectedGu.count++;
                        inactive.disconnectedGu.sum += Number(params["amount"]);
                    }
                    else{
                        inactive.disconnected.count++;
                        inactive.disconnected.sum += Number(params["amount"]);
                    }
                    let district = districtKeyList[params["district"]];
                    if (district){
                        inactiveDistrict[district].count++;
                        inactiveDistrict[district].sum += Number(params["amount"]);
                    }
                }
                if (params["clientStatus"] == "Приостановлен"){
                    inactive.block.count++;
                    inactive.block.sum += Number(params["amount"]);
                    let district = districtKeyList[params["district"]];
                    if (district){
                        inactiveDistrict[district].count++;
                        inactiveDistrict[district].sum += Number(params["amount"]);
                    }
                }
                
                allClientsCount++;
            }
            else{
                tr.classList.add("hidden");
            }
        }
    }
    else{
        for(let tr of trList){
            let params = tr.filterParams;
            averageLife += Number(params["averageLife"]);
            tr.classList.remove("even");
            tr.classList.remove("odd");
            i++;
            let clas = (i % 2 !== 0) ? "even" : "odd";
            tr.classList.add(clas);
            tr.classList.remove("hidden");
            if (params["clientStatus"] == "Подключен"){
                let type = params["clientType"];
                if (type == "ФЛ"){
                    active.connectedFl.count++
                    active.connectedFl.sum += Number(params["amount"]);
                }
                else if (type == "ГУ"){
                    active.connectedGu.count++;
                    active.connectedGu.sum += Number(params["amount"]);
                }
                else{
                    active.connected.count++;
                    active.connected.sum += Number(params["amount"]);
                }
                let district = districtKeyList[params["district"]];
                if (district){
                    activeDistrict[district].count++;
                    activeDistrict[district].sum += Number(params["amount"]);
                }
            }
            if (params["clientStatus"] == "Отключен"){
                let type = params["clientType"];
                if (type == "ФЛ"){
                    inactive.disconnectedFl.count++;
                    inactive.disconnectedFl.sum += Number(params["amount"]);
                }
                else if (type == "ГУ"){
                    inactive.disconnectedGu.count++;
                    inactive.disconnectedGu.sum += Number(params["amount"]);
                }
                else{
                    inactive.disconnected.count++;
                    inactive.disconnected.sum += Number(params["amount"]);
                }
                let district = districtKeyList[params["district"]];
                if (district){
                    inactiveDistrict[district].count++;
                    inactiveDistrict[district].sum += Number(params["amount"]);
                }
            }
            
            if (params["clientStatus"] == "Приостановлен"){
                inactive.block.count++;
                inactive.block.sum += Number(params["amount"]);
                let district = districtKeyList[params["district"]];
                if (district){
                    inactiveDistrict[district].count++;
                    inactiveDistrict[district].sum += Number(params["amount"]);
                }
            }
            
            allClientsCount++;
        }
    }
    let set = function(id,value){
        page.querySelector("#" + id).textContent = value;
    };
    let activeCount = 0;
    let activeSum = 0;
    for(let key in active){
        activeCount += active[key].count;
        activeSum += active[key].sum;
        set(key + "Count",active[key].count);
        set(key + "Sum",active[key].sum);
    }
    set("activeCount",activeCount);
    set("activeSum",activeSum);
    
    let inactiveCount = 0;
    let inactiveSum = 0;
    for(let key in inactive){
        inactiveCount += inactive[key].count;
        inactiveSum += inactive[key].sum;
        set(key + "Count",inactive[key].count);
        set(key + "Sum",inactive[key].sum);
    }
    set("inactiveCount",inactiveCount);
    set("inactiveSum",inactiveSum);
    set("allClientsCount",allClientsCount);
    for(let key in activeDistrict){
        set("connected_" + key + "Count",activeDistrict[key].count);
        set("connected_" + key + "Sum",activeDistrict[key].sum);
    }
    for(let key in inactiveDistrict){
        set("disconnected_" + key + "Count",inactiveDistrict[key].count);
        set("disconnected_" + key + "Sum",inactiveDistrict[key].sum);
    }
    set("averageLife",averageLife);
}



/*---------------------------------------------------------------------------*/

function showAddClientListFilterItemForm(
        el,
        select = false
){
    let param;
    let page;
    if (select){
        param = getValue(el);
    }
    else{
        param = "name";
        page = el.closest(".page");
    }
    
    let acceptFunction;
    getXhr({
        action : "getClientListFilterForm",
        param : param
    }).then((html) => {
        if (select){
            let msgBlockData = document.getElementById("filterForm").querySelector(".fmsgBlock_data");
            acceptFunction = msgBlockData.acceptFunction;
            fUnMsgBlock("filterForm");
        }
        else{
            acceptFunction = function(
                    param,
                    type,
                    value,
                    text
            ){
                let container = page.querySelector("#paramsContainer");
                let block = document.createElement("div");
                block.textContent = text;
                block.style.display = "flex";
                block.style.height = "24px";
                block.style.borderRadius = "8px";
                block.style.margin = "10px 0px 0px 10px";
                block.style.fontSize = "12px";
                block.style.justifyContent = "center";
                block.style.alignItems = "center";
                block.style.backgroundColor = "var(--modColor_dark)";
                block.style.padding = "0px 8px";
                block.style.cursor = "pointer";
                block.style.color = "var(--modBGColor)";
                block.classList.add("filterParam");
                block.setAttribute("filter_param",param + "_" + type);
                block.setAttribute("filter_value",value);
                block.addEventListener("click",function(){
                    container.removeChild(block);
                });
                container.appendChild(block);
                fUnMsgBlock("filterForm");
            };
        }
        fMsgBlock("Выберите параметр",html,235,500,"filterForm");
        let msgBlockData = document.getElementById("filterForm").querySelector(".fmsgBlock_data");
        msgBlockData.acceptFunction = acceptFunction;
//        debug(html);
    });
}


/*---------------------------------------------------------------------------*/

function filterFormAcceptClick(
        button
){
    let container = button.closest(".fmsgBlock_data");
    let param = container.querySelector("#filterFormParam");
    let type = container.querySelector("#filterFormType");
    let value = container.querySelector("#filterFormValue");
    let text = `${getShow(param)} ${getShow(type)} ${getShow(value)}`;
    container.acceptFunction(getValue(param),getValue(type),getValue(value),text);
}

/*---------------------------------------------------------------------------*/

function clientListSort(
        button
){
    let page = button.closest(".page");
    let index = button.closest("th").cellIndex;
    let tbody = page.querySelector("tbody");
    let sortedIndex = tbody.sortedIndex;
    let sortFunc;
    if ((sortedIndex != undefined) && (sortedIndex == index) && (tbody.sortDir)){
        sortFunc = function(a,b){
            let valueA = a.cells[index].textContent;
            let valueB = b.cells[index].textContent;
            if (valueA < valueB){
                return 1;
            }
            else if(valueB < valueA){
                return -1;
            }
            else{
                return 0;
            }
        };
        tbody.sortDir = false;
    }
    else{
        sortFunc = function(a,b){
            let valueA = a.cells[index].textContent.toLowerCase();
            let valueB = b.cells[index].textContent.toLowerCase();
            if (valueA < valueB){
                return -1;
            }
            else if(valueB < valueA){
                return 1;
            }
            else{
                return 0;
            }
        };
        tbody.sortDir = true;
    }
    let rowList = Array.from(tbody.rows);
    rowList.sort(sortFunc);
    tbody.sortedIndex = index;
    tbody.innerHTML = "";
    let i = 0;
    for(let el of rowList){
        el.classList.remove("even");
        el.classList.remove("odd");
        if (!el.classList.contains("hidden")){
            i++;
        }
        let clas = (i % 2 !== 0) ? "even" : "odd";
        el.classList.add(clas);
        tbody.appendChild(el);
    }
} 

/*---------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------*/


function showClientList(){
    let name = "Client";
    getXhr({
        action : "getClientListPage"
    }).then((html) => {
        newPage(html,true,function(container){
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



