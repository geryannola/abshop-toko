<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ env('nama_toko') }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ env('nama_toko') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-lg-0 mb-2 me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('products') }}">Semua Product</a></li>
                            <li><a class="dropdown-item" href="#">New Arrivals</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" id="products-search" action="{{ url('products') }}" method="GET">
                    <input class="form-control me-2" name="cari" type="search" value="{{ Request::get('cari') }}"
                        placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <form class="d-flex">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark rounded-pill ms-1 text-white">0</span>
                    </button>
                </form>
                <form class="d-flex" action={{ url('login') }}>
                    <button class="btn btn-outline-light" type="submit">Login</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Header-->
    <header class="bg-dark container-fluid py-5">
        <div class="px-lg-5 container my-5 px-4">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">{{ env('nama_toko') }}</h1>
                <p class="lead fw-normal text-white-50 mb-0">{{ env('slogan_toko') }}</p>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <section class="content">

        @yield('content')


    </section>
    <!-- /.content -->
    <!-- Footer-->
    <footer class="bg-dark py-5">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; ABkreator.com 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
