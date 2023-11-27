@extends('layouts.app')
@section('title', 'Liên hệ - Male Fashion')

@section('content')
    <!-- Map Begin -->
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d60464.01274519857!2d105.63839207658813!3d18.7087824997067!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cddf0bf20f23%3A0x86154b56a284fa6d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBWaW5o!5e0!3m2!1svi!2s!4v1696919572063!5m2!1svi!2s"
            height="500" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <!-- Map End -->
    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="contact__text">
                        <div class="section-title">
                            <span>Thông tin</span>
                            <h2>Liên hệ với chúng tôi</h2>
                        </div>
                        <ul>
                            <li>
                                <h4>Nghệ An</h4>
                                <p>182 Lê Duẩn - TP Vinh <br />+84 982-314-0958</p>
                            </li>
                            <li>
                                <h4>Hà Tĩnh</h4>
                                <p>10 Lý Tự Trọng - TP Hà Tĩnh <br />+84 945-423-9893</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__form">
                        <form id="form_contact">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="Tên của bạn">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <textarea name="message" class="form-control" placeholder="Nội dung liên hệ"></textarea>
                                </div>
                                <div class="p-3 w-100">
                                    <button id="btnSubmitContact" class="site-btn w-100">Gửi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->
@endsection
@section('web-script')
    <script>
        $(document).ready(function() {
            $('#btnSubmitContact').on('click', function(e) {
                e.preventDefault();
                const formContact = $('form#form_contact');
                let formData = new FormData(formContact[0]);

                // Remove previous error messages and classes
                formContact.find('.is-invalid').removeClass('is-invalid');
                formContact.find('.invalid-feedback').remove();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('contact.create') }}",
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                }).done(function(data) {
                    if (data == 'ok') {
                        notiSuccess('Cảm ơn phản hồi của bạn.', 'center');
                        formContact[0].reset();
                    }
                }).fail(function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (xhr.status === 400 && errors) {
                        // Loop through the errors and display them
                        $.each(errors, function(key, value) {
                            const inputField = formContact.find('[name="' + key + '"]');
                            inputField.addClass('is-invalid');
                            inputField.after('<div class="invalid-feedback">' + value[0] +
                                '</div>');
                        });
                    } else {
                        notiError();
                    }
                })
            })
        })
    </script>
@endsection
