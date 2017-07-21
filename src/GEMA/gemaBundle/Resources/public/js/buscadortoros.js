
function BuscarToros(dato){

    if(dato==null || dato=='' || dato==' ')
    {
        Snarl.addNotification({
            title: 'Error',
            text: 'Por favor teclee el nombre, padre o abuelo materno del toro.',
            icon: '<i class="fa fa-info-circle"></i>',
            timeout: 5000
        });
        return;
    }
    var url = Routing.generate('gema_searchtoroscarne',{dato:dato,isfull:'0'});
    $.ajax({
        type: 'POST',
        url: url,
        success: function (data) {
            if(data[0]===0){
                vex.dialog.alert({ unsafeMessage:'<span style="font-family: Fago-normal"> La búsqueda no produjo resultados</span>'

                    , className: 'vex-theme-wireframe' ,
                    overlayClassName: 'success',
                    contentClassName: 'borderblueclass',
                    closeClassName: 'closebleclass'
                })
            }
            if(data[0]===1)
            {
                var url = Routing.generate('gema_searchtoroscarne',{dato:dato,isfull:'1'});
                window.location.replace(url);
            }

        },
        error: function (req, stat, err) {

        }
    });



}