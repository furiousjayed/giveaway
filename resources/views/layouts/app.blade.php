<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    {{ $title }}@if ($title !== config('app.name', 'Giveaway App'))
      | {{ config('app.name', 'Giveaway App') }}
    @endif
  </title>

  <meta name="description" content="{{ $metaDescription }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="canonical" href="{{ $canonical }}">

  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $metaDescription }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ $canonical }}">

  @if ($metaImage)
    <meta property="og:image" content="{{ $metaImage }}">
  @endif

  {{-- Bootstrap CDN --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons CDN --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Google Font --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  {{-- Custom Public CSS --}}
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

  @stack('styles')
</head>

<body class="{{ $bodyClass }}">
  <div class="public-site-wrapper">

    <header class="public-navbar sticky-top">
      <nav class="navbar navbar-expand-lg">
        <div class="container">

          <a href="{{ Route::has('giveaways.index') ? route('giveaways.index') : url('/') }}" class="navbar-brand public-brand">

            <span class="public-brand-mark">
              G
            </span>

            <span class="public-brand-text">
              <span class="public-brand-name">
                {{ config('app.name', 'Giveaway App') }}
              </span>

              <span class="public-brand-subtitle">
                Win exciting prizes
              </span>
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div id="publicNavbar" class="collapse navbar-collapse">

            <ul class="navbar-nav mx-auto public-nav-links">

              <li class="nav-item">
                <a href="#" class="nav-link active">
                  Giveaways
                </a>
              </li>

              {{-- @auth --}}
              <li class="nav-item">
                <a href="" class="nav-link">
                  My Entries
                </a>
              </li>
              {{-- @endauth --}}

            </ul>

            <div class="public-navbar-actions">


              <a href="#" class="btn btn-app-primary">
                Dashboard
              </a>

              <a href="{{ route('filament.admin.auth.login') }}" class="btn btn-app-link">
                Login
              </a>

              <a href="#" class="btn btn-app-primary">
                Register
              </a>

            </div>

          </div>
        </div>
      </nav>
    </header>

    <main class="public-main">

      <div class="container public-alert-container">

        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            {{ session('error') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger rounded-4" role="alert">
            <p class="alert-title">
              Please fix the following errors:
            </p>

            <ul class="alert-list">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

      </div>

      {{ $slot }}

    </main>

    <footer class="public-footer">
      <div class="container">
        <div class="row align-items-center gy-3">

          <div class="col-md-6 text-center text-md-start">
            <p class="public-footer-copy">
              © {{ date('Y') }} {{ config('app.name', 'Giveaway App') }}. All rights reserved.
            </p>
          </div>

          <div class="col-md-6">
            <div class="public-footer-links">

              <a href="#">
                Giveaways
              </a>

              <a href="#">
                My Entries
              </a>

            </div>
          </div>

        </div>
      </div>
    </footer>

  </div>

  {{-- Bootstrap JS CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>

</html>
