function showConfirmDialog(message, preConfirmCallback) {
    Swal.fire({
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Ok',
        preConfirm: preConfirmCallback
    });
}

/**
 * show error message
 * @param {*} mess
 */
function notiSuccess(mess = 'Success', callback = function () { }) {
    $('#alert-error').addClass('d-none');
    $('html, body').animate({
        scrollTop: $(".container").offset().top
    });
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: 'success',
        title: mess
    }).then(() => {
        callback();
    });
}

function notiError(mess){
    Swal.fire({
        icon:'error',
        title:'Error',
        text:mess,
    });
};