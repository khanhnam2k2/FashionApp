<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="#"><img src="{{ asset('img/footer-logo.png') }}" alt=""></a>
                    </div>
                    <p>Khách hàng là trung tâm của mô hình kinh doanh độc đáo của chúng tôi, bao gồm cả thiết kế.</p>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h6>Đường dẫn nhanh</h6>
                    <ul>
                        <li><a href="{{ route('shop.index') }}">Shop</a></li>
                        <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                        <li><a href="{{ route('post.index') }}">Bài viết</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h6>Liên hệ chúng tôi</h6>
                    <ul>
                        <li><a href="#">📞+034398499</a></li>
                        <li><a href="#">📧:Mailfahsion@gmail.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h6>Tin tức mới nhất</h6>
                    <div class="footer__newslatter">
                        <p>Hãy là người đầu tiên biết về hàng mới, sách tra cứu, khuyến mại và khuyến mãi!</p>
                        <form action="#">
                            <input type="text" placeholder="email của bạn">
                            <button type="submit"><span class="icon_mail_alt"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="footer__copyright__text">
                    <p>Bản quyền ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>2020
                        Mọi quyền được bảo lưu | Mẫu này được thực hiện với <i class="fa fa-heart-o"
                            aria-hidden="true"></i> bởi <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
