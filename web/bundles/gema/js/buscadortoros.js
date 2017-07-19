
function BuscarToros(dato){

    if(dato==null || dato=='' || dato==' ')
    {


        vex.dialog.alert({ unsafeMessage:'<span style="font-family: Fago-normal">Por favor teclee el nombre, padre o abuelo materno del toro.</span>'

            , className: 'vex-theme-wireframe' ,
            overlayClassName: 'success',
            contentClassName: 'borderblueclass',
            closeClassName: 'closebleclass'
        })
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