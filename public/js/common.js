/**
 * show modal dialog confirm
 * @param {String} message message to confirm
 * @param {Function} preConfirmCallback function call back when confirm
 */
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
 * show success message
 * @param {String} mess
 * @param {Function} callback function call back when notification done
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

/**
 * show error message
 * @param {String} mess
 */
function notiError(mess="Something went wrong. Please try again."){
    Swal.fire({
        icon:'error',
        title:'Error',
        text:mess,
    });
};


/**
 * handle image
 * @param {Element} input
 * @param {Element} image
 */
function handleImageUpload(input,image){
    if(input.files && input.files[0]){
        $(image).attr('src',URL.createObjectURL(input.files[0]));
    }
}

/**
 * handle image
 * @param {Number} productId id of product
 * @param {Number} quantity 
 */
function addToCart(productId,quantity){
    $.ajax({
        type: "POST",
        url: globalRouter.urlAddToCart,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            productId: productId,
            quantity: quantity,
        }
    }).done(function(res) {
        const data = res.data.original;
        console.log(data);
        if (data.success) {
            notiSuccess(data.success)
        } else if (data.error) {
            notiError(data.error);
        }
    }).fail(function(err) {
        if (err.status === 401) {
            window.location.href = "{{ route('login') }}";
        }
    }).always(function() {})
}