var token;
$(document).ready(function() {
    let url = $("input[name='baseurl']").val().trim() +'dashcntl/students_ajax';
    token = $("input[name='csrf_test_name']").val().trim();
    let action = {
        visible : true,
        data: 'action_button'
    };
    $("#dynamic-student-table").DataTable({
        processing:true,
        destroy: true,
        searching :true,
        ordering:true,
        serverSide: true,
        ajax:{
            url: url,
            data : {'csrf_test_name': token},
            type: 'post',
            dataSrc: 'data'
        },
        columns: [
            {
                render: function(data,type,row,meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data : 'name'},
            {data : 'rollno'},
            {data : 'address'},
            {data : 'class'},
            {data : 'subject1'},
            {data : 'subject2'},
            {data : 'subject3'},
            {data : 'grade'},
            {data : 'create_date'},
            action

        ]
    })
    $("#user-table").DataTable({
        lengthMenu: [10,25,100],
        iDisplayLength:10,
        searching: true,
        ordering:true
    })

    $("#add-teacher").submit(function(e) {
        e.preventDefault();
        let frm = $("#add-teacher")[0];
        let name = $("#name").val().trim();
        let pwd = $("#password").val().trim();

        if(name == '' || pwd == '') {
            $("#resp").removeClass('hidden').html('Input fields are required');
            return;
        }
        
        let formdata = new FormData(frm);
        let baseurl = $("input[name='baseurl']").val().trim() + 'dashcntl/addteacher';
        $.ajax({
            url: baseurl,
            type: 'post',
            data : formdata,
            contentType: false,
            dataType: false,
            processData: false,
        })
        .done((result) => {
            let json = jQuery.parseJSON(result);
            try {
                if(json['status']) {
                    setTimeout(()=>{
                        window.location.reload();
                    },1000);
                } else {
                $("#resp").removeClass('hidden').html(json['message']);
                }
            } catch(e) {
                $("#resp").removeClass('hidden').html(e);
            }
        })
        .fail((err) => {
            document.getElementById('resp').innerHTML = err;
        })
    })
})

$(document).on('click',".action-btn",function(e) {
    let baseurl = $("input[name='baseurl']").val();
    let action = $(this).attr('data-action');
    let id = e.target.id;
    if(action == 'edit') {
        $('.modal-backdrop').remove();

        $(".editModal").removeClass("hidden");
        fetch('http://localhost:8080/CI/college/dashcntl/getstudent/'+id)
        .then(result => result.json())
        .then(res => {
            let {slno,name,rollno,subject1,subject2,subject3,address,grade} = res.data[0];
            $("#slno").val(slno);
            $("#name").val(name)
            $("#rollno").val(rollno)
            $("#sub1").val(subject1)
            $("#sub2").val(subject2)
            $("#sub3").val(subject3)
            $("#grade").val(grade)
            $("#address").val(address)
        })
        .catch(err => console.log(err))
    } else {
        fetch('http://localhost:8080/CI/college/dashcntl/removeStudent/'+id)
        .then((result) => result.json())
        .then(res => {
            document.getElementById("resp").removeAttribute('hidden');
            document.getElementById("resp").innerHTML = res.message;
        })
        .catch(err => console.log(err))
    }
})