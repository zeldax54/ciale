$(document).ready(function () {
    function disableBack() {
        window.history.forward()


    }

     window.onload = disableBack();
    window.onpageshow = function (evt) {
        if (evt.persisted)
            disableBack()
    }

    $('[data-toggle="tooltip"]').tooltip();

    /*
     * Custom Select
     */
    $('.tag-select2').selectpicker({
        liveSearch: true
    });

    $('input[type="date"]').datetimepicker({
        'format': 'YYYY-MM-DD',
        locale: 'es',
        icons: {
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right'
        }
    });
    
    $('input[name^="gema_gemabundle_activo[estOps]"]').datetimepicker({
       'format': false,
        locale: 'es',
        sideBySide: true,
        icons: {
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            
            time: 'glyphicon glyphicon-time',
            date: 'glyphicon glyphicon-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            today: 'glyphicon glyphicon-screenshot',
            clear: 'glyphicon glyphicon-trash',
            close: 'glyphicon glyphicon-remove'
        }
    });

    /*
     * Tag Select
     */
    $('.tag-select').chosen({
        disable_search: false,
        no_results_text: "No hay coincidencias",
        placeholder_text_multiple: "Por favor, seleccione...",
        placeholder_text_single: "Por favor, seleccione...",
        width: '100%',
        allow_single_deselect: true,
        search_contains: true
    });

    /*
     * Date Time Picker
     */

    //Date Time Picker
    if ($('.date-time-picker')[0]) {
        $('.date-time-picker').datetimepicker();
    }

    //Time
    if ($('.time-picker')[0]) {
        $('.time-picker').datetimepicker({
            format: 'LT'
        });
    }

    //Date
    if ($('.date-picker')[0]) {
        $('.date-picker').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    }
    /*
     * Custom Scrollbars
     */
    function scrollbar(className, color, cursorWidth) {
        $(className).niceScroll({
            cursorcolor: color,
            cursorborder: 0,
            cursorborderradius: 0,
            cursorwidth: cursorWidth,
            bouncescroll: true,
            mousescrollstep: 100
        });
    }

    //Scrollbar for HTML(not mobile) but not for login page
    if ($('html')) {
        if (!$('.login_gema')[0]) {
            scrollbar('html', 'rgba(0,0,0,0.3)', '5px');
        }

        //Scrollbar Tables
        if ($('.table-responsive')[0]) {
            scrollbar('.table-responsive', 'rgba(0,0,0,0.5)', '5px');
        }

        //Scrill bar for Chosen
        if ($('.chosen-results')[0]) {
            scrollbar('.chosen-results', 'rgba(0,0,0,0.5)', '5px');
        }

        //Scrollbar for rest
        if ($('.c-overflow')[0]) {
            scrollbar('.c-overflow', 'rgba(0,0,0,0.5)', '5px');
        }
    }







});

