const CsvTable = {
    download(
            container
    ){
        let tableList = Array.from(container.querySelectorAll(".csvTable"));
        let result = {};
        for(let el of tableList){
            let thead = el.querySelector("thead");
            let tbody = el. querySelector("tbody");
            let tableName = el.getAttribute("data_csvname");
            let rows = [];
            if (thead) {
                rowList = Array.from(thead.rows);
                for(let row of rowList){
                    let bufRow = [];
                    let cellList = Array.from(row.cells);
                    for(let cell of cellList){
                        bufRow.push(getVarFromContainer(cell,"csvData"));
                    }
                    rows.push(bufRow);
                }
            }
            if (tbody){
                rowList = Array.from(tbody.rows);
                for(let row of rowList){
                    let bufRow = [];
                    let cellList = Array.from(row.cells);
                    for(let cell of cellList){
                        bufRow.push(getVarFromContainer(cell,"csvData"));
                    }
                    rows.push(bufRow);
                }
            }
            result[tableName] = rows;
        }
        saveFileFromRouter({
            action : "downloadCsvFromData",
            data : JSON.stringify(result)
        });
    }
    
    /*---------------------------------------------------------------------------*/
    
};