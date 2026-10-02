const loadData = $('#dataTable').DataTable( {
    "processing": true,
    "serverSide": true,
    "order":[[4,'desc']],
    columnDefs: [
      { orderable: false, targets: 5 }
    ],
    "ajax": "/api/channels",
    language: {
      paginate: {
        next: '<i class="fas fa-chevron-right"></i>',
        previous: '<i class="fas fa-chevron-left"></i>'
      }
    },
  });


$("#modDetail").on("show.bs.modal", function (e) {
  let id = $(e.relatedTarget).data("id")
  if (id != 0) {
    $.ajax({
      url: "/api/channel",
      type: "POST",
      data: {
        id: id
      },
      success: function (data) {
        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#channel_slug").val(data.channel_slug).removeClass('is-invalid');
        $("#slug").val(data.slug).removeClass('is-invalid');
        $("#footer>div").html(data.footer);
        $('#footer').removeClass('d-none');
      }
    });
  } else { 
    $('#footer').addClass('d-none');
    $("#id").val(0);
    $('#name').val('');
    $('#channel_slug').val('').removeClass('is-invalid');
    $('#slug').val('').removeClass('is-invalid');
  }
});

$('#formChannel').submit(function(e) { 
  e.preventDefault();
  $.ajax({
    url: '/api/channel/save',
    type: 'POST',
    data: $('#formChannel').serialize(),
    success: function (data) {
      if (data.status) {
        loadData.ajax.reload();
        $("#modDetail").modal("hide");
      }

      if (data.message != 'taken') {
        swal.fire({
          icon: data.icon,
          title: data.message,
          showConfirmButton: false,
          timer: 4000,
        });
      } else { 
        $('#channel_slug').addClass('is-invalid').next('div').text('Combination of channel slug and ads slug has already taken');
        $('#slug').addClass('is-invalid').next('div').text('Combination of ads slug and channel slug has already taken');
      }
    }
  });
});

$('#modDelete').on('show.bs.modal', function (e) {
  let id = $(e.relatedTarget).data("id");
  let name = $(e.relatedTarget).data("name");
  $('#modDelete #id').val(id);
  $('#modDeleteTitle').text('Delete Channel: '+name);
});

$('#formChannelDelete').submit(function(e) { 
  let id = $('#modDelete #id').val();
  e.preventDefault();
  $.ajax({
    url: '/api/channel/'+id,
    type: 'DELETE',
    success: function (data) {
      
      if (data.status) {
        loadData.ajax.reload();
        $("#modDelete").modal("hide");

        swal.fire({
          icon: data.icon,
          title: data.message,
          showConfirmButton: false,
          timer: 4000,
        });
      }
    }
  });
});