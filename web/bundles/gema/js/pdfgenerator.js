function SaveToDisk(fileURL, fileName) {

    try{

        // for non-IE
        if (!window.ActiveXObject) {
            var save = document.createElement('a');
            save.href = fileURL;
            save.target = '_blank';
            save.download = fileName || 'unknown';

            var evt = new MouseEvent('click', {
                'view': window,
                'bubbles': true,
                'cancelable': false
            });
            save.dispatchEvent(evt);

            (window.URL || window.webkitURL).revokeObjectURL(save.href);
        }

        // for IE < 11
        else if (!!window.ActiveXObject && document.execCommand) {
            var _window = window.open(fileURL, '_blank');
            _window.document.close();
            _window.document.execCommand('SaveAs', true, fileName || fileURL)
            _window.close();
        }
    }
    catch(e){
        console.log('error');
        console.log(e);

        window.open(fileURL, '_blank');

    }

}
$('.imprimircatalogo').click(function(){


    var capasImg;
    var imgIntrodURL;
    var imgIntrodName;
    var torosInfo;
    var imgListaPrec;
    var imgMsjintrod;
    var imgpreload;




    var familyId=$(this).attr('familyId');
    var table=$('table.'+familyId).not('.DTFC_Cloned').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    var arratid=[];
    // Iterate over all selected checkboxes
    $.each(rows_selected, function(index, rowId) {
        arratid.push(rowId);
    });
    if(rows_selected.length===0){

        vex.dialog.alert({ unsafeMessage:'No ha seleccionado ningún toro'

            , className: 'vex-theme-wireframe' ,
            overlayClassName: 'success',
            contentClassName: 'bordernaranjaclass',
            closeClassName: 'closebleclass'
        })
    }else {
        $.ajax({
            type: 'POST',
            data:{torosid:arratid},
            url: Routing.generate('gema_catalogobaseinfo'),
            success: function (data) {

                capasImg=data[0].capas;
                imgIntrodURL=data[0].imgIntrod;
                imgIntrodName=data[0].imgIntrodName;
                torosInfo=data[0].torosInfo;
                imgListaPrec=data[0].imglistaprec;
                imgMsjintrod=data[0].imgmsjintrod;
                imgpreload=data[0].imgpreload;

                var source = {};


                function PrimerDialog(datos) {

                    var capascheck='checked="checked"';
                    var listaprecioscheck='checked="checked"';
                    var msjintroduccioncheck='checked="checked"';
                    var tablacontenidoscheck ='checked="checked"';

                    if(datos!=undefined)
                    {
                        if(datos.capas===undefined)
                            capascheck=null;
                        if(datos.listaprecios===undefined)
                            listaprecioscheck=null;
                        if(datos.msjintroduccion===undefined)
                            msjintroduccioncheck=null;
                        if(datos.tablacontenidos===undefined)
                            tablacontenidoscheck=null;

                    }


                    vex.dialog.confirm({


                        contentClassName: 'bordernaranjaclass',
                        closeClassName: 'closebleclass',
                        //  className: 'primmodalflag' ,

                        message: '',


                        input: [

                            '<div class="row"> ' +
                            '<div class="col-md-6">' +
                            '<h4 class="headercatalog">Crear PDF</h4>' +
                            '<div><label><input class="checkheader" type="checkbox" '+capascheck+' id="capas" name="capas" ><b class="catalotext">Tapas</b> </label></div>' +
                            '<div><label><input class="checkheader" type="checkbox" '+listaprecioscheck+' id="listaprecios" name="listaprecios"><b class="catalotext">Lista de Precios</b> </label></div>' +
                            '<div><label><input class="checkheader" type="checkbox" '+msjintroduccioncheck+' id="msjintroduccion" name="msjintroduccion"><b class="catalotext">Mensaje de Intruducción</b> </label></div>' +
                            '<div><label><input class="checkheader" type="checkbox" '+tablacontenidoscheck+' id="tablacontenidos" name="tablacontenidos"><b class="catalotext">Tabla de contenidos</b> </label></div>' +

                            '</div>' +
                            '<div class="col-md-6">' +
                            '<h4 class="headercatalog">Imprimir Toros</h4>' +
                            '<div><label><input class="checkheader" onclick="return false;" type="checkbox" checked="checked" id="capas" ><b class="catalotext">Toros Individuales</b> </label></div>' +

                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-md-6"><div><img class="imagenheadercatalog" src="' + data[0].miniaturacrarpdf + '"></div></div>' +
                            '<div class="col-md-6"><div><img class="imagenheadercatalog" src="' + data[0].miniaturaimprimirtoros + '"></div></div>' +
                            '</div>'


                        ].join(''),
                        showCloseButton: true,
                        buttons: [

                            $.extend({}, vex.dialog.buttons.YES, {
                                text: 'Imprimir Toros',
                                className: 'vex-dialog-button-primary vex-dialog-button vex-first imprimirtorosbutton'
                            }),

                            $.extend({}, vex.dialog.buttons.YES, {
                                text: 'Crear PDF',
                                className: 'vex-dialog-button-primary vex-dialog-button vex-first crearpdfbutton'
                            }),


                        ],



                        callback: function (data) {



                            if ($('#clicked').val().search(/imprimirtorosbutton/) != -1) {
                                var vexwaiting = vex.dialog.alert({unsafeMessage: '<div style="text-align: center"><img src="'+imgpreload+'" width="400" height="300">'+'<br><b>Procesando.Espere...</b><div>',
                                    contentClassName: 'bordernaranjaclassMasAncho',
                                    className:'vex-theme-os'})

                                var arra = [];
                                $.each(rows_selected, function (index, rowId) {
                                    arra.push(rowId);
                                });

                                var url = Routing.generate('pdf_generate');

                                $.ajax({
                                    type: 'POST',
                                    data: {ids: arra, filename: 'toros'},
                                    url: url,
                                    success: function (data) {
                                        vex.close(vexwaiting)
                                        if (data[0] == 1) {
                                            //window.open(data[1], '_blank');
                                            SaveToDisk(data[1],data[2]);
                                            //var pom = document.createElement('a');
                                            //pom.setAttribute('href', 'data:application/octet-stream,' + encodeURIComponent(data[1]));
                                            //pom.setAttribute('download', data[2]);
                                            //pom.setAttribute('target', '_blank');
                                            //pom.style.display = 'none';
                                            //document.body.appendChild(pom);
                                            //pom.click();
                                            //document.body.removeChild(pom);

                                        } else {
                                            vex.dialog.alert({
                                                unsafeMessage: '<b>Error generando PDF</b>',
                                                className: 'vex-theme-wireframe',
                                                overlayClassName: 'success',
                                                contentClassName: 'bordernaranjaclass',
                                                closeClassName: 'closebleclass'
                                            })
                                        }

                                    },
                                    error: function (req, stat, err) {
                                        vex.close(vexwaiting)
                                        console.log(err);
                                    }
                                });
                                $('#clicked').val('');

                                return;
                            }

                            if ($('#clicked').val().search(/crearpdfbutton/) != -1) {



                                function SegundoDialogo(saveddatos){

                                    source = {
                                        'capas': data.capas,
                                        'listaprecios': data.listaprecios,
                                        'msjintroduccion': data.msjintroduccion,
                                        'tablacontenidos': data.tablacontenidos,
                                        'capaName': ''
                                    };
                                    source['imgIntrodURL']=imgIntrodURL;
                                    source['imgIntrodName']=imgIntrodName;
                                    source['torosInfo']=torosInfo;
                                    source['imgListaPrecURL']=imgListaPrec;
                                    source['imgMsjintrod']=imgMsjintrod;

                                    var datatitulo='';
                                    var datasubtitulo='';
                                    var datacontacto='';
                                    var datanombre='';
                                    var datadireccion='';
                                    var datatelefono='';
                                    var dataemail='';
                                    var datatitulopdf='';
                                    var selectedcapa='';

                                    if(saveddatos!==undefined){
                                        datatitulo=saveddatos.titulo==undefined?'':saveddatos.titulo;

                                        datasubtitulo=saveddatos.subtitulo==undefined?'':saveddatos.subtitulo;
                                        datacontacto=saveddatos.contacto==undefined?'':saveddatos.contacto;
                                        datanombre=saveddatos.nombre==undefined?'':saveddatos.nombre;
                                        datadireccion=saveddatos.direccion==undefined?'':saveddatos.direccion;
                                        datatelefono=saveddatos.telefono==undefined?'':saveddatos.telefono;
                                        dataemail=saveddatos.email==undefined?'':saveddatos.email;
                                        datatitulopdf=saveddatos.titulopdf==undefined?'':saveddatos.titulopdf;
                                        selectedcapa=saveddatos.capaName==undefined?'':saveddatos.capaName;
                                    }

                                    var capasHtml = '';
                                    if (source.capas === 'on') {
                                        capasHtml = '<b>Escoja su Tapa</b><br><br>' +
                                            '<div class="row" style="overflow-y: auto;height: 183px">';
                                        capasImg.forEach(function (capa) {


                                            var isselected='';
                                            if(selectedcapa!=undefined && capa[1].replace('_small','')==selectedcapa)
                                                isselected='checked';
                                            capasHtml += '<div class="col-md-4" style="text-align: center;">' +
                                                '<img src="' + capa[0] + '" class="imagencapasminuaturas" id="'+capa[2]+'" name="'+capa[1]+'"/><br>' +
                                                '<input  type="radio" '+isselected+' required name="capasradio" value="' + capa[1] + '">' +
                                                '</div>'

                                        });
                                        capasHtml += '</div>';


                                    }

                                    vex.dialog.confirm({
                                        contentClassName: 'bordernaranjaclass',
                                        closeClassName: 'closebleclass',
                                        message: '',
                                        input: [
                                            capasHtml + '<br>' +
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Título</b></div><div  class="col-md-8"><input class="inputtext" type="text"  name="titulo" value="'+datatitulo+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Subtítulo</b></div><div  class="col-md-8"><input class="inputtext" type="text"  name="subtitulo" value="'+datasubtitulo+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Contacto</b></div><div class="col-md-8"><input class="inputtext" type="text"  name="contacto" value="'+datacontacto+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Nombre</b></div><div class="col-md-8"><input class="inputtext" type="text"   name="nombre" value="'+datanombre+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Dirección</b></div><div class="col-md-8"><input class="inputtext" type="text"  name="direccion" value="'+datadireccion+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Teléfono</b></div><div class="col-md-8"><input class="inputtext" type="text"  name="telefono" value="'+datatelefono+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Email</b></div><div class="col-md-8"><input class="inputtext" type="email"  name="email" value="'+dataemail+'"></div></div>'+
                                            '<div class="divaligncenter row"><div class="col-md-4"><b class="bdatapdf">Nombre del PDF</b></div><div class="col-md-8"><input class="inputtext"  type="text" name="titulopdf" value="'+datatitulopdf+'"></div></div>'
                                        ].join(''),
                                        showCloseButton: true,
                                        buttons: [

                                            $.extend({}, vex.dialog.buttons.YES, {
                                                text: 'Continuar',
                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first segundocontinuarbutton'
                                            }),

                                            $.extend({}, vex.dialog.buttons.YES, {
                                                text: 'Volver',
                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first segundoVolverbutton '
                                            }),


                                        ],

                                        callback: function (data) {
                                            var clickwhere=$('#clicked');

                                            source['capaName'] = data.capasradio==undefined?undefined: data.capasradio.replace('_small', '');

                                            if(source['capaName']!=undefined)
                                                source['capaUrl']=$('#'+source['capaName'].replace('pdfresources/tapas/','').replace('.','').replace('_','').replace('\\','').replace('/','').replace('(','').replace(')','')).attr('src');
                                            source['titulo']=data.titulo==undefined?'':data.titulo;
                                            source['subtitulo']=data.subtitulo==undefined?'':data.subtitulo;
                                            source['contacto']=data.contacto==undefined?'':data.contacto;
                                            source['nombre']=data.nombre==undefined?'':data.nombre;
                                            source['direccion']=data.direccion==undefined?'':data.direccion;
                                            source['telefono']=data.telefono==undefined?'':data.telefono;
                                            source['email']=data.email==undefined?'':data.email;
                                            source['titulopdf']=data.titulopdf==undefined?'':data.titulopdf;


                                            if (clickwhere.val().search(/segundoVolverbutton/) != -1) {

                                                PrimerDialog(source);
                                                $('#clicked').val('');
                                                return;
                                            }
                                            if (clickwhere.val().search(/segundocontinuarbutton/) != -1) {
                                                console.log(source['capaUrl']);
                                                function PreviaUno(){
                                                    var previaCapa='';
                                                    if(source.capas=='on'){
                                                        previaCapa='<div class="col-md-6">' +
                                                            '<img class="imagenheadercatalogprevia1" src="'+source.capaUrl+'" style="width: 101% !important;">'+
                                                            '</div>'

                                                    }

                                                    console.log(source);

                                                    //var brContacto=source.contacto==""?'':'<br>';
                                                    //var brNombre=source.nombre==""?'':'<br>';
                                                    //var brDireccion=source.direccion==""?'':'<br>';
                                                    //var brTelefono=source.telefono==""?'':'<br>';
                                                    //var brEmail=source.email==""?'':'<br>';

                                                    var brContacto='<br>';
                                                    var brNombre='<br>';
                                                    var brDireccion='<br>';
                                                    var brTelefono='<br>';
                                                    var brEmail='<br>';



                                                    vex.dialog.confirm({
                                                        contentClassName: 'bordernaranjaclassMasAncho',
                                                        closeClassName: 'closebleclass',
                                                        message: '',

                                                        input: [
                                                            '<b class="previaheader">Vista Previa</b><br>'+
                                                            '<div class="row">'+
                                                            previaCapa+
                                                            '<div class="col-md-6" style="height: 320px !important;background-image: url'+"("+''+source["imgIntrodURL"].replace('\\','/')+');background-size: cover !important;background-position: bottom !important; ">' +
                                                            '<span class="previatitulo">'+source.titulo+'</span><br>'+
                                                            '<span class="previasubtitulo">'+source.subtitulo+'</span><br>'+
                                                            '<div style="height: 116px;"></div>'+
                                                            '<span class="previasubtitulo previatexto">'+source.contacto+'</span>'+brContacto+
                                                            '<span class="previasubtitulo previatexto">'+source.nombre+'</span>'+brNombre+
                                                            '<span class="previasubtitulo previatexto">'+source.direccion+'</span>'+brDireccion+
                                                            '<span class="previasubtitulo previatexto">'+source.telefono+'</span>'+brTelefono+
                                                            '<span class="previasubtitulo previatexto" style="position:absolute;;margin-top: 9px !important;">'+source.email+'</span>'+brEmail+
                                                            '</div>'+
                                                            '</div>'
                                                        ].join(''),
                                                        showCloseButton: true,
                                                        buttons: [

                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                text: 'Continuar',
                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first tercerocontinuarbutton'
                                                            }),

                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                text: 'Volver',
                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first terceroVolverbutton '
                                                            }),

                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                text: 'Intentar Nuevamente',
                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first terceroNuevamentebutton '
                                                            }),

                                                        ],

                                                        callback: function (data) {
                                                            var clickwhere=$('#clicked');

                                                            if (clickwhere.val().search(/terceroVolverbutton/) != -1) {
                                                                SegundoDialogo(source);
                                                                $('#clicked').val('');
                                                                return;
                                                            }
                                                            if (clickwhere.val().search(/tercerocontinuarbutton/) != -1) {



                                                                function ListaPrecioDialogo(){

                                                                    var tablalistaprec='<div style="overflow: auto;height: 450px;text-align: center">' +
                                                                        '<table class="table-responsive" style="margin-left: 23% !important;margin-top: 2% !important;width: 64%;">' +
                                                                        '<tr><thead><th style="display: none">ToroID</th><th>Apodo</th><th>Precio</th></thead></tr>' +
                                                                        '<tbody>';

                                                                    if(source.listtoroprocesada!=undefined && source.listtoroprocesada!=null){

                                                                        for(var j=0;j<source.listtoroprocesada.length;j++){

                                                                            tablalistaprec+='<tr>' +
                                                                                '<td style="display: none"><input type="text" name="idtoros" value="'+source.listtoroprocesada[j][0]+'"/></td>' +
                                                                                '<td><input type="text" value="'+source.listtoroprocesada[j][1]+'" name="apodos" hidden/>'+source.listtoroprocesada[j][1]+'</td>' +
                                                                                '<td><input step="any" name="preciotoros" class="toroprecio" style="border-bottom: 1px solid #B24E2A;" value="'+source.listtoroprocesada[j][2]+'" type="number"></td>';

                                                                        }

                                                                    }
                                                                    else{
                                                                        source.torosInfo.forEach(function(toro){
                                                                            var id=toro[0];
                                                                            var apodo=toro[1];

                                                                            tablalistaprec+='<tr>' +
                                                                                '<td style="display: none"><input type="text" name="idtoros" value="'+id+'"/></td>' +
                                                                                '<td><input type="text" value="'+apodo+'" name="apodos" hidden/>'+apodo+'</td>' +
                                                                                '<td><input step="any" name="preciotoros" required value="0" class="toroprecio" style="border-bottom: 1px solid #B24E2A;" type="number"></td>';
                                                                        });
                                                                    }

                                                                    tablalistaprec+='</tbody></table></div>';


                                                                    vex.dialog.confirm({
                                                                        contentClassName: 'bordernaranjaclassMasAncho',
                                                                        closeClassName: 'closebleclass',
                                                                        message: '',
                                                                        input: [
                                                                            '<b class="previaheader">Lista de precios</b><br>'+
                                                                            tablalistaprec

                                                                        ].join(''),
                                                                        showCloseButton: true,
                                                                        buttons: [

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Continuar',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first listaprecioscontinuarbutton'
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Volver',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first listapreciosVolverbutton '
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Intentar Nuevamente',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first listapreciosNuevamentebutton '
                                                                            }),

                                                                        ],

                                                                        callback: function (data) {

                                                                            if (clickwhere.val().search(/listapreciosNuevamentebutton/) != -1) {
                                                                                PrimerDialog(source);

                                                                            }

                                                                            if (clickwhere.val().search(/listapreciosVolverbutton/) != -1) {
                                                                                PreviaUno();

                                                                            }

                                                                            if (clickwhere.val().search(/listaprecioscontinuarbutton/) != -1) {
                                                                                var listtoroprocesada=[];
                                                                                listaprecioprevia='<div style="overflow-y: auto;overflow-x: hidden;height: 319px !important;"><table class="table-responsive" style="width: 100% !important;"><tbody>';

                                                                                if(Array.isArray(data.idtoros)){

                                                                                    for(var i=0;i<data.idtoros.length;i++){
                                                                                        var temp=[];
                                                                                        temp.push(data.idtoros[i]);
                                                                                        temp.push(data.apodos[i]);
                                                                                        temp.push(data.preciotoros[i]);
                                                                                        listtoroprocesada.push(
                                                                                            temp
                                                                                        );
                                                                                        listaprecioprevia+='<tr style="text-align: center"><td>'+data.apodos[i]+'</td><td style="float: right"><strong>$'+data.preciotoros[i]+'</strong></td></tr>'
                                                                                    }

                                                                                }
                                                                                else{
                                                                                    var temp=[];
                                                                                    temp.push(data.idtoros);
                                                                                    temp.push(data.apodos);
                                                                                    temp.push(data.preciotoros);
                                                                                    listtoroprocesada.push(
                                                                                        temp
                                                                                    );

                                                                                    listaprecioprevia+='<tr style="text-align: center"><td>'+data.apodos+'</td><td style="float: right"><strong>$'+data.preciotoros+'</strong></td></tr>'

                                                                                }

                                                                                listaprecioprevia+='</tbody></table></div>';
                                                                                source['listtoroprocesada']=listtoroprocesada;
                                                                                ListaprecioPrevia();
                                                                            }
                                                                        }

                                                                    });
                                                                }

                                                                function ListaprecioPrevia(){
                                                                    vex.dialog.confirm({
                                                                        contentClassName: 'bordernaranjaclassMasAncholistatoroprevia',
                                                                        closeClassName: 'closebleclass',
                                                                        message: '',
                                                                        input: [
                                                                            '<div style="text-align: center ;height:450px !important;width: 295px !important;margin-left: 21%;background-image: url'+"("+''+source["imgListaPrecURL"].replace('\\','/')+');background-size: cover !important;background-position: bottom !important; ">' +
                                                                            '<b class="previaheader">Lista de Precios</b><br>'+
                                                                            listaprecioprevia+

                                                                            '</div>'

                                                                        ].join(''),
                                                                        showCloseButton: true,
                                                                        buttons: [

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Continuar',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previalistaprecioscontinuarbutton'
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Volver',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previalistapreciosVolverbutton '
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Intentar Nuevamente',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previalistapreciosNuevamentebutton '
                                                                            }),

                                                                        ],

                                                                        callback: function (data) {

                                                                            if (clickwhere.val().search(/previalistapreciosNuevamentebutton/) != -1) {
                                                                                PrimerDialog(source);

                                                                            }

                                                                            if (clickwhere.val().search(/previalistapreciosVolverbutton/) != -1) {

                                                                                ListaPrecioDialogo();


                                                                            }

                                                                            if (clickwhere.val().search(/previalistaprecioscontinuarbutton/) != -1) {
                                                                                if(source.msjintroduccion=="on")
                                                                                    MensajeIntrodDialogo();
                                                                                else{
                                                                                    FinDialogo();
                                                                                }




                                                                            }
                                                                        }

                                                                    });
                                                                }



                                                                if(source.listaprecios=='on')
                                                                    ListaPrecioDialogo();
                                                                else if(source.msjintroduccion=='on' && source.listaprecios==undefined)
                                                                    MensajeIntrodDialogo();
                                                                else if(source.listaprecios==undefined && source.msjintroduccion==undefined)
                                                                    FinDialogo();






                                                                function MensajeIntrodDialogo(){
                                                                    var titulo='';
                                                                    var cuerpo='';
                                                                    if(source['mensajeintroducTitulo']!=undefined)
                                                                        titulo=source['mensajeintroducTitulo'];
                                                                    if(source['mensajeintrudCuerpo']!=undefined)
                                                                        cuerpo=source['mensajeintrudCuerpo'];
                                                                    vex.dialog.confirm({
                                                                        contentClassName: 'bordernaranjaclassMasAncho',
                                                                        closeClassName: 'closebleclass',
                                                                        message: '',
                                                                        input: [
                                                                            '<div style="text-align: center"><b class="previaheader">Cree su Mensaje</b><div/><br>'+
                                                                            '<div><span>Título</span><input required type="text" style="border: 1px solid #B24E2A" value="'+titulo+'" name="mensajeintrodtitulo"></div>'+
                                                                            '<div><textarea required style="border: 1px solid #B24E2A" name="cuerpomsjinrtrod"  rows="10">'+cuerpo+'</textarea></div>'

                                                                        ].join(''),
                                                                        showCloseButton: true,
                                                                        buttons: [

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Continuar',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first msjintrodcontinuarbutton'
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Volver',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first msjintrodVolverbutton '
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Intentar Nuevamente',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first msjintrodNuevamentebutton '
                                                                            }),

                                                                        ],

                                                                        callback: function (data) {

                                                                            if (clickwhere.val().search(/msjintrodNuevamentebutton/) != -1) {
                                                                                PrimerDialog(source);

                                                                            }

                                                                            if (clickwhere.val().search(/msjintrodVolverbutton/) != -1) {

                                                                                if(source.listaprecios=='on')
                                                                                    ListaprecioPrevia();
                                                                                else
                                                                                    PreviaUno();

                                                                            }

                                                                            if (clickwhere.val().search(/msjintrodcontinuarbutton/) != -1) {
                                                                                source['mensajeintroducTitulo']=data.mensajeintrodtitulo;
                                                                                source['mensajeintrudCuerpo']=data.cuerpomsjinrtrod;
                                                                                PrevioMsjIntrodDialogo();
                                                                            }

                                                                        }

                                                                    });
                                                                }

                                                                function PrevioMsjIntrodDialogo(){

                                                                    vex.dialog.confirm({
                                                                        contentClassName: 'bordernaranjaclassMasAncholistatoroprevia',
                                                                        closeClassName: 'closebleclass',
                                                                        message: '',
                                                                        input: [
                                                                            '<b class="previaheader">Vista Previa</b><br>'+
                                                                            '<div style="text-align: center ;margin-top: 5% !important;height:450px !important;width: 295px !important;margin-left: 21%;background-image: url'+"("+''+source["imgMsjintrod"].replace('\\','/')+');background-size: cover !important;background-position: bottom !important; ">' +
                                                                            '<span class="previatitulo">'+source['mensajeintroducTitulo']+'</span>' +
                                                                            '<div style="width: 123% !important;"> <p class="" style="/*! overflow: auto; */overflow-y: auto;height: 203px;"> '+source['mensajeintrudCuerpo']+' </p>' +

                                                                            ' </div>'+
                                                                            '<div class="segdafoot">'+
                                                                            '<span class="previasubtitulo previatexto">'+source.contacto+'</span>'+brContacto+
                                                                            '<span class="previasubtitulo previatexto">'+source.nombre+'</span>'+brNombre+
                                                                            '<span class="previasubtitulo previatexto">'+source.direccion+'</span>'+brDireccion+
                                                                            '<span class="previasubtitulo previatexto">'+source.telefono+'</span>'+brTelefono+
                                                                            '<span class="previasubtitulo previatexto">'+source.email+'</span>'+brEmail+
                                                                            '</div>'+
                                                                            '</div>'


                                                                        ].join(''),
                                                                        showCloseButton: true,
                                                                        buttons: [

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Continuar',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previamsjintrodcontinuarbutton'
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Volver',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previamsjintrodVolverbutton '
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Intentar Nuevamente',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first previamsjintrodNuevamentebutton '
                                                                            }),

                                                                        ],

                                                                        callback: function (data) {

                                                                            if (clickwhere.val().search(/previamsjintrodNuevamentebutton/) != -1) {
                                                                                PrimerDialog(source);

                                                                            }

                                                                            if (clickwhere.val().search(/previamsjintrodVolverbutton/) != -1) {
                                                                                MensajeIntrodDialogo();


                                                                            }

                                                                            if (clickwhere.val().search(/previamsjintrodcontinuarbutton/) != -1) {

                                                                                FinDialogo();


                                                                            }



                                                                        }

                                                                    });

                                                                }

                                                                function FinDialogo(){
                                                                    var tapa='';
                                                                    if(source.capas=='on'){
                                                                        tapa='<div class="col-md-6" style="margin-left: 25% !important;margin-bottom: 2% !important;">' +
                                                                            '<img class="imagenheadercatalogprevia1" src="'+source.capaUrl+'">'+
                                                                            '</div><div class="col-md-6"> </div>';
                                                                    }
                                                                    vex.dialog.confirm({
                                                                        contentClassName: 'bordernaranjaclassMasAncho',
                                                                        closeClassName: 'closebleclass',
                                                                        message: '',
                                                                        input: [
                                                                            '<br>'+
                                                                            tapa +'<br>'


                                                                        ].join(''),
                                                                        showCloseButton: true,
                                                                        buttons: [

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Crear Catálogo',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first fincontinuarbutton'
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Volver',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first finVolverbutton '
                                                                            }),

                                                                            $.extend({}, vex.dialog.buttons.YES, {
                                                                                text: 'Intentar Nuevamente',
                                                                                className: 'vex-dialog-button-primary vex-dialog-button vex-first finNuevamentebutton '
                                                                            }),

                                                                        ],

                                                                        callback: function (data) {

                                                                            if (clickwhere.val().search(/finNuevamentebutton/) != -1) {
                                                                                PrimerDialog(source);

                                                                            }

                                                                            if (clickwhere.val().search(/finVolverbutton/) != -1) {
                                                                                if(source.msjintroduccion=='on')
                                                                                    PrevioMsjIntrodDialogo();
                                                                                else if(source.msjintroduccion==undefined && source.listaprecios=='on')
                                                                                    ListaprecioPrevia();
                                                                                else if(source.msjintroduccion==undefined && source.listaprecios==undefined)
                                                                                    PreviaUno();


                                                                            }

                                                                            if (clickwhere.val().search(/fincontinuarbutton/) != -1) {
                                                                                console.log(source);


                                                                                var vexwaiting2 = vex.dialog.alert({unsafeMessage: '<div style="text-align: center"><img src="'+imgpreload+'" width="400" height="300">'+ '<br><b>Procesando. Esto puede demorar en dependencia de la cantidad de toros seleccionados. Sea paciente.Espere...</b></div>',
                                                                                    contentClassName: 'bordernaranjaclassMasAncho',
                                                                                    className:'vex-theme-os'
                                                                                });

                                                                                var arra = [];
                                                                                $.each(rows_selected, function (index, rowId) {
                                                                                    arra.push(rowId);
                                                                                });

                                                                                var url = Routing.generate('pdf_generate_catalogo');

                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    data: {source: source},
                                                                                    url: url,
                                                                                    success: function (data) {
                                                                                        vex.close(vexwaiting2)
                                                                                        if (data[0] == 1) {
                                                                                            //window.open(data[1], '_blank');
                                                                                            SaveToDisk(data[1],data[2]);

                                                                                        } else {
                                                                                            vex.dialog.alert({
                                                                                                unsafeMessage: '<b>Error generando PDF</b>',
                                                                                                className: 'vex-theme-wireframe',
                                                                                                overlayClassName: 'success',
                                                                                                contentClassName: 'bordernaranjaclass',
                                                                                                closeClassName: 'closebleclass'
                                                                                            })
                                                                                        }

                                                                                    },
                                                                                    error: function (req, stat, err) {
                                                                                        vex.close(vexwaiting2)
                                                                                        console.log(err);
                                                                                    }
                                                                                });
                                                                                $('#clicked').val('');

                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                            }
                                                            if (clickwhere.val().search(/terceroNuevamentebutton/) != -1) {
                                                                PrimerDialog(source);
                                                            }
                                                        }
                                                    });
                                                }
                                                clickwhere.val('');
                                                PreviaUno();
                                            }
                                        }
                                    });
                                    $('#clicked').val('');
                                }
                                SegundoDialogo(source);
                            }

                        }

                    });
                }


                PrimerDialog();

            },
            error: function (req, stat, err) {
                console.log(err);
            }
        });
    }
});


