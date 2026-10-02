$('input').on('focus', function() {
  $(this).removeClass('is-invalid').removeClass('is-valid');
});
$('select').on('focus', function() {
  $(this).removeClass('is-invalid').removeClass('is-valid');
});

moment.locale('id');
$('#dob').daterangepicker({
  "singleDatePicker": true,
  "showDropdowns": true,
  "locale": {
    format: "D MMMM YYYY",
    cancelLabel: "Clear",
  },
  "autoUpdateInput": false,
  "maxDate": moment().subtract(18,'year'),
  "minYear": parseInt(moment().subtract(70, 'year').format('YYYY'), 10),
  "startDate": moment().subtract(18,'year'),
  "drops": "auto",
});

 $('#dob').on('apply.daterangepicker', function(ev, picker) {
   $('input[name="birthday"]').val(picker.startDate.format('YYYY-MM-DD'));
   $('#dob').val(picker.startDate.format('D MMMM YYYY'));
});

$('#dob').on('cancel.daterangepicker',function(ev,pick){
  $('input[name="birthday"]').val('');
  $('#dob').val('');
})

$(document).ready(function(e) {
  function ResizeImage(dis) {
    var filesToUpload = document.getElementById("image").files;
    var file = filesToUpload[0];

    var img = document.createElement("img");
    var reader = new FileReader();
    reader.onload = function(e) {
      var img = new Image();
      img.src = this.result;
      setTimeout(function() {
        var canvas = document.createElement("canvas");
        var MAX_WIDTH = 600;
        var MAX_HEIGHT = 600;
        var width = img.width;
        var height = img.height;

        if (width > height) {
          if (width > MAX_WIDTH) {
            height *= MAX_WIDTH / width;
            width = MAX_WIDTH;
          }
        } else {
          if (height > MAX_HEIGHT) {
            width *= MAX_HEIGHT / height;
            height = MAX_HEIGHT;
          }
        }
        canvas.width = width;
        canvas.height = height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0, width, height);

        let type = document.getElementById("image").files[0].type;
        var dataurl = canvas.toDataURL(type);
        $("#type").val(type);
        $("#data").val(dataurl);
      }, 200);

      $("#target").attr("src", reader.result);
    }
    reader.readAsDataURL(file);
  }

  $("#image").on("change", (function(e) {
    e.preventDefault();
    ResizeImage(this);
  }));

  $("#password").on("focus",function(){
    $(this).closest(".row").find(".invalid-feedback").removeClass("d-block");
  });
});

