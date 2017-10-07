function GetData(folder,param)
{

    var url = Routing.generate('gema_library',{param:param,folder:folder});
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            var getUrl = window.location;
            var segment=getUrl.pathname.split('/');
            var segundoseg='';
            for(var i=0;i<segment.length;i++){
                if(segment[i]=='web')
                    break;
                segundoseg+=segment[i]+'/';
            }
            console.log(segundoseg);
            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + segundoseg;
            reload_js(baseUrl+'/web/bundles/gema/js/bootstrap3.min.js');
            InModalChargeView(data);

        },
        error: function (req, stat, err) {
            console.log(err);
        }
    });
}

function InModalChargeView(data) {
    AddChargeModal(data);
}





function AddChargeModal(data){

    if(document.getElementById("modalcontainer")==null){

        var elemDiv = document.createElement('div');
        elemDiv.id = 'modalcontainer';
        document.getElementsByTagName('body')[0].appendChild(elemDiv);

        $('#modalcontainer').append('' +
            '<div class="modal fade" id="modalsup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
            '<h4 class="modal-title">Biblioteca Multimedia</h4>' +
            '</div>' +
            '<div class="modal-body">' +
            '<div id="fathertab" class="modal-body"><div id="modaltable" > </div>' +
            '</div>' +
            '<div class="modal-footer">' +
            '</div>' +
            '</div><!-- /.modal-content -->' +
            '</div><!-- /.modal-dialog -->' +
            '</div><!-- /.modal -->'
        );


    }
    $('#modalsup').modal('show');
    var $tabla = $("#modaltable");
    $tabla.empty();
    $tabla.append(data);




}


$('#modalsup').show(function(){
    $('#addFileButton').hide();

});

function reload_js(src) {
    $('script[src="' + src + '"]').remove();
    $('<script>').attr('src', src + '?cachebuster='+ new Date().getTime()).appendTo('head');
}


$('#deleteFileButton').click(function(){
    if (confirm("Desea eliminar "+this.name+'?Esta acción no se puede deshacer.') == true) {
        var folder=$('#imgContainer').attr('folder');
        var guidParam=$('#imgContainer').attr('param');
        DeleteFile(folder,guidParam,this.name);
    } else {

    }

});
function DeleteFile(folder,guidParam,filename){
    $('#senddesc').val('');
    filename=filename.replace('.','&');
    var url = Routing.generate('gema_deletefile',{filename:filename,folder:folder,guidParam:guidParam});
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {

            if(data[3]==1)
            {
                console.log(data);
                var fullName=data[2];
                //Limpiando
                $('div[name*="'+fullName+'"]').empty();
               // $('#'+fullName).html(' ');
                var imageDet=$('#imgDet');
                imageDet.attr('href','');
                imageDet.attr('target','blank');
                imageDet.find('#imgContainer').attr('src','');
                imageDet.find('#imgContainer').attr('folder','');
                imageDet.find('#imgContainer').attr('param','');
                imageDet.attr('style','background: transparent;border:none');
                $('#nameFile').val('');
                $('#deleteFileButton').attr('name', '');
                $('#urlFile').val('');

                $('div[name="'+fullName+'"]').remove();
            }
        },
        error: function (req, stat, err) {
            console.log(err);
            console.log(stat);
            console.log(req);
        }
    });
}

$('#textoBuscar').on('change textInput input', function (){
    if(this.value=="")
        $('.imgDiv').show();
    else
        FindFile(this.value);
});
function FindFile(textto)
{
    $('.imgDiv').hide();
    $('.imgDiv .archivo').each(function(){
        if(this.name.toLowerCase().indexOf(textto.toLowerCase())!=-1)
            $(this).parent().show();
    });
}

$('#copyURLButton').click(function () {
    window.prompt("Copiar URL: Ctrl+C, Enter", $('#urlFile').val());
});

$(document).on('click', '.archivo', function() {
    Change(this);
});

function Change(e){
    var server=$('#serverN').val();

    //var getUrl = window.location;
    //'http://'+server+

    var url=e.name;



    $('.archivo').attr('style','border:3px solid wait');
    $(e).attr('style','border: 3px solid  #0073aa');
    var imageDet=$('#imgDet');
    var img=$(e).find('#originalImage');

    imageDet.attr('href',url);
    imageDet.attr('target','blank');
    imageDet.find('#imgContainer').attr('src',img.attr('src'));
    imageDet.find('#imgContainer').attr('folder',img.attr('folder'));
    imageDet.find('#imgContainer').attr('param',img.attr('param'));
    imageDet.attr('style','background: transparent;border:none');

    $('#nameFile').val(img.attr('name'));
    $('#deleteFileButton').attr('name', img.attr('name'));
    $('#urlFile').val(url);

    var inputdesc=$('#senddesc');
    inputdesc.attr('dtanombre',img.attr('name'));
    inputdesc.attr('datafolder',img.attr('folder'));
    inputdesc.attr('subdatafolder',img.attr('param'));
    inputdesc.val('');
    var filename=img.attr('name');
        filename=filename.replace('.','&');
    var ruta=Routing.generate('gema_getmediadescription',{folder:img.attr('folder'),subfolder:img.attr('param'),nombre:filename});

    $.ajax({
        type: 'GET',
        url: ruta,
        success: function (data) {
            inputdesc.val(data[0]);
        },
        error: function (req, stat, err) {
            console.log(err);

        }
    });



}

function getUrlParam( paramName ) {
    var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
    var match = window.location.search.match( reParam );
    return ( match && match.length > 1 ) ? match[1] : null;
}

