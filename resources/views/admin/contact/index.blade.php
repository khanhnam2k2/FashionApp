@extends('admin.layouts.app')
@section('content')
    <div class="contact-container container">
        <div class="d-flex justify-content-between">
            <h2>Contact List</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchContact" placeholder="search here..." class="form-control"
                    name="nameCategory">
                <button class="btn btn-primary" onclick="searchContact()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <div class="mt-3">
            <div id="contact_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        const urlDeleteContact = "{{ route('admin.contact.delete', ['id' => ':id']) }}";

        /**
         * Load contact list
         */
        function searchContact(page = 1) {
            $.ajax({
                url: '<?= route('admin.contact.search') ?>?page=' + page,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    searchName: $('#txtSearchContact').val(),
                }
            }).done(function(data) {
                $('#contact_table').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchContact();


            // delete contact
            $(document).on('click', '#btnDeleteContact', function() {
                let contactId = $(this).data('id');
                showConfirmDialog('Are you sure you want to delete this contact?', function() {
                    $.ajax({
                        url: urlDeleteContact.replace(':id', contactId),
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    }).done(function(res) {
                        if (res == 'ok') {
                            notiSuccess("Deleted contact successfully");
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
