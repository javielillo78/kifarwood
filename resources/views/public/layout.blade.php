<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>@yield('title', __('site.brand'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <style>
    :root{
      --brand:#bc2225;
      --bg:#1d1b1d;
      --bg-2:#2a272a;
      --text:#f7f7f7;
      --muted:#cfcfcf;
      --ring:#ff8a8a;
    }
    html,body{height:100%}
    body{
      margin:0; color:var(--text);
      background:
        radial-gradient(1200px 700px at 10% -10%, rgba(188,34,37,.35), transparent 60%),
        radial-gradient(900px 600px at 110% 20%, rgba(188,34,37,.25), transparent 65%),
        linear-gradient(180deg, var(--bg), var(--bg-2));
      font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",sans-serif;
      min-height:100vh;
      background-repeat:no-repeat;
      background-color: var(--bg-2);
      display:flex;
      flex-direction:column;
    }
    a{color:inherit;text-decoration:none}
    .container-wide{max-width:1320px}
    .navbar{
      background:linear-gradient(90deg, rgba(188,34,37,.18) 0%, rgba(29,27,29,.92) 22%, rgba(29,27,29,.72) 100%);
      backdrop-filter:saturate(140%) blur(10px);
      border-bottom:1px solid rgba(255,255,255,.08);
      position:sticky; top:0; z-index:1000;
      padding-top:.55rem; padding-bottom:.55rem;
    }
    .navbar::after{
      content:""; position:absolute; inset:auto 0 -2px 0; height:2px;
      background:linear-gradient(90deg, transparent, var(--brand), transparent);
      opacity:.6;
    }
    .navbar .navbar-brand{
      display:flex; align-items:center; gap:12px;
      font-weight:900; letter-spacing:.2px; color:#fff;
      text-shadow:none !important;
      font-size:1.25rem;
    }
    .brand-typo{
      font-family: "Alfa Slab One", serif; 
      font-weight: 400;              
      line-height: 1;
      letter-spacing: .2px;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
      color:#f3f3f3;                
    }
    .navbar .navbar-brand img{
      width:56px; height:56px; border-radius:14px; object-fit:cover;
      box-shadow:0 0 0 2px rgba(255,255,255,.08), 0 12px 28px rgba(188,34,37,.28);
      transition:transform .25s ease;
    }
    .navbar .navbar-brand:hover img{transform:translateY(-1px) scale(1.03)}
    .navbar-nav{align-items:center}
    .nav-link{
      color:#ececec!important; opacity:.92; position:relative;
      padding:.55rem .9rem; border-radius:12px; transition:all .2s ease;
      font-weight:600; letter-spacing:.15px;
    }
    .nav-link:hover,.nav-link.active{
      opacity:1; color:#fff!important;
      background:rgba(255,255,255,.06);
      box-shadow:inset 0 0 0 1px rgba(255,255,255,.14);
    }
    .nav-link::after{
      content:""; position:absolute; left:14%; right:14%; bottom:6px; height:2px;
      background:linear-gradient(90deg, transparent, var(--brand), transparent);
      transform:scaleX(0); transform-origin:center; transition:.22s ease;
    }
    .nav-link:hover::after,.nav-link.active::after{transform:scaleX(1); will-change:transform}
    .btn-login{
      border:1px solid rgba(255,255,255,.18); color:#fff; background:rgba(255,255,255,.05);
      backdrop-filter:blur(6px); border-radius:14px; padding:.45rem .8rem; font-weight:600;
    }
    .btn-login:hover{background:rgba(255,255,255,.09)}
    .btn-primary{
      background:linear-gradient(135deg, var(--brand), #e13a3d);
      border:0; border-radius:14px; box-shadow:0 10px 26px rgba(188,34,37,.35);
      padding:.45rem .9rem; font-weight:700;
    }
    .btn-primary:hover{filter:brightness(1.05)}
    .flag{width:26px; height:26px; border-radius:50%; overflow:hidden; border:1px solid rgba(255,255,255,.25)}
    main{
      flex:1 0 auto;
    }
    main.container{padding-top:40px; padding-bottom:48px}
    .glass{
      background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
      border:1px solid rgba(255,255,255,.10);
      border-radius:16px;
      box-shadow:0 10px 24px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
    }
    footer{
      border-top:1px solid rgba(255,255,255,.08);
      background:linear-gradient(90deg, rgba(29,27,29,.85), rgba(29,27,29,.65));
      margin-top:auto;
    }
    .divider-thin{width:1px; height:24px; background:rgba(255,255,255,.12)}
    .navbar-toggler{border-color:rgba(255,255,255,.18); padding:.35rem .5rem}
    .navbar-dark .navbar-toggler-icon{filter:drop-shadow(0 0 6px rgba(188,34,37,.35))}
    @media (min-width:992px){
      .navbar-nav>.nav-item{margin-left:.15rem; margin-right:.15rem}
    }
        /* popup */
    #kf-toast-container {
      position: fixed;
      top: 90px;
      right: 110px;
      z-index: 1080;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .kf-toast {
      min-width: 260px;
      max-width: 340px;
      padding: 12px 14px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,.55);
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: .9rem;
      animation: kf-toast-in .25s ease-out;
      background: linear-gradient(
        145deg,
        rgba(29,27,29,.96),
        rgba(42,39,42,.96)
      );
      border: 1px solid rgba(255,255,255,.10);
      color: var(--text);
    }

    .kf-toast__icon {
      margin-top: 2px;
    }

    .kf-toast__body {
      flex: 1;
    }

    .kf-toast__close {
      border: 0;
      background: transparent;
      color: inherit;
      opacity: .7;
      cursor: pointer;
      padding: 0 0 0 6px;
    }

    .kf-toast__close:hover {
      opacity: 1;
    }

    .kf-toast--success {
      border-color: rgba(40,167,69,.70);
      box-shadow: 0 0 0 1px rgba(40,167,69,.28), 0 10px 25px rgba(0,0,0,.55);
      color: #d6ffe6;
    }

    .kf-toast--error {
      border-color: rgba(188,34,37,.90); 
      box-shadow: 0 0 0 1px rgba(188,34,37,.40), 0 10px 25px rgba(0,0,0,.6);
      color: #ffd5da;
    }

    .kf-toast--warning {
      border-color: rgba(255,193,7,.80);
      box-shadow: 0 0 0 1px rgba(255,193,7,.32), 0 10px 25px rgba(0,0,0,.55);
      color: #fff3cd;
    }

    @keyframes kf-toast-in {
      from { opacity: 0; transform: translateY(-8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    /* boton arriba */
    .kf-back-to-top {
    position: fixed;
    bottom: 22px;
    right: 22px;
    z-index: 1050;
    width: 48px;
    height: 48px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.16);
    background:
      radial-gradient(circle at 30% 20%, rgba(255,255,255,.14), transparent 60%),
      linear-gradient(135deg, var(--brand), var(--bg-2));
    color: var(--text);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 30px rgba(0,0,0,.55);
    cursor: pointer;
    transition:
      transform .22s ease-out,
      box-shadow .22s ease-out,
      background .22s ease-out,
      border-color .22s ease-out,
      opacity .18s ease-out;
    opacity: .96;
  }

  .kf-back-to-top__icon {
    width: 22px;
    height: 22px;
    transition: transform .22s ease-out;
  }

  .kf-back-to-top__glow {
    position: absolute;
    inset: 0;
    border-radius: inherit;
    background: radial-gradient(circle, color-mix(in srgb, var(--brand) 55%, transparent), transparent 70%);
    filter: blur(8px);
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s ease-out;
  }

  .kf-back-to-top:hover,
  .kf-back-to-top:focus-visible {
    outline: none;
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 18px 40px rgba(0,0,0,.7);
    background:
      radial-gradient(circle at 30% 20%, rgba(255,255,255,.18), transparent 60%),
      linear-gradient(135deg, var(--brand), var(--ring));
    border-color: var(--ring);
  }

  .kf-back-to-top:hover .kf-back-to-top__icon,
  .kf-back-to-top:focus-visible .kf-back-to-top__icon {
    transform: translateY(-1px);
  }

  .kf-back-to-top:hover .kf-back-to-top__glow,
  .kf-back-to-top:focus-visible .kf-back-to-top__glow {
    opacity: 1;
  }

  @media (max-width: 576px) {
    .kf-back-to-top {
      width: 44px;
      height: 44px;
      bottom: 16px;
      right: 16px;
      opacity: .9;
    }
  }
  </style>
  @yield('head')
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container container-wide">
      <a class="navbar-brand" href="{{ route('public.index') }}">
        <img src="{{ asset('images/logo_blanco.png') }}" alt="logo">
        <span class="brand-typo">{{ __('site.brand') }}</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ml-auto align-items-lg-center">
          {{-- Enlaces principales --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('public.index') ? 'active' : '' }}" href="{{ route('public.index') }}">{{ __('site.nav.home') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('public.productos.*') ? 'active' : '' }}" href="{{ route('public.productos.index') }}">{{ __('site.nav.products') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('public.servicios.*') ? 'active' : '' }}" href="{{ route('public.servicios.index') }}">{{ __('site.nav.services') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  {{ request()->routeIs('public.contacto.*') ? 'active' : '' }}" href="{{ route('public.contacto.index') }}">{{ __('site.nav.contact') }}</a>
          </li>

          <li class="nav-item d-none d-lg-block mx-2"><span class="divider-thin d-inline-block"></span></li>
          {{-- Banderas --}}
          <li class="nav-item">
            <a class="nav-link p-0" href="{{ route('locale.switch','es') }}" title="Español">
                <img width="30" height="30" src="https://img.icons8.com/color/48/spain-circular--v1.png" alt="ES">
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link p-0" href="{{ route('locale.switch','en') }}" title="English">
                <img width="30" height="30" src="https://img.icons8.com/color/48/great-britain-circular.png" alt="EN">
            </a>
          </li>
          {{-- Cesta visible para todos --}}
          @php
            $cartCount = 0;
            if (auth()->check()) {
                $pedido = \App\Models\Pedido::where('user_id', auth()->id())
                          ->where('estado','carrito')->first();
                $cartCount = $pedido?->detalles()->sum('cantidad') ?? 0;
            } else {
                try { $cart = json_decode(request()->cookie('cart','[]'), true) ?: []; } catch (\Throwable $e) { $cart = []; }
                foreach ($cart as $it) { $cartCount += (int)($it['qty'] ?? 0); }
            }
          @endphp
          <li class="nav-item">
            <a class="nav-link position-relative" href="{{ route('public.cesta.index') }}" title="Cesta">
              <i class="fa fa-shopping-basket fa-lg"></i>
              @if($cartCount > 0)
                <span class="badge badge-danger" style="position:absolute; top:2px; right:2px; font-size:.65rem; border-radius:10px;">
                  {{ $cartCount }}
                </span>
              @endif
            </a>
          </li>
          {{-- Auth --}}
          @guest
            <li class="nav-item ml-2">
              <a class="btn btn-sm btn-login d-flex align-items-center" href="{{ route('login') }}">
                <i class="fa-regular fa-user mr-1"></i> {{ __('site.auth.login') }}
              </a>
            </li>
            <li class="nav-item ml-2">
              <a class="btn btn-sm btn-primary d-flex align-items-center" href="{{ route('register') }}">
                {{ __('site.auth.register') }}
              </a>
            </li>
            {{-- Icono de exclamación --}}
            <li class="nav-item ml-2">
              <a class="nav-link p-2 d-flex align-items-center" href="https://youtu.be/mhnck6M9fG8" target="_blank" title="Información">
                <i class="fa fa-exclamation-circle fa-lg"></i>
              </a>
            </li>
          @else
            <li class="nav-item dropdown ml-2">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDrop" data-toggle="dropdown">
                <i class="fa-regular fa-circle-user fa-lg mr-1"></i> {{ Auth::user()->name }}
              </a>
              <div class="dropdown-menu dropdown-menu-right bg-dark border-0 glass">
                @if(Auth::user()->rol === 'admin')
                  <a class="dropdown-item text-light" href="{{ route('admin.dashboard') }}"><i class="fa fa-toolbox mr-2"></i> {{ __('site.auth.admin') }}</a>
                @endif
                <a class="dropdown-item text-light" href="{{ route('public.pedidos.index') }}">
                    <i class="fas fa-shopping-bag mr-2"></i> {{ __('site.nav.my_orders') }}
                </a>
                <div class="dropdown-divider" style="border-top-color:rgba(255,255,255,.12)"></div>
                <form action="{{ route('logout') }}" method="POST" class="px-3 pb-2">
                  @csrf
                  <button class="btn btn-sm btn-outline-light btn-block">
                    <i class="fa fa-right-from-bracket mr-2"></i> {{ __('site.auth.logout') }}
                  </button>
                </form>
              </div>
            </li>
            {{-- Icono de exclamación --}}
            <li class="nav-item ml-2">
              @if(Auth::user()->rol === 'admin')
                <a class="nav-link p-2 d-flex align-items-center" href="https://youtu.be/ZCZKBWpMbjk" target="_blank" title="Información">
                  <i class="fa fa-exclamation-circle fa-lg"></i>
                </a>
              @else
                <a class="nav-link p-2 d-flex align-items-center" href="https://youtu.be/mhnck6M9fG8" target="_blank" title="Información">
                  <i class="fa fa-exclamation-circle fa-lg"></i>
                </a>
              @endif
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
  {{-- popup --}}
  <div id="kf-toast-container"></div>
  <main class="container">
    @if(session('login_ok'))
      <div class="alert alert-success mt-3">
        {{ session('login_ok') }}
      </div>
    @endif
    @yield('content')
  </main>
    <footer class="mt-5" style="border-top:1px solid rgba(255,255,255,.08); background:linear-gradient(135deg, rgba(16,15,16,.96), rgba(16,15,16,.90));">
        <div class="container py-4 py-md-5">
            <div style="max-width:1080px;margin:0 auto;">
                <div class="row justify-content-between align-items-start text-center text-md-left">
                    <div class="col-md-4 mb-4 d-flex flex-column align-items-center align-items-md-start">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/logo_blanco.png') }}" alt="logo"
                                style="width:44px;height:44px;border-radius:14px;object-fit:cover;margin-right:10px;box-shadow:0 8px 18px rgba(0,0,0,.45);">
                            <div>
                                <div style="font-weight:800;font-size:1.05rem;letter-spacing:.04em;">
                                    {{ __('site.brand') }}
                                </div>
                                <small class="text-muted d-block" style="font-size:.78rem;">
                                    {{ __('site.footer.tagline') }}
                                </small>
                            </div>
                        </div>
                        <p class="text-muted mb-3" style="font-size:.85rem;max-width:320px;">
                            {{ __('site.footer.description') }}
                        </p>
                        <div class="d-flex justify-content-center justify-content-md-start" style="gap:12px;font-size:1.1rem;">
                            <a href="https://www.instagram.com" target="_blank" rel="noopener"
                              class="text-muted d-inline-flex align-items-center justify-content-center"
                              style="width:32px;height:32px;border-radius:50%;border:1px solid rgba(255,255,255,.12);">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com" target="_blank" rel="noopener"
                              class="text-muted d-inline-flex align-items-center justify-content-center"
                              style="width:32px;height:32px;border-radius:50%;border:1px solid rgba(255,255,255,.12);">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4 d-flex flex-column align-items-center">
                        <h6 class="text-uppercase text-muted mb-3"
                            style="letter-spacing:.12em;font-size:.75rem;text-align:center;">
                            {{ __('site.footer.explore') }}
                        </h6>
                        <ul class="list-unstyled mb-0" style="font-size:.9rem;text-align:center;">
                            <li class="mb-2">
                                <a href="{{ route('public.index') }}" class="text-light-50 d-inline-flex align-items-center">
                                    {{ __('site.nav.home') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.productos.index') }}" class="text-light-50 d-inline-flex align-items-center">
                                    {{ __('site.nav.products') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.servicios.index') }}" class="text-light-50 d-inline-flex align-items-center">
                                    {{ __('site.nav.services') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.contacto.index') }}" class="text-light-50 d-inline-flex align-items-center">
                                    {{ __('site.nav.contact') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-4 mb-4 d-flex flex-column align-items-center align-items-md-end text-center text-md-right">
                        <h6 class="text-uppercase text-muted mb-3"
                            style="letter-spacing:.12em;font-size:.75rem;">
                            {{ __('site.footer.contact') }}
                        </h6>
                        <p class="text-muted mb-1" style="font-size:.85rem;">
                            <i class="fa fa-location-dot mr-2"></i>
                            Fuente Palmera (Córdoba), España
                        </p>
                        <p class="text-muted mb-1" style="font-size:.85rem;">
                            <i class="fa fa-envelope mr-2"></i>
                            {{ config('mail.contact_to', config('mail.from.address')) }}
                        </p>
                        <p class="text-muted mb-3" style="font-size:.85rem;">
                            <i class="fa fa-clock mr-2"></i>
                            L–V · 7:00–19:00 · Sábados 7:00–14:00
                        </p>
                        <div class="d-flex justify-content-center justify-content-md-end w-100">
                            <a href="{{ route('public.contacto.index') }}" class="btn btn-login btn-sm">
                                <i class="fa fa-paper-plane mr-1"></i> {{ __('site.footer.send_message') }}
                            </a>
                        </div>
                    </div>
                </div>
                <hr class="mt-3 mb-3" style="border-color:rgba(255,255,255,.08);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-left"
                    style="font-size:.8rem;">
                    <div class="text-muted mb-2 mb-md-0">
                        © {{ date('Y') }} {{ __('site.brand') }} — {{ __('site.footer.copy') }}
                    </div>
                    <div class="text-muted d-flex flex-wrap justify-content-center">
                        <a href="#" class="text-muted mx-2">{{ __('site.footer.legal_notice') }}</a>
                        <span class="d-none d-sm-inline">·</span>
                        <a href="#" class="text-muted mx-2">{{ __('site.footer.privacy') }}</a>
                        <span class="d-none d-sm-inline">·</span>
                        <a href="#" class="text-muted mx-2">{{ __('site.footer.cookies') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <a href="#top"
          aria-label="{{ __('site.common.back_to_top') }}"
          class="kf-back-to-top"
          onclick="window.scrollTo({ top: 0, behavior: 'smooth' }); return false;">
          <span class="kf-back-to-top__glow"></span>
          <svg class="kf-back-to-top__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7 7 7M12 3v18"/>
          </svg>
        </a>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>
    <script>
      window.KFToast = function (type, message, options) {
        options = options || {};
        var duration = options.duration || 4000;

        var container = document.getElementById('kf-toast-container');
        if (!container) return;

        var el = document.createElement('div');
        el.className = 'kf-toast kf-toast--' + (type || 'success');

        var iconHtml = '';
        if (type === 'error') {
          iconHtml = '<i class="fa fa-circle-exclamation"></i>';
        } else if (type === 'warning') {
          iconHtml = '<i class="fa fa-exclamation-triangle"></i>';
        } else {
          iconHtml = '<i class="fa fa-check-circle"></i>';
        }

        el.innerHTML =
          '<div class="kf-toast__icon">' + iconHtml + '</div>' +
          '<div class="kf-toast__body">' + message + '</div>' +
          '<button type="button" class="kf-toast__close" aria-label="Cerrar">' +
            '&times;' +
          '</button>';

        el.querySelector('.kf-toast__close').addEventListener('click', function () {
          el.remove();
        });

        container.appendChild(el);

        setTimeout(function () {
          if (el && el.parentNode) {
            el.remove();
          }
        }, duration);
      };
    </script>
    @if(session('cart_ok'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          if (window.KFToast) {
            KFToast('success', @json(session('cart_ok')));
          }
        });
      </script>
    @endif
    @php
      $cartErr = session('cart_err');
    @endphp
    @if($cartErr && strpos($cartErr, 'Se ha ajustado a la cantidad máxima disponible') === false)
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          if (window.KFToast) {
            KFToast('error', @json($cartErr));
          }
        });
      </script>
    @endif
    @yield('scripts')
  </body>
  </html>