function Exportatabla(options,tabla){
    var defaults = {
        separator: ',',
        ignoreColumn: [],
        tableName:'yourTableName',
        type:'csv',
        pdfFontSize:14,
        pdfLeftMargin:20,
        escape:'true',
        htmlContent:'false',
        consoleLog:'false'
    };

    var options = $.extend(defaults, options);
    var el = $('#'+tabla);

    if(defaults.type == 'csv' || defaults.type == 'txt'){

        // Header
        var tdData ="";
        $(el).find('thead').find('tr').each(function() {
            tdData += "\n";
            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        tdData += '"' + parseString($(this)) + '"' + defaults.separator;
                    }
                }

            });
            tdData = $.trim(tdData);
            tdData = $.trim(tdData).substring(0, tdData.length -1);
        });

        // Row vs Column
        $(el).find('tbody').find('tr').each(function() {
            tdData += "\n";
            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        tdData += '"'+ parseString($(this)) + '"'+ defaults.separator;
                    }
                }
            });
            //tdData = $.trim(tdData);
            tdData = $.trim(tdData).substring(0, tdData.length -1);
        });

        //output
        if(defaults.consoleLog == 'true'){
            console.log(tdData);
        }
        var base64data = "base64," + $.base64.encode(tdData);
        window.open('data:application/'+defaults.type+';filename=exportData;' + base64data);
    }else if(defaults.type == 'sql'){

        // Header
        var tdData ="INSERT INTO `"+defaults.tableName+"` (";
        $(el).find('thead').find('tr').each(function() {

            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        tdData += '`' + parseString($(this)) + '`,' ;
                    }
                }

            });
            tdData = $.trim(tdData);
            tdData = $.trim(tdData).substring(0, tdData.length -1);
        });
        tdData += ") VALUES ";
        // Row vs Column
        $(el).find('tbody').find('tr').each(function() {
            tdData += "(";
            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        tdData += '"'+ parseString($(this)) + '",';
                    }
                }
            });

            tdData = $.trim(tdData).substring(0, tdData.length -1);
            tdData += "),";
        });
        tdData = $.trim(tdData).substring(0, tdData.length -1);
        tdData += ";";

        //output
        //console.log(tdData);

        if(defaults.consoleLog == 'true'){
            console.log(tdData);
        }

        var base64data = "base64," + $.base64.encode(tdData);
        window.open('data:application/sql;filename=exportData;' + base64data);


    }else if(defaults.type == 'json'){

        var jsonHeaderArray = [];
        $(el).find('thead').find('tr').each(function() {
            var tdData ="";
            var jsonArrayTd = [];

            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        jsonArrayTd.push(parseString($(this)));
                    }
                }
            });
            jsonHeaderArray.push(jsonArrayTd);

        });

        var jsonArray = [];
        $(el).find('tbody').find('tr').each(function() {
            var tdData ="";
            var jsonArrayTd = [];

            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        jsonArrayTd.push(parseString($(this)));
                    }
                }
            });
            jsonArray.push(jsonArrayTd);

        });

        var jsonExportArray =[];
        jsonExportArray.push({header:jsonHeaderArray,data:jsonArray});

        //Return as JSON
        //console.log(JSON.stringify(jsonExportArray));

        //Return as Array
        //console.log(jsonExportArray);
        if(defaults.consoleLog == 'true'){
            console.log(JSON.stringify(jsonExportArray));
        }
        var base64data = "base64," + encode(JSON.stringify(jsonExportArray));
        window.open('data:application/json;filename=exportData;' + base64data);
    }else if(defaults.type == 'xml'){

        var xml = '<?xml version="1.0" encoding="utf-8"?>';
        xml += '<tabledata><fields>';

        // Header
        $(el).find('thead').find('tr').each(function() {
            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        xml += "<field>" + parseString($(this)) + "</field>";
                    }
                }
            });
        });
        xml += '</fields><data>';

        // Row Vs Column
        var rowCount=1;
        $(el).find('tbody').find('tr').each(function() {
            xml += '<row id="'+rowCount+'">';
            var colCount=0;
            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        xml += "<column-"+colCount+">"+parseString($(this))+"</column-"+colCount+">";
                    }
                }
                colCount++;
            });
            rowCount++;
            xml += '</row>';
        });
        xml += '</data></tabledata>'

        if(defaults.consoleLog == 'true'){
            console.log(xml);
        }

        var base64data = "base64," + $.base64.encode(xml);
        window.open('data:application/xml;filename=exportData;' + base64data);

    }

    else if(defaults.type == 'excel' || defaults.type == 'doc'|| defaults.type == 'powerpoint'  ){
        //console.log($(this).html());
        var excel="<table>";
        // Header
        $(el).find('thead').find('tr').each(function() {
            excel += "<tr>";
            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        excel += "<td>" + parseString($(this))+ "</td>";
                    }
                }
            });
            excel += '</tr>';

        });


        // Row Vs Column
        var rowCount=1;
        $(el).find('tbody').find('tr').each(function() {
            excel += "<tr>";
            var colCount=0;
            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        excel += "<td>"+parseString($(this))+"</td>";
                    }
                }
                colCount++;
            });
            rowCount++;
            excel += '</tr>';
        });
        excel += '</table>'

        if(defaults.consoleLog == 'true'){
            console.log(excel);
        }

        var excelFile = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:"+defaults.type+"' xmlns='http://www.w3.org/TR/REC-html40'>";
        excelFile += "<head>";
        excelFile += "<!--[if gte mso 9]>";
        excelFile += "<xml>";
        excelFile += "<x:ExcelWorkbook>";
        excelFile += "<x:ExcelWorksheets>";
        excelFile += "<x:ExcelWorksheet>";
        excelFile += "<x:Name>";
        excelFile += "{worksheet}";
        excelFile += "</x:Name>";
        excelFile += "<x:WorksheetOptions>";
        excelFile += "<x:DisplayGridlines/>";
        excelFile += "</x:WorksheetOptions>";
        excelFile += "</x:ExcelWorksheet>";
        excelFile += "</x:ExcelWorksheets>";
        excelFile += "</x:ExcelWorkbook>";
        excelFile += "</xml>";
        excelFile += "<![endif]-->";
        excelFile += "</head>";
        excelFile += "<body>";
        excelFile += excel;
        excelFile += "</body>";
        excelFile += "</html>";

        var base64data = "base64," + encode(excelFile);
        window.open('data:application/vnd.ms-'+defaults.type+';filename=exportData.doc;' + base64data);

    }

    else if(defaults.type == 'png'){
        html2canvas($(el), {
            onrendered: function(canvas) {
                var img = canvas.toDataURL("image/png");
                window.open(img);


            }
        });
    }else if(defaults.type == 'pdf'){

        var doc = new jsPDF('p','pt', 'a4', true);
        doc.setFontSize(defaults.pdfFontSize);

        // Header
        var startColPosition=defaults.pdfLeftMargin;
        $(el).find('thead').find('tr').each(function() {
            $(this).filter(':visible').find('th').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        var colPosition = startColPosition+ (index * 50);
                        doc.text(colPosition,20, parseString($(this)));
                    }
                }
            });
        });


        // Row Vs Column
        var startRowPosition = 20; var page =1;var rowPosition=0;
        $(el).find('tbody').find('tr').each(function(index,data) {
            rowCalc = index+1;

            if (rowCalc % 26 == 0){
                doc.addPage();
                page++;
                startRowPosition=startRowPosition+10;
            }
            rowPosition=(startRowPosition + (rowCalc * 10)) - ((page -1) * 280);

            $(this).filter(':visible').find('td').each(function(index,data) {
                if ($(this).css('display') != 'none'){
                    if(defaults.ignoreColumn.indexOf(index) == -1){
                        var colPosition = startColPosition+ (index * 50);
                        doc.text(colPosition,rowPosition, parseString($(this)));
                    }
                }

            });

        });

        // Output as Data URI
        doc.output('datauri');

    }


    function parseString(data){

        if(defaults.htmlContent == 'true'){
            content_data = data.html().trim();
        }else{
            content_data = data.text().trim();
        }

        if(defaults.escape == 'true'){
            content_data = escape(content_data);
        }



        return content_data;
    }


}


