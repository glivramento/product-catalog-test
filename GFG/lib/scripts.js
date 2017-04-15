    function btnAction(action){
        var json = $('#json').val();
        var valida = $.ajax({
            type: "POST",
            url: action+'.php',
            data: json,
            success: function(data){ 
                if(data.valid == 1) {
                    $('#logbox').html('<div class="validbox"> <span class="glyphicon glyphicon-thumbs-up validicon" ></span><div class="validtxt">Valid JSON format</div></div>');
                    if(data.newJson)
                        $('#json').val(data.newJson);
                } else {
                    $('#logbox').html('<div class="invalidbox"> <span class="glyphicon glyphicon-thumbs-down invalidicon" ></span><div class="validtxt">Invalid JSON format</div></div>');

                }
                $.each(data.errors, function(index,value){
                    if (value.code == 2 )
                        $('#logbox').append('<span style="color: #f44336;">* '+value.desc+'<br><br><span>');
                    if (value.code == 1 )
                        $('#logbox').append('<span style="color: #f44336;">* Field '+value.field_name+' of register sku:'+value.sku+' (<b>'+value.desc+'</b>)<br><br><span>');
                    if (value.code == 0 )
                        $('#logbox').append('<span style="color: #008CBA">* '+value.desc+'<br><br><span>');
                });
            },
            error:function(){
                console.log('Error on post');
            },   
            dataType: "json"
        });
    }
