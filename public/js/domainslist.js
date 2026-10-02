const loadData = $('#dataTable').DataTable( {
    "processing": true,
    "serverSide": true,
    "order":[[4,'desc']],
    columnDefs: [
      { orderable: false, targets: 5 }
    ],
    "ajax": "/api/domains",
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
      url: "/api/domain",
      type: "POST",
      data: {
        id: id
      },
      success: function (data) {
        $("#id").val(data.id);
        $("#name").val(data.name);
        $("#domain").val(data.domain);
        $("#slug").val(data.slug).removeClass('is-invalid');
        $("#footer>div").html(data.footer);
        $('#footer').removeClass('d-none');
      }
    });
  } else { 
    $('#footer').addClass('d-none');
    $("#id").val(0);
    $('#name').val('');
    $('#domain').val('');
    $('#slug').val('').removeClass('is-invalid');
  }
});

$('#formDomain').submit(function(e) { 
  e.preventDefault();
  $.ajax({
    url: '/api/domain/save',
    type: 'POST',
    data: $('#formDomain').serialize(),
    success: function (data) {
      if (data.status) {
        loadData.ajax.reload();
        $("#modDetail").modal("hide");
      }

      if (data.message != 'slug taken') {
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
  $('#modDeleteTitle').text('Delete Domain: '+name);
});

$('#formDomainDelete').submit(function(e) { 
  let id = $('#modDelete #id').val();
  e.preventDefault();
  $.ajax({
    url: '/api/domain/'+id,
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