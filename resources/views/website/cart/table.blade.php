@if (count($cartItems) > 0)
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__pic">
                                                @php
                                                    $imagesArray = json_decode($item->productImage, true);
                                                @endphp
                                                <img src="{{ Storage::url($imagesArray[0]) }}"
                                                    style="width: 90px;height:90px;object-fit:cover" alt="">
                                            </div>
                                            <div class="product__cart__item__text">
                                                <h6>{{ $item->productName }}</h6>
                                                <h5>${{ $item->productPrice }}</h5>
                                            </div>
                                        </td>
                                        <td class="size__item">
                                            <div class="size">
                                                <div class="pro-size-2">
                                                    <span>{{ $item->size }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="quantity__item">
                                            <div class="quantity">
                                                @if ($item->quantity > $item->quantityAvailable)
                                                    <span class="btn btn-danger">Out Of Stock</span>
                                                @else
                                                    <div
                                                        class="pro-qty-2 d-flex align-items-center justify-content-between w-100 border border-dark">
                                                        <button class="decrement btn rounded-0">-</button>
                                                        <input type="number" data-product-id="{{ $item->productId }}"
                                                            class="qtyProductCart" data-size="{{ $item->size }}"
                                                            value="{{ $item->quantity }}" onkeydown="return false;">
                                                        <button class="increment btn rounded-0">+</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="cart__price">$ {{ $item->total }}</td>
                                        <td class="cart__close"><span class="remove-from-cart"
                                                data-product-id="{{ $item->productId }}" data-size="{{ $item->size }}"
                                                style="cursor: pointer"><i class="fa fa-close"></i></span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                <a href="{{ route('shop.index') }}">Continue Shopping</a>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="cart__total">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Total <span>$<span id="total-cart"> {{ $totalCarts }} </span></span></li>
                        </ul>
                        <a href="{{ route('checkout.index') }}" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <div class="container d-flex align-items-center justify-content-center flex-column" style="min-height: 70vh;">
        <h3>Your shopping cart is empty!</h3>
        <div class="continue__btn mt-3">
            <a href="{{ route('shop.index') }}">Go to Shopping</a>
        </div>
    </div>
@endif
