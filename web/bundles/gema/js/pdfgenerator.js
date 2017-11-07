$('.imprimircatalogo').click(function(){








    var familyId=$(this).attr('familyId');
    var table=$('table.'+familyId).not('.DTFC_Cloned').DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    if(rows_selected.length===0){

        vex.dialog.alert({ unsafeMessage:'No ha seleccionado ningún toro'

            , className: 'vex-theme-wireframe' ,
            overlayClassName: 'success',
            contentClassName: 'bordernaranjaclass',
            closeClassName: 'closebleclass'
        })


    }else {

        $(document).on('.imprimirtorosbutton','click',function(){
            alert('asd');
        });

        $.ajax({
            type: 'GET',
            data:{},
            url: Routing.generate('gema_catalogobaseinfo'),
            success: function (data) {





                //var source = {
                //    'Capas': true,
                //    'Marks': marks,
                //    'Subjects': subjects
                //}

                var priemerDialogo=  vex.dialog.open({


                    contentClassName: 'bordernaranjaclass',
                    closeClassName: 'closebleclass',

                    message: '',


                    input: [

                        '<div class="row"> ' +
                        '<div class="col-md-6">' +
                        '<h4 class="headercatalog">Crear PDF</h4>'+
                            '<div><label><input class="checkheader" type="checkbox" checked="checked" id="capas" name="capas" ><b class="catalotext">Capas</b> </label></div>'+
                        '<div><label><input class="checkheader" type="checkbox" checked="checked" id="listaprecios" name="listaprecios"><b class="catalotext">Lista de Precios</b> </label></div>'+
                        '<div><label><input class="checkheader" type="checkbox" checked="checked" id="msjintroduccion" name="msjintroduccion"><b class="catalotext">Mensaje de Intruducción</b> </label></div>'+
                        '<div><label><input class="checkheader" type="checkbox" checked="checked" id="tablacontenidos" name="tablacontenidos"><b class="catalotext">Tabla de contenidos</b> </label></div>'+

                        '</div>' +
                        '<div class="col-md-6">' +
                        '<h4 class="headercatalog">Imprimir Toros</h4>'+
                        '<div><label><input class="checkheader" onclick="return false;" type="checkbox" checked="checked" id="capas" ><b class="catalotext">Toros Individuales</b> </label></div>'+

                        '</div>' +
                        '</div>'+
                        '<div class="row">' +
                        '<div class="col-md-6"><div><img class="imagenheadercatalog" src="'+ data[0].miniaturacrarpdf+'"></div></div>'+
                        '<div class="col-md-6"><div><img class="imagenheadercatalog" src="'+ data[0].miniaturaimprimirtoros+'"></div></div>'+
                        '</div>'



                    ].join(''),
                    showCloseButton:true,
                    buttons: [

                        $.extend({}, vex.dialog.buttons.NO, { text: 'Imprimir Toros',className:'vex-dialog-button-primary vex-dialog-button vex-first imprimirtorosbutton',type:"button" }),

                        $.extend({}, vex.dialog.buttons.YES, { text: 'Crear PDF',className:'vex-dialog-button-primary vex-dialog-button vex-first crearpdfbutton' }),


                    ],

                    callback: function (data) {

                        if(!data){
                           var vexwaiting= vex.dialog.alert({ unsafeMessage: '<b>Procesando.Espere...</b>' })

                            var arra = [];
                            $.each(rows_selected, function (index, rowId) {
                                arra.push(rowId);
                            });

                            var url=Routing.generate('pdf_generate');

                            $.ajax({
                                type: 'POST',
                                data:{ids:arra,filename:'toros'},
                                url: url,
                                success: function (data) {
                                    vex.close(vexwaiting)
                                    if(data[0]==1){
                                        window.open(data[1], '_blank');
                                        //window.location.href="about:blank";
                                        //window.location=data[1];
                                    }else{
                                        vex.dialog.alert({ unsafeMessage: '<b>Error generando PDF</b>',
                                            className: 'vex-theme-wireframe' ,
                                            overlayClassName: 'success',
                                            contentClassName: 'bordernaranjaclass',
                                            closeClassName: 'closebleclass'})
                                    }

                                },
                                error: function (req, stat, err) {
                                    vex.close(vexwaiting)
                                    console.log(err);
                                }
                            });


                        }
                        else{
                            var source = {
                                'capas': data.capas,
                                'listaprecios': data.listaprecios,
                                'msjintroduccion': data.msjintroduccion,
                                'tablacontenidos':data.tablacontenidos
                            };


                           if(source.capas==='on'){

                           }

                        }



                    }

                })


            },
            error: function (req, stat, err) {
                console.log(err);
            }
        });







    }



});