@extends('public.layout')

@section('title', __('site.checkout.title') . ' · ' . __('site.brand'))

@section('content')
<h1 class="mb-3" style="font-weight:800">{{ __('site.checkout.title') }}</h1>

@if(!count($items))
    <div class="glass p-4">
        <p class="mb-0 text-muted">{{ __('site.checkout.empty_cart') }}</p>
    </div>
@else
    <div class="glass p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('site.checkout.product') }}</th>
                        <th style="width:140px" class="text-right">{{ __('site.checkout.price') }} (IVA inc.)</th>
                        <th style="width:120px" class="text-center">{{ __('site.checkout.quantity') }}</th>
                        <th style="width:140px" class="text-right">{{ __('site.checkout.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center" style="gap:12px">
                                    <img src="{{ $it['img'] ?? asset('images/place.png') }}" alt="{{ $it['name'] }}"
                                        style="width:64px;height:48px;object-fit:cover;border-radius:8px">
                                    <div class="font-weight-bold">{{ $it['name'] }}</div>
                                </div>
                            </td>
                            <td class="align-middle text-right">{{ number_format($it['price'],2,',','.') }} €</td>
                            <td class="align-middle text-center">{{ $it['qty'] }}</td>
                            <td class="align-middle text-right">{{ number_format($it['subtotal'],2,',','.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('site.checkout.tax_base') }}</th>
                        <th class="text-right">{{ number_format($base,2,',','.') }} €</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('site.checkout.vat') }} (21%)</th>
                        <th class="text-right">{{ number_format($iva,2,',','.') }} €</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('site.checkout.total') }} (IVA inc.)</th>
                        <th class="text-right">{{ number_format($total,2,',','.') }} €</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @guest
            <div class="alert alert-warning mt-3 mb-0">
                {{ __('site.checkout.login_required') }}
                <div class="mt-2">
                    <a href="{{ route('login', ['redirect_to' => route('public.cesta.checkout')]) }}" class="btn btn-primary btn-sm">
                        {{ __('site.checkout.login_continue') }}
                    </a>
                </div>
            </div>
        @else
            <div class="mt-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#facturacionModal">
                    {{ __('site.checkout.pay') }}
                </button>
            </div>

            <div class="modal fade" id="facturacionModal" tabindex="-1" role="dialog" aria-labelledby="facturacionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header">
                            <h5 class="modal-title" id="facturacionModalLabel">{{ __('site.checkout.billing_data') }}</h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="payment-form" action="{{ route('public.cesta.confirmar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_intent_id" id="payment_intent_id">

                            <div class="modal-body">
                                {{-- Datos usuario --}}
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>{{ __('site.checkout.name') }}</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ __('site.checkout.email') }}</label>
                                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                                    </div>
                                </div>

                                {{-- Datos de facturación --}}
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nif">{{ __('site.checkout.nif') }}</label>
                                        <input type="text" name="nif" id="nif" class="form-control @error('nif') is-invalid @enderror" value="{{ old('nif', auth()->user()->nif ?? '') }}">
                                        @error('nif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono">{{ __('site.checkout.phone') }}</label>
                                        <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', auth()->user()->telefono ?? '') }}">
                                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="direccion">{{ __('site.checkout.address') }}</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', auth()->user()->direccion ?? '') }}">
                                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="cp">{{ __('site.checkout.postal_code') }}</label>
                                        <input type="text" name="cp" id="cp" class="form-control @error('cp') is-invalid @enderror" value="{{ old('cp', auth()->user()->cp ?? '') }}">
                                        @error('cp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="ciudad">{{ __('site.checkout.city') }}</label>
                                        <input type="text" name="ciudad" id="ciudad" class="form-control @error('ciudad') is-invalid @enderror" value="{{ old('ciudad', auth()->user()->ciudad ?? '') }}">
                                        @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <hr class="my-3">
                                <h5 class="mb-2">{{ __('site.checkout.payment_method') }}</h5>
                                <div id="payment-element" class="bg-light" style="padding:12px;border-radius:8px;color:#000;"></div>
                                <div id="payment-error" class="text-danger mt-2" style="display:none;"></div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('site.common.cancel') }}</button>
                                <button type="submit" id="submit-payment" class="btn btn-primary">{{ __('site.checkout.confirm_pay') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Script autocompletar ciudad por CP --}}
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cpInput = document.getElementById('cp');
                const ciudadInput = document.getElementById('ciudad');
                if (!cpInput || !ciudadInput) return;
                let cpTimeout = null;

                function buscarPorCP(cp) {
                    cp = cp.trim();
                    if (!/^\d{5}$/.test(cp)) return;
                    fetch('https://api.zippopotam.us/es/' + cp)
                        .then(r => r.ok ? r.json() : Promise.reject('No encontrado'))
                        .then(data => {
                            if (!data.places || !data.places.length) return;
                            const place = data.places[0];
                            const texto = place['state'] ? (place['place name'] + ', ' + place['state']) : place['place name'];
                            ciudadInput.value = texto;
                        })
                        .catch(err => console.warn('No se encontró info para ese CP', err));
                }

                cpInput.addEventListener('input', () => {
                    if (cpTimeout) clearTimeout(cpTimeout);
                    cpTimeout = setTimeout(() => buscarPorCP(cpInput.value), 500);
                });

                cpInput.addEventListener('blur', () => buscarPorCP(cpInput.value));
            });
            </script>

            {{-- Stripe --}}
            <script src="https://js.stripe.com/v3/"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                @auth
                const stripeKey = "{{ config('services.stripe.key') }}";
                const clientSecret = "{{ $clientSecret ?? '' }}";
                if (!stripeKey || !clientSecret) return console.warn('Stripe no configurado correctamente.');

                const stripe = Stripe(stripeKey);
                const elements = stripe.elements({ clientSecret });
                const paymentElement = elements.create('payment');
                paymentElement.mount('#payment-element');

                const form = document.getElementById('payment-form');
                const submitBtn = document.getElementById('submit-payment');
                const errorDiv = document.getElementById('payment-error');

                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                    submitBtn.disabled = true;
                    submitBtn.textContent = '{{ __("site.checkout.processing") }}';

                    const { error, paymentIntent } = await stripe.confirmPayment({
                        elements,
                        confirmParams: { return_url: window.location.origin + '/cesta/result-temp' },
                        redirect: 'if_required'
                    });

                    if (error) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = error.message || '{{ __("site.checkout.payment_error") }}';
                        submitBtn.disabled = false;
                        submitBtn.textContent = '{{ __("site.checkout.confirm_pay") }}';
                        return;
                    }

                    if (paymentIntent?.status === 'succeeded') {
                        document.getElementById('payment_intent_id').value = paymentIntent.id;
                        form.submit();
                    } else {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = '{{ __("site.checkout.payment_failed") }}';
                        submitBtn.disabled = false;
                        submitBtn.textContent = '{{ __("site.checkout.confirm_pay") }}';
                    }
                });
                @endauth
            });
            </script>
        @endguest
    </div>
@endif
@endsection
