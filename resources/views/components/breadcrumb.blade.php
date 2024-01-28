@props(['route1', 'route2', 'nameRoute1', 'nameRoute2', 'current'])

<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>{{ $current }}</h4>
                    <div class="breadcrumb__links">
                        @isset($route1, $nameRoute1)
                            <a href="{{ route($route1) }}">{{ $nameRoute1 }}</a>
                        @endisset

                        @isset($route2, $nameRoute2)
                            <a href="{{ route($route2) }}">{{ $nameRoute2 }}</a>
                        @endisset

                        <span>{{ $current }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
