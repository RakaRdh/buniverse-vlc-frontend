$(document).ready(function() {
  $('#dataTable').DataTable( {
    "processing": true,
    "serverSide": true,
    "order":[[3,'desc']],
    columnDefs: [
      { orderable: false, targets: 4 }
    ],
    "ajax": "/api/admins",
    language: {
      paginate: {
        next: '<i class="fas fa-chevron-right"></i>',
        previous: '<i class="fas fa-chevron-left"></i>'
      }
    },
  });
});

$(document).on("click", ".btn-danger", function (e) {
  e.preventDefault();
  const form = $(this).closest("form");

  Swal.fire({
      title: 'Delete Admin',
      text: "Are you sure you want to delete this user? If you do, click 'Delete' button.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#ccc',
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
  }).then((result) => {
      if (result.value) {
        form.submit();
      }
  });
});