@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 d-flex align-items-strech">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                            <div class="mb-3 mb-sm-0">
                                <h5 class="card-title fw-semibold">Bảng thống kê số tiền kiếm được theo từng tháng </h5>
                            </div>
                            <div>
                                <select id="yearSelect" name="year" class="form-select">

                                </select>
                            </div>
                        </div>
                        <div>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title fw-semibold mb-4">Doanh thu của từng sản phẩm</h5>
                            <div class="d-flex align-items-center">
                                <select class="form-select me-3" id="selectMonth">
                                    <option value="" disabled selected>Chọn tháng</option>
                                    <option value="">Tất cả</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"> Tháng {{ $i }}</option>
                                    @endfor
                                </select>
                                <select class="form-select" id="selectYear">
                                    <option value="" selected disabled>Chọn năm</option>
                                    <option value="">Tất cả</option>
                                    @for ($year = date('Y'); $year >= date('Y') - 2; $year--)
                                        <option value="{{ $year }}">
                                            {{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="table_revenue_products">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Thiết kế và phát triển bởi <a href="https://adminmart.com/" target="_blank"
                    class="pe-1 text-primary text-decoration-underline">AdminMart.com</a> Phân phối bởi <a
                    href="https://themewagon.com">ThemeWagon</a></p>
        </div>
    </div>
@endsection
@section('web-script')
    <script>
        const urlGetTotalOrderInYear = "{{ route('admin.getTotalOrderInYear') }}";
        const urlGetRevenueProduct = "{{ route('admin.product.searchRevenueProducts') }}";
        const iconLoading = "{{ asset('admin/assets/images/loading.svg') }}";
        /**
         * Load revenue product list
         */
        function searchRevenueProduct(page = 1, month = null, year = null) {
            $('#table_revenue_products').html(`<div class="d-flex justify-content-center">
                                <img src="${iconLoading}" alt="">
                            </div>`);
            $.ajax({
                url: urlGetRevenueProduct + '?page=' + page,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    month: month,
                    year: year,
                },
            }).done(function(data) {
                $('#table_revenue_products').html(data);
            }).fail(function() {
                notiError();
            });
        }

        $(document).ready(function() {
            searchRevenueProduct();

            // View revenue products by month
            $(document).on('change', '#selectMonth', function() {
                let selectedMonth = $('#selectMonth').val();
                let selectedYear = $('#selectYear').val();
                if (selectedMonth == null || selectedMonth == '') {
                    searchRevenueProduct(1, null, selectedYear);
                } else {
                    searchRevenueProduct(1, selectedMonth, selectedYear);
                }
            });

            // View revenue products by year
            $(document).on('change', '#selectYear', function() {
                let selectedMonth = $('#selectMonth').val();
                let selectedYear = $('#selectYear').val();
                if (selectedYear == null || selectedYear == '') {
                    searchRevenueProduct(1, selectedMonth, null);
                } else {
                    searchRevenueProduct(1, selectedMonth, selectedYear);
                }
            });
        })
    </script>
    <script src="{{ asset('admin/assets/js/dashboard.js') }}"></script>
@endsection
