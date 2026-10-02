const loadData = $('#dataTable').DataTable( {
    "processing": true,
    "serverSide": true,
    "order":[[3,'desc']],
    columnDefs: [
      { orderable: false, targets: 4 }
    ],
    "ajax": "/api/types",
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
      url: "/api/type",
      type: "POST",
      data: {
        id: id
      },
      success: function (data) {
        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#slug").val(data.slug);
        $("#footer>div").html(data.footer);
        $('#footer').removeClass('d-none');
      }
    });
  } else { 
    $('#footer').addClass('d-none');
    $("#id").val(0);
    $('#name').val('');
    $('#slug').val('').removeClass('is-invalid');
  }
});

$('#formType').submit(function(e) { 
  e.preventDefault();
  $.ajax({
    url: '/api/type/save',
    type: 'POST',
    data: $('#formType').serialize(),
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
        $('#slug').addClass('is-invalid').next('div').text('Slug has already taken');
      }
    }
  });
});

$('#modDelete').on('show.bs.modal', function (e) {
  let id = $(e.relatedTarget).data("id");
  let name = $(e.relatedTarget).data("name");
  $('#modDelete #id').val(id);
  $('#modDeleteTitle').text('Delete Position: '+name);
});

$('#formTypeDelete').submit(function(e) { 
  let id = $('#modDelete #id').val();
  e.preventDefault();
  $.ajax({
    url: '/api/type/'+id,
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