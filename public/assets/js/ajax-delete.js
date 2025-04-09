function confirmDelete(url, key, token) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: '_token=' + token,
                success: function (data) {

                    $('#row_' + key).remove();
                    Swal.fire(
                        'Deleted!',
                        'Data has been deleted.',
                        'success'
                    )

                },
                error:function (xhr, ajaxOptions, thrownError){
                    switch (xhr.status) {
                        case 404:
                            Swal.fire(
                                xhr.statusText,
                                'Page Not Found',
                                'error'
                            )
                        break;
                        case 403:
                            Swal.fire(
                                xhr.statusText,
                                xhr.responseJSON.msg || 'Permission Denied',
                                'warning'
                            )
                            break;
                        case 419:
                            Swal.fire(
                                xhr.statusText,
                                'Session Expired',
                                'info'
                            )
                            break;
                        default:
                            Swal.fire(
                                xhr.statusText,
                                'There are some error. Please contact developer',
                                'error'
                            )
                    }

                }

            });

        }
    })
}
