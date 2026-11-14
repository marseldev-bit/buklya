<script src="js/header.js" defer></script>

<div class="headerBlock">
    <header class="cont">
        <a href="?page=main" class="logo">
            <img src="../assets/images/logo.png" alt="">
            <h1>Букля</h1>
        </a>

        <nav class="desktop">
            <a href="?page=main#books">Книги</a>
            <a href="?page=main#collections">Подборки</a>
            <a href="?page=main#authors">Авторы</a>
        </nav>

            <?php if (isset($USER)) { ?>
                <div class="headerOptions desktop">
                    <a href="?page=myCollections" class="headerOption">
                        <svg width="34" height="34" viewBox="0 0 34 34" fill="#FFE0B5" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.1001 0V34L17.0001 28.9L28.9001 34V0H5.1001ZM22.5126 15.7376L17 21.25L11.4876 15.7376C9.96529 14.2153 9.96529 11.7473 11.4876 10.225C13.0097 8.70287 15.4778 8.70274 17 10.225C18.5223 8.70287 20.9904 8.70274 22.5126 10.225C24.0348 11.7473 24.0348 14.2153 22.5126 15.7376Z" />
                        </svg>
                    </a>

                    <a href="?page=profile" class="headerOption">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="#FFE0B5" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_10_21)">
                                <path
                                    d="M20.0026 20.7711C25.7169 20.7711 30.3881 16.0999 30.3881 10.3855C30.3881 4.67123 25.7169 4.57764e-05 20.0026 4.57764e-05C14.2883 4.57764e-05 9.61719 4.67123 9.61719 10.3855C9.61719 16.0999 14.2884 20.7711 20.0026 20.7711Z" />
                                <path
                                    d="M37.8257 29.0704C37.5536 28.3901 37.1908 27.7552 36.7827 27.1656C34.6965 24.0817 31.4766 22.0409 27.8485 21.542C27.395 21.4967 26.8961 21.5873 26.5333 21.8594C24.6285 23.2653 22.361 23.9909 20.0026 23.9909C17.6443 23.9909 15.3768 23.2653 13.472 21.8594C13.1092 21.5873 12.6103 21.4513 12.1568 21.542C8.52869 22.0409 5.26341 24.0817 3.22261 27.1656C2.81445 27.7552 2.4516 28.4355 2.17955 29.0704C2.04353 29.3425 2.08884 29.66 2.22487 29.9321C2.58771 30.567 3.04119 31.202 3.44935 31.7462C4.08424 32.6079 4.76454 33.3788 5.53554 34.1044C6.17044 34.7393 6.89605 35.3289 7.62174 35.9185C11.2045 38.5942 15.5129 40.0001 19.9573 40.0001C24.4018 40.0001 28.7102 38.5942 32.2929 35.9185C33.0185 35.3743 33.7441 34.7393 34.3791 34.1044C35.1047 33.3788 35.8303 32.6078 36.4653 31.7462C36.9188 31.1566 37.327 30.567 37.6898 29.9321C37.9164 29.66 37.9618 29.3424 37.8257 29.0704Z" />
                            </g>
                            <defs>
                                <clipPath id="clip0_10_21">
                                    <rect width="40" height="40" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </div>
            <?php } else { ?>
                <div class="guestOptions desktop">
                    <a href="?page=reg">Регистрация</a>
                    <a href="?page=login">Войти</a>
                </div>
            <?php } ?>

            <div class="burgerLabel mobile tablet">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
    </header>
</div>

<div class="burger">
    <nav class="cont">
        <a href="?page=main#books">Книги</a>
        <a href="?page=main#collections">Подборки</a>
        <a href="?page=main#authors">Авторы</a>
        <?php if(isset($USER)) { ?>
        <a href="?page=profile">Профиль</a>
        <a href="?page=myCollections">Мои подборки</a>
        <?php } else { ?>
        <a href="?page=reg">Регистрация</a>
        <a href="?page=login">Войти</a>
        <?php } ?>
    </nav>
</div>