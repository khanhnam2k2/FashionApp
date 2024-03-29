@extends('admin.layouts.app')
@section('content')
    <div class="contact-container container">
        <div class="d-flex justify-content-between">
            <h2>Danh sách liên hệ</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchContact" placeholder="Tìm kiếm ở đây..." class="form-control"
                    name="nameCategory">
            </div>
        </div>
        <div class="mt-3">
            <div id="contact_table">

            </div>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        const urlDeleteContact = "{{ route('admin.contact.delete', ['id' => ':id']) }}";
        const iconLoadingContact = "{{ asset('admin/assets/images/loading.svg') }}";

        /**
         * Load contact list
         * @param page current page
         * @param searchName name of the search
         */
        function searchContact(page = 1, searchName = '') {
            $('#contact_table').html(`<div class="d-flex justify-content-center mt-5">
                                <img src="${iconLoadingContact}" alt="">
                            </div>`);
            $.ajax({
                url: '<?= route('admin.contact.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: searchName,
                }
            }).done(function(data) {
                $('#contact_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {

            searchContact();

            // Event enter keyword search
            $('#txtSearchContact').keyup(debounce(function(e) {
                let search = e.currentTarget.value ?? '';
                if (search != '') {
                    searchContact(1, search);
                } else {
                    searchContact();
                }
            }, 500));

            // Delete contact
            $(document).on('click', '#btnDeleteContact', function() {
                let contactId = $(this).data('id');
                showConfirmDialog('Bạn có chắc chắn muốn xóa địa chỉ liên hệ này không?', function() {
                    $.ajax({
                        url: urlDeleteContact.replace(':id', contactId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Đã xóa liên hệ thành công");
                            searchContact();
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

        });
    </script>
@endsection
