/**
 * Created by hector.duran on 29-abr-16.
 */


$(document).ready(function(){




});

function DBConect(ruta,rutaidparam,valueid,tarjetselectid) {

    if(valueid==null || valueid=='')
    {
        $('#' + tarjetselectid).empty();
        $('#' + tarjetselectid).trigger("chosen:updated");

    }
    else
    {
        var url = Routing.generate(ruta, {id: valueid})


        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: url,
            success: function (data) {
                fillcampo(data);
                $('#' + tarjetselectid).empty();
                $('#' + tarjetselectid).trigger("chosen:updated");
                for (var f = 0; f < data.length; f++) {
                    $('#' + tarjetselectid).append('' +
                    '<option value=' + data[f].id + '>' + data[f].nombre + '<option>');
                    $('#' + tarjetselectid).trigger("chosen:updated");

                }
            },
            error: function (req, stat, err) {


                fillcampo(req.responseText);
            }

        });
    }


}
    function fillcampo(data)
    {

        $('#cc').empty();
        $('#cc').append(data);
    }


//Funcion que retorna el elemento asignado a un Select en un Edit , para las relaciones OneOne, via AJAX
//url -Ruta donde esta la funcion   destino-id del select destino
 function FindMyOne(url,destino)
 {
     $.ajax({
         type: 'POST',
         dataType: 'json',
         url: url,
         success: function (data) {
             for (var f=0;f<data.length;f++) {
                 $('#'+destino).append('' +
                     '<option value='+data[f].id+' selected>'+data[f].nombre+'<option>')
                 $('#'+destino).trigger("chosen:updated");
             }

         },
         error: function (req, stat, err) {
             fillcampo(req.responseText);
         }

     });
 }

function CreateData(files,images,nombresf)
{
    $('#'+images).empty();
    $('#'+nombresf).empty();
    var cont=0;
    files.forEach(function(file){
        $('#'+images).append('' +
            '<input type="textarea" name="textareas[]" id="'+file.name+cont+'" value="">'
        );
        $('#'+nombresf).append('' +
            '<input type="textarea" name="nombresarray[]" id="nombres'+file.name+cont+'">'
        );
        document.getElementById(file.name+cont).value=file.dataURL;
        document.getElementById('nombres'+file.name+cont).value=file.name;
        cont++;
    });
}