$('#addFileButton').click(function(){
    if($('#urlFile').val()!=null && $('#urlFile').val()!="")
    {
        var funcNum = getUrlParam( 'CKEditorFuncNum' );
        var fileUrl = $('#urlFile').val();
        window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl, function() {
            var dialog = this.getDialog();
            if ( dialog.getName() == 'image' ) {
                var element = dialog.getContentElement( 'info', 'txtAlt' );
                if ( element )
                    element.setValue( '...' );
            }
        } );
        window.close();
    }
});

function StartDropZone(dropzoneid,param,guidParam,maxFiles,preload,maxFilesize)
{
    if (maxFilesize === undefined)
        maxFilesize=20;
    if (maxFiles === undefined)
        maxFiles=null;
    if(preload==undefined)
        preload=false;
    Dropzone.autoDiscover = false;
    $("div#"+dropzoneid).dropzone({


        init: function() {
            this.on("removedfile", function(file) {
                DeleteFile(param,guidParam,file.name);
            });

            //if(preload===true){
            //
            //}



        },
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: maxFilesize, // MB
        maxFiles:maxFiles,
        url:function(){
            return Routing.generate('gema_pushfile',{param:param,guidParam:guidParam});
        },
      //  previewTemplate: document.getElementById('mytemplate').innerHTML,
        addRemoveLinks:true,
        acceptedFiles:".jpg,.png,.gif,.jpeg,.xls,.xlsx,.doc,.docx,.pdf,.mp4,.avi,.flv,.webm",
        dictRemoveFile:"Eliminar este archivo",
        dictDefaultMessage:"Seleccione los archivos." +
        "\n"+"  Maximo tamaño permitido "+maxFilesize+" MB por archivo.",
        dictFileTooBig:"Archivo demasiado grande.Máximo "+maxFilesize+"MB permitidos.",
        dictRemoveFileConfirmation:'Esto puede borrarar el archivo del servidor si es un archivo que ya fue subido.Desea continuar?',
        accept: function(file, done) {
            if (file.name == "justinbieber.jpg") {
                done("WTF!!!.JB");
            }
            else { done(); }
        },
        success:function(file,response) {

        if( guidParam===undefined){
            guidParam='';
        }
            console.log(response);

            //$('img[alt="'+file.name+'"]')
            //$('.dz-image').find('img').find('')
            var newInput = document.createElement("textarea");
            newInput.setAttribute("class", "form-control descrip");
            newInput.setAttribute("name", "descripciones");
            newInput.setAttribute("dtanombre", response[3]);
            newInput.setAttribute("datafolder",param);
            newInput.setAttribute("subdatafolder",guidParam);
            newInput.placeholder="Descripción del Archivo";




            $(file._removeLink).attr('style','float:right;text-decoration:none');
            $(file._removeLink).attr('class','dz-remove btn btn-danger');

         file.previewElement.appendChild(newInput);

                if(response[2]==true){
                    $('#allImagesFather').append(
                        '<div class="col-xs-5 col-md-2 imgDiv" name="'+response[4]+'" style="width: 200px;height: 200px">' +
                        '<a name="'+response[0]+'" href="#" title="'+response[3]+'"  class="thumbnail archivo" style="border: 3px solid white"> ' +
                        ' <img src="'+response[1]+'"  id="originalImage" name="'+response[3]+'" folder="'+response[5]+'" param="'+response[6]+'">' +
                        '   </a> ' +
                        '    </div> ');

                    if( document.getElementsByClassName('archivo')[0]!=null)
                    document.getElementsByClassName('archivo')[0].addEventListener("click", Change);
                }
        }
    });



}


// Get the input box
//var textInput = document.getElementsByClassName('descrip');

// Init a timeout variable to be used below
var timeout = null;

// Listen for keystroke events
//textInput.onkeyup = function (e) {
//
//    // Clear the timeout if it has already been set.
//    // This will prevent the previous task from executing
//    // if it has been less than <MILLISECONDS>
//    clearTimeout(timeout);
//
//    // Make a new timeout set to go off in 800ms
//    timeout = setTimeout(function () {
//        console.log('Input Value:', textInput.value);
//    }, 500);
//};

$(document).on('keyup', '.descrip', function () {
    clearTimeout(timeout);
    var folder=$(this).attr('datafolder');
    var subfolder=$(this).attr('subdatafolder');
    var nombre=$(this).attr('dtanombre');
    nombre=nombre.replace('.','&');
    var descripcion=$(this).val();
    descripcion=descripcion.replace('//','^^');
    descripcion=descripcion.replace('/','^');
    var alphabet = "abcdefghijklmnopqrstuvwxyz".split("");
    alphabet.forEach(function(letter) {
        var upperletter=letter.toUpperCase();
        descripcion=descripcion.replace('/'+letter,'^'+letter);
        descripcion=descripcion.replace('/'+upperletter,'^'+upperletter);

    });
    var numbers = "0123456789".split("");
    numbers.forEach(function(number) {
        descripcion=descripcion.replace('/'+number,'^'+number);

    });

    console.log(descripcion);
    if(descripcion!=null && descripcion!==undefined && descripcion!=''){
        timeout = setTimeout(function (e) {

            var url=Routing.generate('gema_setmediadescription',{folder:folder,subfolder:subfolder,nombre:nombre,description:descripcion});

            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {

                },
                error: function (req, stat, err) {
                    console.log(err);

                }
            });
        }, 500);

    }


});





