$("#delMe").on("click", function(e) {
  e.preventDefault();
  let href = $(this).attr('href');
  Swal.fire({
    title: 'Delete Permanently',
    text: "Click 'Delete' if you sure you want to delete your account permanently.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#ccc',
    confirmButtonText: 'Delete',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.value) {
      document.location.href = href;
    }
  });
});