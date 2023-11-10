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
                                <div class="col-lg-6">
                                    <input type="text" name="name" placeholder="Tên của bạn">
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" placeholder="Email">
                                </div>
                                <div class="col-lg-12">
                                    <textarea name="message" placeholder="Nội dung liên hệ"></textarea>
                                    <button id="btnSubmitContact" class="site-btn">Gửi</button>
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
                let formData = new FormData($('form#form_contact')[0]);
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
                        notiSuccess('Cảm ơn phản hồi của bạn.', 'center', function() {
                            window.location.reload();
                        })
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 400 && xhr.responseJSON.errors) {
                        const errorMessages = xhr.responseJSON.errors;
                        for (let fieldName in errorMessages) {
                            notiError(errorMessages[fieldName][0]);
                        }
                    } else {
                        notiError();
                    }
                })
            })
        })
    </script>
@endsection
