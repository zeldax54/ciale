function GetData(ruta)
{
    var url = Routing.generate(ruta);
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            InModalChargeView(data);
            $('.dropdown-toggle').dropdown();
        },
        error: function (req, stat, err) {
            $('.dropdown-toggle').dropdown();
            console.log(err);
            console.log(stat);
            console.log(req);
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





$('#deleteFileButton').click(function(){
    if (confirm("Desea eliminar "+this.name+'?Esta acción no se puede deshacer.') == true) {
        DeleteFile(this.name);
    } else {

    }

});
function DeleteFile(filename){
    var url = Routing.generate('gema_deletefile',{filename:filename});
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            if(data[0]==true)
            {
                var fullName='library/'+data[1];
                //Limpiando
                $('a[name*="'+fullName+'"]').parent().empty();
                var imageDet=$('#imgDet');
                imageDet.attr('href','');
                imageDet.attr('target','blank');
                imageDet.find('#imgContainer').attr('src','');
                imageDet.attr('style','background: transparent;border:none');


                $('#nameFile').val('');
                $('#deleteFileButton').attr('name', '');
                $('#urlFile').val('');

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
    var url='http://'+server+e.name;

    $('.archivo').attr('style','border:3px solid wait');
    $(e).attr('style','border: 3px solid  #0073aa');
    var imageDet=$('#imgDet');
    var img=$(e).find('#originalImage');

    imageDet.attr('href',url);
    imageDet.attr('target','blank');
    imageDet.find('#imgContainer').attr('src',img.attr('src'));
    imageDet.attr('style','background: transparent;border:none');

    $('#nameFile').val(img.attr('name'));
    $('#deleteFileButton').attr('name', img.attr('name'));
    $('#urlFile').val(url);
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
            // Get the reference to a dialog window.
            var dialog = this.getDialog();
            // Check if this is the Image Properties dialog window.
            if ( dialog.getName() == 'image' ) {
                // Get the reference to a text field that stores the "alt" attribute.
                var element = dialog.getContentElement( 'info', 'txtAlt' );
                // Assign the new value.
                if ( element )
                    element.setValue( '...' );
            }
            // Return "false" to stop further execution. In such case CKEditor will ignore the second argument ("fileUrl")
            // and the "onSelect" function assigned to the button that called the file manager (if defined).
            // return false;
        } );
        window.close();
    }
});

function StartDropZone(dropzoneid,param,guidParam)
{
    $("div#"+dropzoneid).dropzone({


        init: function() {
            this.on("removedfile", function(file) {
                DeleteFile(file.name);
            });

        },
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 10, // MB
        url:function(){
            return Routing.generate('gema_pushfile',{param:param,guidParam:guidParam});
        },
        addRemoveLinks:true,
        acceptedFiles:".jpg,.png,.gif,.jpeg,.xls,.xlsx,.doc,.docx,.pdf,.mp4,.avi,.flv,.webm",
        dictRemoveFile:"Eliminar este archivo",
        dictDefaultMessage:"Seleccione los archivos." +
        "\n"+"  Maximo tamaño permitido 10 MB por archivo.",
        dictFileTooBig:"Archivo demasiado grande.Máximo 10MB permitidos.",
        dictRemoveFileConfirmation:'Esto puede borrarar el archivo del servidor si es un archivo que ya fue subido.Desea continuar?',
        accept: function(file, done) {
            if (file.name == "justinbieber.jpg") {
                done("WTF!!!.JB");
            }
            else { done(); }
        },
        success:function(file,response) {

                if(response[2]==true){
                    $('#allImagesFather').append(
                        '<div class="col-xs-5 col-md-2 imgDiv">' +
                        '<a name="'+response[0]+'" href="#"  class="thumbnail archivo" style="border: 3px solid white"> ' +
                        ' <img src="'+response[1]+'"  id="originalImage" name="'+response[3]+'">' +
                        '   </a> ' +
                        '    </div> ');

                    document.getElementsByClassName('archivo')[0].addEventListener("click", Change);
                }



        }
    });



}




