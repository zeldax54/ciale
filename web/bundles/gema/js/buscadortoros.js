
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

  function buscarTorosLike(dato,parentid,divparent,customheight,divparent,isresponsive){
      if(divparent==undefined)
          divparent='divparent';
      if(isresponsive==undefined)
          isresponsive=false;
     var url = Routing.generate('gema_searchtoroscarnelike',{dato:dato});



      var getUrl = window.location;
      var segment=getUrl.pathname.split('/');
      var segundoseg='';
      var band=false;
      for(var i=0;i<segment.length;i++){
          if(segment[i]=='web')
          {
              band=true;
              break;

          }
          segundoseg+=segment[i]+'/';
      }
      var baseUrl='';
      if(band==true)
         baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + segundoseg;
      else
          baseUrl=getUrl .protocol + "//" + getUrl.host + "/";
console.log(baseUrl);
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
                  ulContainer.css('width',parseFloat(width)+ + parseFloat(10));
                  ulContainer.css('position','absolute');
                  ulContainer.css('z-index','10000');
                  var offset=ulParent.css('height').replace(/[^-\d\.]/g, '');
                  var top= ulParent.offset().top + parseFloat(offset);
                  var left= ulParent.offset().left;
                  ulContainer.offset({top:top,left:left})

                  var lis='';
                  for (var f = 0; f < data.length; f++) {

                      var torodetalleruta=Routing.generate('gema_torodetail',{apodo:data[f].apodo});

                      lis+='<li class="menu-items dynamicliscontainer" >' +
                          '<a href="'+torodetalleruta+'">' +
                          '<div><div class="col-xs-4">';
                              if(isresponsive==false)
                                 lis+= '<img class="imgdynamicli" src="'+data[f].imagen+'">';
                               else
                                  lis+= '<img width="40px" height="40px" class="" src="'+data[f].imagen+'">';
                         lis+= '</div>' +
                          '<div class="col-xs-8">'+
                          '<strong>'+data[f].apodo+'</strong><br>' +
                          '<span>'+data[f].nombreraza+'</span><br>' +
                          '<span style="font-size: 11px">'+data[f].nombretoro+'</span>' +
                          '</div></div></a></li>';

                  }
                  lis+='';
                  ulContainer.append(lis);
                  ulContainer.show();
                  if(ulContainer.find('li').length>1)
                  {
                      ulContainer.css('height',customheight);
                      ulContainer.css('overflow','auto');
                  }
                  else{
                      ulContainer.css('height','auto');
                  }
                  $(window).unbind( "click" );
                  $(window).click(function() {
                      ulContainer.remove();
                  });
                  //ulParent.focusout(function(){
                  //   // if(ulContainer.find('li').length==0){
                  //
                  //        ulContainer.hide();
                  //
                  //  //  }
                  //});

              }
              if(data===0){
                  ulContainer.remove();
              }


          },
          error: function (req, stat, err) {

          }
      });

   }