function encode (input) {
   var _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var output = "";
    var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
    var i = 0;

    input = _utf8_encode(input);

    while (i < input.length) {

        chr1 = input.charCodeAt(i++);
        chr2 = input.charCodeAt(i++);
        chr3 = input.charCodeAt(i++);

        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        enc4 = chr3 & 63;

        if (isNaN(chr2)) {
            enc3 = enc4 = 64;
        } else if (isNaN(chr3)) {
            enc4 = 64;
        }

        output = output +
            _keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
            _keyStr.charAt(enc3) + _keyStr.charAt(enc4);

    }

    return output;
}


 function _utf8_encode (string) {
    string = string.replace(/\r\n/g,"\n");
    var utftext = "";

    for (var n = 0; n < string.length; n++) {

        var c = string.charCodeAt(n);

        if (c < 128) {
            utftext += String.fromCharCode(c);
        }
        else if((c > 127) && (c < 2048)) {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        }
        else {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }

    return utftext;
}

// private method for UTF-8 decoding
  function _utf8_decode (utftext) {
    var string = "";
    var i = 0;
    var c = c1 = c2 = 0;

    while ( i < utftext.length ) {

        c = utftext.charCodeAt(i);

        if (c < 128) {
            string += String.fromCharCode(c);
            i++;
        }
        else if((c > 191) && (c < 224)) {
            c2 = utftext.charCodeAt(i+1);
            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            i += 2;
        }
        else {
            c2 = utftext.charCodeAt(i+1);
            c3 = utftext.charCodeAt(i+2);
            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }

    }

    return string;
}