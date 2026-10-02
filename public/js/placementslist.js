const loadData = $('#dataTable').DataTable( {
    "processing": true,
    "serverSide": true,
    "order":[[6,'desc']],
    columnDefs: [
      { orderable: false, targets: 7 }
    ],
    "ajax": "/api/placements/" + slug,
    language: {
      paginate: {
        next: '<i class="fas fa-chevron-right"></i>',
        previous: '<i class="fas fa-chevron-left"></i>'
      }
    },
  });


$("#modDetail").on("show.bs.modal", function (e) {
  let id = $(e.relatedTarget).data("id")
  $('.alert').addClass('d-none');
  if (id != 0) {
    $.ajax({
      url: "/api/placement",
      type: "POST",
      data: {
        id: id
      },
      success: function (data) {
        $("#id").val(data.id);
        $("#gpt_id").val(data.gpt_id);
        $("#size").val(data.size);
        $('#type').val(data.type);
        $('#channel_id').val(data.channel_id);
        $('#type_id').val(data.type_id);
        $("#footer>div").html(data.footer);
        $('#footer').removeClass('d-none');
      }
    });
  } else { 
    $('#footer').addClass('d-none');
    $("#id").val(0);
    $('#gpt_id').val('');
    $('#size').val('');
    $('#type').val('');
    $('#channel_id').val('');
    $('#type_id').val('');
  }
});

$('#formPlacement').submit(function (e) { 
  e.preventDefault();
  $.ajax({
    url: '/api/placement/save',
    type: 'POST',
    data: $('#formPlacement').serialize(),
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
        $('.alert').removeClass('d-none');
      }
    }
  });
});

$('#modDelete').on('show.bs.modal', function (e) {
  let id = $(e.relatedTarget).data("id");
  let name = $(e.relatedTarget).data("name");
  $('#modDelete #id').val(id);
  $('#modDeleteTitle').text('Delete Placement: '+name);
});

$('#formPlacementDelete').submit(function(e) { 
  let id = $('#modDelete #id').val();
  e.preventDefault();
  $.ajax({
    url: '/api/placement/'+id,
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