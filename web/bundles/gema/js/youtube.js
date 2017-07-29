
function Initializate(youtubeFieldId){
    var youtubeFieldData=$('#'+youtubeFieldId);
    var jsonString=youtubeFieldData.val();
    if(jsonString!=null && jsonString!='')
      var array = JSON.parse(jsonString)

    var source = [];
    if(array!=undefined){
        for(var i=0;i<array.length;i++)
        {
            source.push({
                "id":i,
                "video" : array[0]
            });
        }
    }

    function resetTabullet(idField) {
        $("#tabla").tabullet({
            data: source,
            action: function (mode, data) {

                if (mode === 'save') {
                    source.push({
                        "id":source.length+1,
                        "video" : data.video
                    });
                    UpdateJson(source,idField);

                }
                if (mode === 'edit') {
                    for (var i = 0; i < source.length; i++) {
                        if (source[i].id == data.id) {
                            source[i] = data;
                            UpdateJson(source,idField);
                        }
                    }
                }
                if(mode == 'delete'){
                    for (var i = 0; i < source.length; i++) {
                        if (source[i].id == data) {
                            source.splice(i,1);
                            UpdateJson(source,idField);
                            break;
                        }
                    }
                }
                resetTabullet(youtubeFieldId);
            }
        });
    }

    resetTabullet(youtubeFieldId);


    function  UpdateJson(data,youtubeFieldId){
        console.log(data);
        var json='[';
        for(var i=0;i<data.length;i++)
        {
            if(i<data.length-1)
                json+='"'+data[i].video+'",';
            else
                json+='"'+data[i].video+'"';
        }
        json+="]";
        console.log(json);
        console.log(youtubeFieldId);
        document.getElementById(youtubeFieldId).value = json;
    }
}