$('.imagegenerator').click(function(){
   var toroid=$(this).attr('id');
  var vexwaiting=  vex.dialog.alert({ unsafeMessage:'Generando Imagen espere...'

        , className: 'vex-theme-wireframe' ,
        overlayClassName: 'success',
        contentClassName: 'bordernaranjaclass',
        closeClassName: 'closebleclass'
    });
    var url = Routing.generate('toro_img');
    $.ajax({
        type: 'POST',
        data: {id: toroid},
        url: url,
        success: function (data) {
            vex.close(vexwaiting)
            if (data[0] == 1) {
                //window.open(data[1], '_blank');
                var pom = document.createElement('a');
                pom.setAttribute('href', 'data:application/octet-stream,' + encodeURIComponent(data[1]));
                pom.setAttribute('download', data[2]);
                pom.setAttribute('target', '_blank');
                pom.style.display = 'none';
                document.body.appendChild(pom);
                pom.click();
                document.body.removeChild(pom);
            } else {
                vex.dialog.alert({
                    unsafeMessage: '<b>Error generando PDF</b>',
                    className: 'vex-theme-wireframe',
                    overlayClassName: 'success',
                    contentClassName: 'bordernaranjaclass',
                    closeClassName: 'closebleclass'
                });
                console.log(data[1]);
            }

        },
        error: function (req, stat, err) {
            vex.close(vexwaiting)
            console.log(err);
        }
    });


});