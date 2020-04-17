
    $("#sendform").on('submit',(function(e){
        e.preventDefault();
        var clickwhere=$('#clicked');
        var theWheel;
        var that = this;

        vex.dialog.confirm({
            message: 'IMPORTANTE! El envío de este formulario es una confirmación formal de su pedido, por favor revisar antes de enviar.',
            className: 'vex-theme-default' ,
            callback: function (value) {
                if(value==false){                  
                    return;
                }
                else{
                   
                    $('#bsubmit').hide();
                   
                    
                 
                  
                    vex.dialog.confirm({
                        message: 'Confirmar la compra y tirar la ruleta. ¡Suerte 🤞🏻!',
                        className: 'vex-theme-default' ,
                        callback: function (value) {
                            if(value==false){
                                $('#bsubmit').show();
                                return;
                            }
                            else{
                                 // Ruleta
                                 console.log(ruletas);
                                 var dosis = tabla.column(2).data().sum();
                                 console.log(dosis);
                                 var showruleta=false;
                                 var ruletagen = null;  
                                 var premiotext = null;         
                     
                                 $.each(ruletas, function( index, ruleta ) {            
                                   if( dosis>=ruleta.min && dosis<=ruleta.max)
                                    {
                                        //Vex
                                        ruletagen=ruleta;                            
                                        showruleta=true;
                                         theWheel = new Winwheel({
                                            'numSegments'  : 8,         // Number of segments
                                            'outerRadius'  : 212,       // The size of the wheel.
                                            'centerX'      : 217,       // Used to position on the background correctly.
                                            'centerY'      : 219,
                                            'textFontSize' : 15,        // Font size.
                                            'segments'     :            // Definition of all the segments.
                                            [
                                               {'fillStyle' : '#eae56f', 'text' : ruleta.premios[0].nombre},
                                               {'fillStyle' : '#89f26e', 'text' : ruleta.premios[1].nombre},
                                               {'fillStyle' : '#7de6ef', 'text' : ruleta.premios[2].nombre},
                                               {'fillStyle' : '#e7706f', 'text' : ruleta.premios[3].nombre},
                                               {'fillStyle' : '#eae56f', 'text' : ruleta.premios[4].nombre},
                                               {'fillStyle' : '#89f26e', 'text' : ruleta.premios[5].nombre},
                                               {'fillStyle' : '#7de6ef', 'text' : ruleta.premios[6].nombre},
                                               {'fillStyle' : '#e7706f', 'text' : ruleta.premios[7].nombre}
                                            ],
                                            'animation' :               // Definition of the animation
                                            {
                                                'type'     : 'spinToStop',
                                                'duration' : 5,
                                                'spins'    : 8,
                                                'callbackFinished' : alertPrize
                                            },
                         
                                        });
                                        theWheel.animation.spins = 8;
                                      
                             
                                        // Called when the animation has finished.
                                        function alertPrize(indicatedSegment)
                                        {
                                            premiotext = indicatedSegment.text;
                                            //delay here
                                           // wait(2000);
                                           
                                            sendCompra(new FormData(that),premiotext,ruletagen,totlaahorro);
                                            return;
            
                                           
                                        }
                                    }            
                            
                                });    
            
                                if(showruleta==true){
                                    $('#canvasModal').modal('show');
                                    theWheel.startAnimation();
                                }
                                else{
                                    sendCompra(new FormData(that),premiotext,ruletagen,totlaahorro);
                                    return;
            
                                }
                              
                            }
                        }
                    });

                }
            }
         });
     
       





    }));

    function wait(ms){
        var start = new Date().getTime();
        var end = start;
        while(end < start + ms) {
          end = new Date().getTime();
       }
     }

  function  sendCompra(formdata,premiotext,ruletagen,totlaahorro)
{
      
        console.log(premiotext);
        console.log(ruletagen);
var rows=tabla.rows().data();
console.log(rows);
var pedidocompra=new Array(rows.length);
$.each(rows, function( index, r ) {   
    let obj={
        pedidobaseid:r[7],
        cantidad:r[2],
        idtoro:r[0],
        total:r[5]
    };
    pedidocompra.push(
        obj
    );
 
});
console.log(pedidocompra);

var src=$('#mygif').attr('src');
vex.dialog.alert({ unsafeMessage:'<div style="text-align: center">' +
        '<h5 style="font-family: Fago-Bold">Enviando pedido</h5>'+
'<img width="200px" height="200px" src="'+src+'"><div>'

, className: 'vex-theme-default' ,
overlayClassName: 'success',
contentClassName: 'borderblueclass errormaxwidth',
closeClassName: 'closebleclass'
});

formdata.append('pedidocompra', JSON.stringify(pedidocompra));
if(ruletagen!=null)
  formdata.append('ruletaid', ruletagen.id);
else
formdata.append('ruletaid', -1);
formdata.append('premiotext',premiotext);
formdata.append('totlaahorro',totlaahorro);
formdata.append('descuento',descuento);



        $.ajax({
            url: Routing.generate('gema_comprasdo'),
            type: "POST",
            data:  formdata,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                console.log(data);
                vex.closeAll();
           //     $("#canvasModal").modal('hide');
            var msj;
            if(ruletagen==null)
                 msj = '<span> Te enviamos un resumen de tu compra a tu correo electrónico. Gracias 😊</span>';
             else
                msj = '<span>Ganaste '+premiotext + '!</span><br><span> Te enviamos un resumen de tu compra y el premio obtenido a tu correo electrónico. Gracias 😊</span>';
                vex.dialog.alert({ unsafeMessage:msj

                    , className: 'vex-theme-wireframe' ,
                    overlayClassName: 'success',
                    contentClassName: 'borderblueclass maxwidth',
                    closeClassName: 'closebleclass'
                });
                console.log(data[2]);
                $('#bsubmit').show();
            },
            error: function(error,stat, err){
                vex.closeAll();
                vex.dialog.alert({ unsafeMessage:err,

                    className: 'vex-theme-default'  ,
                    overlayClassName: 'success',
                    contentClassName: 'borderblueclass errormaxwidth',
                    closeClassName: 'closebleclass'
                })
                $('#bsubmit').show();
            }
        });
    }

