<!-- layout/master.blade.php -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    @section('title')
    <title>{!! e($title, false) !!}</title>
    @show @section('keywords')
    <meta name="Keywords" content="{!! e($keywords, false) !!}" />
    @show @section('description')
    <meta name="Description" content="{!! e($description, false) !!}" />
    @show
    <!-- Stylesheets -->
    <link
      href="{{ url('/') }}/public/assets/main/css/bootstrap.css"
      rel="stylesheet"
    />
    <link
      href="{{ url('/') }}/public/assets/main/css/style.css"
      rel="stylesheet"
    />
    <link
      href="{{ url('/') }}/public/assets/main/css/responsive.css"
      rel="stylesheet"
    />
    <link
      href="{{ url('/') }}/public/assets/main/css/sasa-ui.css"
      rel="stylesheet"
    />
    <!-- Premium light UI (scoped to body.sx-site) -->
    <link
      href="{{ url('/') }}/public/assets/main/css/sasa-premium.css"
      rel="stylesheet"
    />
    <link href="{{ url('/') }}/public/assets/main/css/sasa-nav.css" rel="stylesheet">
	<link href="{{ url('/') }}/public/assets/main/css/sasa-luxe.css" rel="stylesheet">

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Inter:wght@400;500;600;700&amp;family=Outfit:wght@500;600;700&amp;display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Outfit:wght@500;600;700&amp;display=swap"
      rel="stylesheet"
    />

    <link
      rel="shortcut icon"
      href="{{ url('/') }}/public/assets/main/images/favicon.png"
      type="image/x-icon"
    />
    <link
      rel="icon"
      href="{{ url('/') }}/public/assets/main/images/favicon.png"
      type="image/x-icon"
    />

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script>
      var path_url = "{{ url('/') }}";
      var path = "{{ url('/') }}";
    </script>

    @section('customstyle') @show

    <style>
      .field_error {
        border: 1px solid #c30 !important;
      }
    </style>
  </head>
  <body class="sx-site">
    <div class="page-wrapper">
      <a
        href="https://wa.me/+923202956541"
        class="float"
        target="_blank"
        rel="noopener"
        aria-label="Chat with us on WhatsApp"
      >
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path
            d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.27-1.38a9.9 9.9 0 0 0 4.77 1.22h.01c5.46 0 9.91-4.45 9.91-9.91A9.85 9.85 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23a8.2 8.2 0 0 1 8.23 8.24c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.13-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.43.12-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.42h-.48c-.17 0-.43.06-.66.31-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.16-.48-.29z"
          />
        </svg>
      </a>

      @section('header') @show @section('breadcrumbs') @show @yield('content')
      @section('footer') @show

      <script src="{{ url('/') }}/public/assets/main/js/jquery.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/popper.min.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/bootstrap.min.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/magnific-popup.min.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/appear.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/parallax.min.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/tilt.jquery.min.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/jquery.paroller.min.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/owl.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/wow.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/isotope.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/odometer.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/mixitup.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/backToTop.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/nav-tool.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/swiper.min.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/jquery-ui.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/TweenMax.min.js"></script>
      <script src="{{ url('/') }}/public/assets/main/js/script.js"></script>
      <script src="{{
          url('/')
        }}/public/assets/main/js/sasa-premium.js"></script>

      <link
        href="{{ url('/') }}/public/assets/main/toast/toast.css"
        rel="stylesheet"
        type="text/css"
      />
      <script
        type="text/javascript"
        src="{{ url('/') }}/public/assets/main/toast/toast.js"
      ></script>

      <script>
        function go_to(url) {
          window.location = url;
        }
      </script>

      @section('customscript') @show
    </div>
  </body>
</html>
