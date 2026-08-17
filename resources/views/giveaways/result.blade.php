@extends('layout.master')

@section('title', 'Participación registrada')

@section('description', 'Folio de participación IMAX')

@section('canonical', url()->current())

@section('class', 'giveaway-result')

@section('styles')
<style>

.giveaway-result {
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px 15px;
    background: #f4f6fa;
}

.giveaway-result__card {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 30px;
    padding: 45px 30px;
    text-align: center;
    box-shadow: 0 25px 70px rgba(0, 0, 0, .12);
    animation: result-card-in .5s ease;
}

.giveaway-result__success {
    width: 85px;
    height: 85px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: #111;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 45px;
    font-weight: 700;
    animation: result-success .6s ease .2s both;
}

.giveaway-result__logo {
    width: 105px;
    margin-bottom: 25px;
    margin:0 auto;
}

.giveaway-result__label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: .5;
}

.giveaway-result h1 {
    font-size: 38px;
    font-weight: 900;
    margin: 8px 0 10px;
}

.giveaway-result__description {
    color: #777;
    line-height: 1.6;
}

.giveaway-result__folio {
    margin: 30px 0 20px;
    padding: 25px 15px;
    border-radius: 20px;
    background: #f4f4f4;
}

.giveaway-result__folio span {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    opacity: .5;
    margin-bottom: 8px;
}

.giveaway-result__folio strong {
    display: block;
    font-size: 29px;
    letter-spacing: 2px;
    font-weight: 900;
}

.giveaway-result__instruction {
    display: flex;
    align-items: center;
    text-align: left;
    gap: 14px;
    padding: 17px;
    border-radius: 17px;
    background: #f7f7f7;
    margin-bottom: 15px;
}

.giveaway-result__instruction-icon {
    font-size: 25px;
}

.giveaway-result__instruction p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
}

.giveaway-result__instagram {
    padding: 15px;
    border-radius: 15px;
    background: #fafafa;
    margin-bottom: 20px;
}

.giveaway-result__instagram span {
    display: block;
    font-size: 11px;
    opacity: .5;
}

.giveaway-result__instagram strong {
    font-size: 17px;
}

.giveaway-result__note {
    display: block;
    color: #999;
    line-height: 1.5;
}

@keyframes result-card-in {

    from {
        opacity: 0;
        transform: translateY(30px) scale(.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}

@keyframes result-success {

    from {
        transform: scale(0);
    }

    70% {
        transform: scale(1.12);
    }

    to {
        transform: scale(1);
    }

}

@media (max-width: 600px) {

    .giveaway-result {
        padding: 0;
    }

    .giveaway-result__card {
        min-height: 100vh;
        max-width: none;
        border-radius: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: none;
    }

}

</style>
@endsection

@section('content')

<section class="giveaway-result">

    <div class="giveaway-result__card">

        <div class="giveaway-result__success">
            ✓
        </div>

        <img
            src="{{ url('img/imax-logo.png') }}"
            alt="IMAX"
            class="giveaway-result__logo"
        >

        <span class="giveaway-result__label">
            ¡Participación registrada!
        </span>

        <h1>
            ¡Listo!
        </h1>

        <p class="giveaway-result__description">
            Tu participación ha sido registrada correctamente.
        </p>

        <div class="giveaway-result__folio">

            <span>
                TU FOLIO
            </span>

            <strong>
                {{ $participant->folio }}
            </strong>

        </div>

        <div class="giveaway-result__instruction">

            <div class="giveaway-result__instruction-icon">
                👋
            </div>

            <p>
                <strong>
                    Muéstrale este folio al equipo IMAX
                </strong>
                para validar tu información.
            </p>

        </div>

        <div class="giveaway-result__instagram">

            <span>
                Instagram
            </span>

            <strong>
                {{ $participant->instagram }}
            </strong>

        </div>

        <small class="giveaway-result__note">
            Conserva esta pantalla hasta que tu participación
            haya sido validada.
        </small>

    </div>

</section>

@endsection