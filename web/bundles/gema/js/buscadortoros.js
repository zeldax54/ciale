
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

  function buscarTorosLike(dato,parentid,divparent,customheight){
     var url = Routing.generate('gema_searchtoroscarnelike',{dato:dato});
      var getUrl = window.location;
      var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
      $.ajax({
          type: 'POST',
          url: url,
          success: function (data) {
              if(data[0]!==0){
                  if(document.getElementById("uldin")==null)
                {
                    var elemDiv = document.createElement('ul');
                    elemDiv.id = 'uldin';
                   // elemDiv.setAttribute("style", "display:none");

                    document.getElementById(divparent).appendChild(elemDiv);
                }
                  var ulContainer=  $('#uldin');
                  ulContainer.empty();
                   ulContainer.prop('class','nav navbar-nav dynamicul');

                  var ulParent=$('#'+parentid);
                  var width=ulParent.css('width').replace(/[^-\d\.]/g, '');
                  ulContainer.css('width',parseFloat(width)+ + parseFloat(50));
                  ulContainer.css('position','absolute');
                  ulContainer.css('z-index','10000');
                  var offset=ulParent.css('height').replace(/[^-\d\.]/g, '');
                  var top= ulParent.offset().top + parseFloat(offset);
                  var left= ulParent.offset().left;
                  ulContainer.offset({top:top,left:left})

                  var lis='';
                  for (var f = 0; f < data.length; f++) {
                      var torodetalleruta=Routing.generate('gema_torodetail',{toroid:data[f].id});
                      lis+='<li class="menu-items dynamicliscontainer" >' +
                          '<a href="'+torodetalleruta+'">' +
                          '<div><div class="col-xs-4"><img class="imgdynamicli" src="'+baseUrl+data[f].imagen+'">' +
                          '</div>' +
                          '<div class="col-xs-8">'+
                          '<strong>'+data[f].apodo+'</strong><br>' +
                          '<span>'+data[f].nombreraza+'</span><br>' +
                          '<span style="font-size: 11px">'+data[f].nombretoro+'</span>' +
                          '</div></div></a></li>';

                  }
                  lis+='';
                  ulContainer.append(lis);
                  if(ulContainer.find('li').length>1)
                  {
                      ulContainer.css('height',customheight);
                      ulContainer.css('overflow','scroll');
                  }
                  else{
                      ulContainer.css('height','auto');
                  }
                  ulParent.focusout(function(){
                      ulContainer.remove();
                      $($(this).prop('value',''));
                  });

              }
              if(data===0){
                  $('#uldin').remove();
              }


          },
          error: function (req, stat, err) {

          }
      });

   }