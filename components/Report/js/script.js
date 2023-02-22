const Report = {
    
    showMain(){
        getXhr({
            action : "getReportPage"
        }).then((html) => {
            newPage(html);
        });
    },
    
    /*---------------------------------------------------------------------------*/
    
    SupportReport : {
        /*---------------------------------------------------------------------------*/
            
        showPage(
                button
        ){
            getXhr({
                action : "getSupportReportPage",
            }).then((html) => {
                let page = button.closest(".page");
                page.innerHTML = html;
            });
        },

        /*---------------------------------------------------------------------------*/

        showTable(
                button
        ){
            let params = Report.SupportReport.getParams(button);
            if (params === null){
                return;
            }
            getXhr({
                action : "getSupportReportTable",
                params : JSON.stringify(params)
            }).then((html) => {
                let page = button.closest(".page");
                let tableContainer = page.querySelector("#reportTable");
                tableContainer.innerHTML = html;
            });
        },
        
        /*---------------------------------------------------------------------------*/
        
        getParams(
                button
        ){
            let result = {
                first : {
                    year : "",
                    month : ""
                },
                second : {
                    year : "",
                    month : ""
                },
                type : ""
            };
            let page = button.closest(".page");
            let periodRadio = page.querySelector("#period_type");
            result.type = getValue(periodRadio);
            let periodContainer = periodRadio.closest(".paramsFrame");
            for(let period in result){
                for(let type in result[period]){
                    result[period][type] = getVarFromContainer(periodContainer,`${period}_${type}`);
                }
            }
            return result;
        },
        
        /*---------------------------------------------------------------------------*/
        
        changeParamsType(
                button
        ){
            let container = button.closest(".paramsFrame");
            let forHideList = Array.from(container.querySelectorAll(".forHide"));
            let buttonValue = button.getAttribute("data_value");
            for(let el of forHideList){
                if (buttonValue == "compare"){
                    el.classList.toggle("hidden");
                }
                else{
                    el.classList.add("hidden");
                }
            }
        }
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    ClientReport : {
        
        /*---------------------------------------------------------------------------*/
        
        Connect : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "connectReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getConnectReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        District : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "districtReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getDistrictReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        ConnectSum : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "connectSumReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getConnectSumReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        Gu : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "guReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getGuReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        Client : {
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "clientReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getClientReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        Amount : {
            showPage(
                    button
            ){
                getXhr({
                    action : "getClientReportPage",
                    reportType : "amountReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ClientReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getAmountReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        getParams(
                button
        ){
            let result = {
                period : {},
                city : [],
                manager : []
            };
            let page = button.closest(".page");
            let periodRadio = page.querySelector("#period_type");
            let periodType = getValue(periodRadio);
            let periodContainer = periodRadio.closest(".paramsFrame");
            let start = getVarFromContainer(periodContainer,"firstPeriod_start");
            let end = getVarFromContainer(periodContainer,"firstPeriod_end");
            if (!start || !end){
                errorMessage("Введите дату");
                return null;
            }
            result.period["first"] = {
                start : start,
                end : end
            };
            if (periodType == "compare"){
                let start = getVarFromContainer(periodContainer,"secondPeriod_start");
                let end = getVarFromContainer(periodContainer,"secondPeriod_end");
                if (!start || !end){
                    errorMessage("Введите дату");
                    return null;
                }
                result.period["second"] = {
                    start : start,
                    end : end
                };
            }
            let cityRadio = page.querySelector("#city_type");
            let cityType = getValue(cityRadio);
            if (cityType == "compare"){
                let cityContainer = cityRadio.closest(".paramsFrame");
                let cityList = Array.from(cityContainer.querySelectorAll(".city_checkbox"));
                for(let el of cityList){
                    if (getValue(el)){
                        result.city.push(el.textContent.trim());
                    }
                }
                if (result.city.length == 0){
                    errorMessage("Выберите город");
                    return null;
                }
            }
            let managerRadio = page.querySelector("#manager_type");
            let managerType = getValue(managerRadio);
            if (managerType == "compare"){
                let managerContainer = managerRadio.closest(".paramsFrame");
                let managerList = Array.from(managerContainer.querySelectorAll(".manager_checkbox"));
                for(let el of managerList){
                    if (getValue(el)){
                        result.manager.push(el.id.trim());
                    }
                }
                if (result.manager.length == 0){
                    errorMessage("Выберите менеджера");
                    return null;
                }
            }
            return result;
        },
        
        /*---------------------------------------------------------------------------*/
        
        changeParamsType(
                button
        ){
            let container = button.closest(".paramsFrame");
            let forHideList = Array.from(container.querySelectorAll(".forHide"));
            let buttonValue = button.getAttribute("data_value");
            for(let el of forHideList){
                if (buttonValue == "compare"){
                    el.classList.toggle("hidden");
                }
                else{
                    el.classList.add("hidden");
                }
            }
        }
        
        /*---------------------------------------------------------------------------*/
        
    },
    
    /*---------------------------------------------------------------------------*/
    
    ContactReport : {
        
        /*---------------------------------------------------------------------------*/
        
        Email : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getContactReportPage",
                    reportType : "emailReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ContactReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getEmailReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        Contact : {
            
            /*---------------------------------------------------------------------------*/
            
            showPage(
                    button
            ){
                getXhr({
                    action : "getContactReportPage",
                    reportType : "contactReport"
                }).then((html) => {
                    let page = button.closest(".page");
                    page.innerHTML = html;
                });
            },

            /*---------------------------------------------------------------------------*/

            showTable(
                    button
            ){
                let params = Report.ContactReport.getParams(button);
                if (params === null){
                    return;
                }
                getXhr({
                    action : "getContactReportTable",
                    params : JSON.stringify(params)
                }).then((html) => {
                    let page = button.closest(".page");
                    let tableContainer = page.querySelector("#reportTable");
                    tableContainer.innerHTML = html;
                });
            }
        },
        
        /*---------------------------------------------------------------------------*/
        
        getParams(
                button
        ){
            let result = {
                city : [],
                status : []
            };
            let page = button.closest(".page");
            let getParam = function(
                    id,
                    name
            ){
                let resultList = [];
                let radio = page.querySelector(`#${id}_type`)
                let type = getValue(radio);
                if (type == "compare"){
                    let container = radio.closest(".paramsFrame");
                    let list = Array.from(container.querySelectorAll(`.${id}_checkbox`));
                    for(let el of list){
                        if (getValue(el)){
                            resultList.push(el.textContent.trim());
                        }
                    }
                    if (resultList.length == 0){
                        errorMessage("Выберите " + name);
                        return null;
                    }
                }
                return resultList;
            };
            result.city = getParam("city","город");
            result.status = getParam("status","статус");
            result.emailType = getParam("emailType","тип email");
            result.clientType = getParam("clientType","тип клиента");
            for(let key in result){
                if (result[key] === null){
                    return null;
                }
            }
            return result;
        },
        
        /*---------------------------------------------------------------------------*/
        
        changeParamsType(
                button
        ){
            let container = button.closest(".paramsFrame");
            let forHideList = Array.from(container.querySelectorAll(".forHide"));
            let buttonValue = button.getAttribute("data_value");
            for(let el of forHideList){
                if (buttonValue == "compare"){
                    el.classList.toggle("hidden");
                }
                else{
                    el.classList.add("hidden");
                }
            }
        }
    }
    
    /*---------------------------------------------------------------------------*/
    
};