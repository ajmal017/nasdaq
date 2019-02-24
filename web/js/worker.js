$( document ).ready(function () {

    const dto = {data:[],open:[],close:[]};

    let submit = $(".worker .form .submit")[0];
    let symbol = $(".worker .form .symbol")[0];
    let start = $(".worker .form .start")[0];
    let end = $(".worker .form .end")[0];
    let email = $(".worker .form .email")[0];
    let error = $(".worker .error")[0];
    let table = $(".worker .table")[0];


    submit.addEventListener('click', function (e) {
        e.preventDefault();

        let linechart = document.getElementById('linechart');
        linechart.innerHTML = '';

        try{
            isSymbol(symbol.value);
            isDate(start.value, 'Start');
            isDate(end.value, 'End');
            isEmail(email.value);
            comparisonDate();
        }catch (e) {
            error.innerText = e.message;
            table.innerHTML = '';
            return;
        }
        error.innerText = '';

        $.ajax({
            url: "/?r=api/data",
            data: {
                symbol:symbol.value.trim(),
                start: start.value.trim(),
                end: end.value.trim(),
                email: email.value.trim()
            },
            type: 'GET',
            success: function(data){
                table.innerHTML = '';
                if(typeof data['error'] !== "undefined"){
                    error.innerText = data['error'];
                    return;
                };
                createTable(data);
                chart();
            },
        });

    });

    function createTable(data){
        let newtable = document.createElement('table');
        let thead = document.createElement('thead');
        let trhead = document.createElement('tr');

        let header = ['Date', 'Open', 'High', 'Low', 'Close', 'Volume', 'Ex-Dividend', 'Split Ratio', 'Adj. Open', 'Adj. High', 'Adj. Low', 'Adj. Close', 'Adj. Volume'];
        for (let i=0; i<header.length; i++){
            let td = document.createElement('td');
            td.innerText = header[i];
            trhead.appendChild(td);
        };

        thead.appendChild(trhead);
        newtable.appendChild(thead);

        for(let i=0; i < data.length; i++ ){
            dto.data[i] = [];
            let tr = document.createElement('tr');
            for (let ii=0; ii< data[i].length; ii++){
                if(ii === 0 || ii === 1 || ii === 4){
                    if(ii === 1){dto.open.push(data[i][ii])};
                    if(ii === 4){dto.close.push(data[i][ii])};
                    dto.data[i].push(data[i][ii]);
                };
                let td = document.createElement('td');
                td.innerText = data[i][ii];
                tr.appendChild(td);
            }
            newtable.appendChild(tr);
        };
        table.appendChild(newtable);
    }

    function isDate(date, text = '') {
        if(date === 'undefined' || date === ''){
            throw new Error(text + ' date empty');
        };
        if(date.match( /\d{4}-\d{2}-\d{2}/ ) == null){
            throw new Error(text + ' date incorrect');
        }
    }

    function comparisonDate(){
        if( (new Date(start.value.trim())) > (new Date(end.value.trim())) ){
            throw new Error('Start date can not be more than the end date. Front validate.');
        }
    }

    function isEmail(email) {
        if(email.match( /(\w|\d)+@(\w|\d)+\.(\w|\d)+/ ) == null ){
            throw new Error('Mail incorrect');
        }
    }

    function isSymbol(symbol) {
        if(symbol === 'undefined' || symbol === ''){
            throw new Error('Symbol empty');
        }
    }

    function chart(){

        var data = {
            labels: [],
            series: [
                dto.open,
                dto.close
            ]
        };

        var options = {
            showPoint: false,
            lineSmooth: false,
            axisX: {
                showGrid: false,
                showLabel: false
            },
            axisY: {
                offset: 60,
                labelInterpolationFnc: function(value) {
                    return value;
                }
            }
        };
        new Chartist.Line('#linechart', data, options);
    }

});