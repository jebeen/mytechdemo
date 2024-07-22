   
    $(".loginbtn").click(function(e) {
        e.preventDefault();
        let name = $("#name").val().trim();
        let pwd = $("#password").val().trim();
        if(name == '' || pwd == '') {
            $('#response').html('Input fields are required ...');
        }
        let frm = document.getElementById("login");
        let formData = new FormData(frm);
        let baseurl = $("input[name='baseurl']").val() + 'logincntl/login';
        let rdurl = $("input[name='baseurl']").val() +'dashcntl';
        $.ajax({
            url: baseurl,
            data: formData,
            type: 'POST',
            contentType: false,
            dataType: false,
            processData: false,
            success: function(result) {
                let json = jQuery.parseJSON(result);
                try {
                    if(json['status']) {
                        $('#response').html('Valid user');
                        setTimeout(()=>{
                            window.location.href = rdurl;
                        },1000);
                    } else {
                        $('#response').html(json['message']);
                    }
                } catch(e) {
                    $('#response').html(e);
                }
            },
            error: function(err) {
                $('#response').html(err);
            }
        })
    })