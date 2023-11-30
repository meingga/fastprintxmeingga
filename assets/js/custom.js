$('.input-group-addon').on('click', function(){
  attrType = $('#password').attr('type');
  icon = $('#icon-pass-profile').attr('class');
  console.log(attrType);
  if(attrType == 'password'){
    $('#password').attr('type', 'text')
    $('#icon-pass-profile').attr('class', 'fa fa-eye-slash')
  }else{
    $('#password').attr('type', 'password');
    $('#icon-pass-profile').attr('class', 'fa fa-eye')
  }
})

$('input').on('keypress',function(e) {
  if(e.which == 13) {
    get()
  }
});

$(document).on('click','#masuk',function(){
  get()
});

function get(){
 $.ajax({
  url: base_url+'auth/login',
  type:'post',
  data:{
    'username': $('#nomor_induk').val(),
    'password': $('#password').val()
  },
  dataType: 'json',
  success: function(res){
    if(res.code == 1){

      Swal.fire({
        timer: 3000,
        title: 'Sukses',
        text: res.message,
        type: res.type,
        onClose: () => {
          window.location.href=base_url+'home';
        }
      })  
    }else{
      Swal.fire({
        timer: 3000,
        title: 'Gagal',
        text: res.message,
        type: res.type
      })
    }
  }
});
}