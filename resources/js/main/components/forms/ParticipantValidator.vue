<template>

    <div class="validator-app">

        <div class="validator-card">

            <!-- HEADER -->
            <div class="validator-header">

                <img
                    src="/img/imax-logo1.png"
                    alt="IMAX"
                >

                <h1>
                    Validar participante
                </h1>

                <p>
                    Ingresa el folio del participante
                </p>

            </div>


            <!-- ===================================================== -->
            <!-- BUSCAR PARTICIPANTE -->
            <!-- ===================================================== -->

            <div v-if="!participant">

                <div class="folio-input">

                    <svg viewBox="0 0 24 24">

                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        />

                        <path
                            d="M7 10h10M7 14h6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />

                    </svg>

                    <span>IMAX-</span>

                    <input
                        type="text"
                        v-model="folio"
                        placeholder="F5XSBEZ0"
                        autocomplete="off"
                        @keyup.enter="search"
                    >

                </div>


                <div
                    v-if="error"
                    class="error"
                >
                    {{ error }}
                </div>


                <button
                    type="button"
                    class="button"
                    :disabled="loading"
                    @click="search"
                >

                    <span v-if="!loading">
                        Buscar participante
                    </span>

                    <span
                        v-else
                        class="loading-content"
                    >

                        <svg
                            class="spinner"
                            viewBox="0 0 24 24"
                        >

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="3"
                            />

                        </svg>

                        Buscando...

                    </span>

                </button>

            </div>


            <!-- ===================================================== -->
            <!-- PARTICIPANTE ENCONTRADO -->
            <!-- ===================================================== -->

            <div v-else>

                <!-- INFORMACIÓN -->
                <div class="participant-info">

                    <div>

                        <small>
                            Folio
                        </small>

                        <strong>
                            {{ participant.folio }}
                        </strong>

                    </div>


                    <div>

                        <small>
                            Instagram
                        </small>

                        <strong>
                            @{{ participant.instagram }}
                        </strong>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- RESUMEN DE RETOS MULTIPLE -->
                <!-- ================================================= -->

                <div
                    v-if="multipleResponses.length"
                    class="section"
                >

                    <div class="section-title">

                        <div class="section-icon section-icon-blue">

                            <svg viewBox="0 0 24 24">

                                <path
                                    d="m5 12 4 4L19 6"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </div>

                        <div>

                            <h2>
                                Retos contestados
                            </h2>

                            <p>
                                Resumen de las respuestas del participante.
                            </p>

                        </div>

                    </div>


                    <div
                        v-for="(response, index) in multipleResponses"
                        :key="response.id"
                        class="result-question"
                    >

                        <div class="result-number">
                            {{ index + 1 }}
                        </div>


                        <div class="result-content">

                            <div class="result-question-text">
                                {{ response.question.question }}
                            </div>

                            <div class="result-answer">

                                Respuesta:

                                <strong>
                                    {{ response.answer }}
                                </strong>

                            </div>

                        </div>


                        <div
                            class="result-status"
                            :class="
                                response.is_correct
                                    ? 'result-correct'
                                    : 'result-incorrect'
                            "
                        >

                            <svg
                                v-if="response.is_correct"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    d="m5 12 4 4L19 6"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>


                            <svg
                                v-else
                                viewBox="0 0 24 24"
                            >

                                <path
                                    d="M6 6l12 12M18 6 6 18"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                />

                            </svg>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- ACTIVIDADES A VALIDAR -->
                <!-- ================================================= -->

                <div class="section">

                    <div class="section-title">

                        <div class="section-icon section-icon-green">

                            <svg viewBox="0 0 24 24">

                                <path
                                    d="M5 12h14"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M12 5v14"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />

                            </svg>

                        </div>

                        <div>

                            <h2>
                                Validar participación
                            </h2>

                            <p>
                                Confirma con el participante cada actividad.
                            </p>

                        </div>

                    </div>


                    <!-- NO HAY ACTIVIDADES PENDIENTES -->

                    <div
                        v-if="!hasPendingBooleanQuestions"
                        class="empty"
                    >

                        Todas las actividades ya fueron validadas.

                    </div>


                    <!-- ACTIVIDADES PENDIENTES -->

                    <div
                        v-for="(question, index) in booleanQuestions"
                        :key="question.id"
                        class="validation-question"
                    >

                        <div class="validation-number">
                            {{ index + 1 }}
                        </div>


                        <div class="validation-content">

                            <div class="validation-text">
                                {{ question.question }}
                            </div>


                            <div class="validation-options">

                                <!-- SÍ -->

                                <button
                                    type="button"
                                    class="validation-button"
                                    :class="{
                                        selectedYes:
                                            answers[question.id] === 'yes'
                                    }"
                                    @click="answer(question.id, 'yes')"
                                >

                                    <span class="validation-icon">

                                        <svg viewBox="0 0 24 24">

                                            <path
                                                d="m5 12 4 4L19 6"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />

                                        </svg>

                                    </span>

                                    Sí

                                </button>


                                <!-- NO -->

                                <button
                                    type="button"
                                    class="validation-button"
                                    :class="{
                                        selectedNo:
                                            answers[question.id] === 'no'
                                    }"
                                    @click="answer(question.id, 'no')"
                                >

                                    <span class="validation-icon">

                                        <svg viewBox="0 0 24 24">

                                            <path
                                                d="M6 6l12 12M18 6 6 18"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                            />

                                        </svg>

                                    </span>

                                    No

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ERROR -->

                <div
                    v-if="error"
                    class="error"
                >
                    {{ error }}
                </div>


                <!-- ================================================= -->
                <!-- VALIDAR -->
                <!-- ================================================= -->

                <button
                    v-if="hasPendingBooleanQuestions"
                    type="button"
                    class="button"
                    :disabled="loading || !allAnswered"
                    @click="validate"
                >

                    <span v-if="!loading">

                        Validar participación

                        <svg
                            class="button-icon"
                            viewBox="0 0 24 24"
                        >

                            <path
                                d="m5 12 4 4L19 6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>

                    </span>


                    <span
                        v-else
                        class="loading-content"
                    >

                        <svg
                            class="spinner"
                            viewBox="0 0 24 24"
                        >

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="3"
                            />

                        </svg>

                        Validando...

                    </span>

                </button>


                <!-- CAMBIAR FOLIO -->

                <button
                    type="button"
                    class="back-button"
                    @click="reset"
                >

                    Buscar otro folio

                </button>

            </div>

        </div>

    </div>

</template>


<script>

export default {

    props: {

        searchAction: {
            type: String,
            required: true
        },

        validateAction: {
            type: String,
            required: true
        }

    },


    data() {

        return {

            folio: '',

            participant: null,

            answers: {},

            loading: false,

            error: ''

        };

    },


    computed: {

        /*
        |--------------------------------------------------------------------------
        | RESPUESTAS MULTIPLE
        |--------------------------------------------------------------------------
        */

        multipleResponses() {

            if (!this.participant) {
                return [];
            }

            return this.participant.multiple_responses || [];

        },


        /*
        |--------------------------------------------------------------------------
        | PREGUNTAS BOOLEAN PENDIENTES
        |--------------------------------------------------------------------------
        |
        | Aquí está la parte importante.
        |
        | Buscamos todas las preguntas boolean del giveaway.
        |
        | Después buscamos si el participante ya tiene una respuesta
        | para esa pregunta.
        |
        | Si esa respuesta tiene verified = true,
        | ya NO mostramos la pregunta.
        |
        */

        booleanQuestions() {

            if (!this.participant) {
                return [];
            }

            const questions =
                this.participant.boolean_questions || [];

            const responses =
                this.participant.responses || [];

            return questions.filter(question => {

                const response = responses.find(
                    response =>
                        Number(response.question_id) ===
                        Number(question.id)
                );

                /*
                |--------------------------------------------------------------------------
                | YA VALIDADA
                |--------------------------------------------------------------------------
                */

                if (
                    response &&
                    (
                        response.verified === true ||
                        response.verified === 1 ||
                        response.verified === '1'
                    )
                ) {

                    return false;

                }

                return true;

            });

        },


        /*
        |--------------------------------------------------------------------------
        | EXISTEN ACTIVIDADES PENDIENTES
        |--------------------------------------------------------------------------
        */

        hasPendingBooleanQuestions() {

            return this.booleanQuestions.length > 0;

        },


        /*
        |--------------------------------------------------------------------------
        | TODAS LAS ACTIVIDADES CONTESTADAS
        |--------------------------------------------------------------------------
        */

        allAnswered() {

            if (!this.booleanQuestions.length) {
                return false;
            }

            return this.booleanQuestions.every(question => {

                return (
                    this.answers[question.id] === 'yes' ||
                    this.answers[question.id] === 'no'
                );

            });

        }

    },


    methods: {

        /*
        |--------------------------------------------------------------------------
        | BUSCAR PARTICIPANTE
        |--------------------------------------------------------------------------
        */

        search() {

            if (!this.folio.trim()) {

                this.error =
                    'Ingresa el folio del participante.';

                return;

            }

            this.loading = true;

            this.error = '';


            const cleanFolio = this.folio
                .trim()
                .replace(/^IMAX-/i, '')
                .toUpperCase();


            axios.get(
                this.searchAction + '/' + cleanFolio
            )

            .then(response => {

                this.participant =
                    response.data.participant;

                this.answers = {};

                this.error = '';

            })

            .catch(error => {

                this.participant = null;

                this.error =
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                        ? error.response.data.message
                        : 'No se encontró el participante.';

            })

            .finally(() => {

                this.loading = false;

            });

        },


        /*
        |--------------------------------------------------------------------------
        | RESPONDER BOOLEAN
        |--------------------------------------------------------------------------
        */

        answer(questionId, value) {

            this.$set(
                this.answers,
                questionId,
                value
            );

        },


        /*
        |--------------------------------------------------------------------------
        | VALIDAR PARTICIPACIÓN
        |--------------------------------------------------------------------------
        */

        validate() {

            if (!this.allAnswered) {

                this.error =
                    'Debes confirmar todas las actividades.';

                return;

            }

            this.loading = true;

            this.error = '';


            axios.post(
                this.validateAction,
                {

                    participant_id:
                        this.participant.id,

                    responses:
                        this.answers

                }
            )

            .then(response => {

                window.swal({
                    title: '¡Participación validada!',
                    text: response.data.message || 'La participación fue validada correctamente.',
                    icon: 'success',
                    button: 'Aceptar'
                });

                /*
                |------------------------------------------------------------------
                | IMPORTANTE
                |------------------------------------------------------------------
                |
                | Volvemos a buscar al participante.
                |
                | Así obtenemos del servidor las responses
                | con verified = true y las preguntas validadas
                | desaparecen automáticamente.
                |
                */

                this.searchParticipantAfterValidation();

            })

            .catch(error => {

                this.error =
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                        ? error.response.data.message
                        : 'No fue posible validar la participación.';

            })

            .finally(() => {

                this.loading = false;

            });

        },


        /*
        |--------------------------------------------------------------------------
        | VOLVER A CONSULTAR PARTICIPANTE
        |--------------------------------------------------------------------------
        */

        searchParticipantAfterValidation() {

            const cleanFolio =
                this.participant.folio
                    .replace(/^IMAX-/i, '')
                    .toUpperCase();


            axios.get(
                this.searchAction + '/' + cleanFolio
            )

            .then(response => {

                this.participant =
                    response.data.participant;

                this.answers = {};

                this.error = '';

            })

            .catch(error => {

                this.error =
                    error.response &&
                    error.response.data &&
                    error.response.data.message
                        ? error.response.data.message
                        : 'No fue posible actualizar el participante.';

            });

        },


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        reset() {

            this.folio = '';

            this.participant = null;

            this.answers = {};

            this.error = '';

        }

    }

};

</script>


<style scoped>

.validator-app {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 15px;
}

.validator-card {
    width: 100%;
    max-width: 580px;
    background: #fff;
    border-radius: 25px;
    padding: 35px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, .12);
}

.validator-header {
    text-align: center;
    margin-bottom: 30px;
}

.validator-header img {
    width: 110px;
    max-height: 60px;
    object-fit: contain;
    margin-bottom: 20px;
}

.validator-header h1 {
    margin: 0 0 8px;
    color: #20242a;
}

.validator-header p {
    margin: 0;
    color: #777;
}


/* =========================================================
   FOLIO
========================================================= */

.folio-input {
    display: flex;
    align-items: center;
    border: 2px solid #e5e8eb;
    border-radius: 15px;
    padding: 0 15px;
    margin-bottom: 15px;
    transition: border-color .2s;
}

.folio-input:focus-within {
    border-color: #409dcd;
}

.folio-input svg {
    width: 24px;
    height: 24px;
    color: #409dcd;
    flex-shrink: 0;
}

.folio-input span {
    margin-left: 10px;
    color: #555;
    font-weight: 700;
}

.folio-input input {
    width: 100%;
    border: 0;
    outline: 0;
    padding: 16px 10px;
    font-size: 17px;
    background: transparent;
}


/* =========================================================
   BUTTON
========================================================= */

.button {
    width: 100%;
    min-height: 52px;
    border: 0;
    border-radius: 14px;
    padding: 14px 16px;
    background: linear-gradient(
        135deg,
        #409dcd,
        #358dbb
    );
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: .2s;
}

.button:hover:not(:disabled) {
    transform: translateY(-2px);
}

.button:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.button-icon {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    margin-left: 6px;
}

.loading-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
}

.spinner {
    width: 20px;
    height: 20px;
    animation: spin .8s linear infinite;
}

@keyframes spin {

    to {
        transform: rotate(360deg);
    }

}


/* =========================================================
   PARTICIPANT
========================================================= */

.participant-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 30px;
}

.participant-info div {
    background: #f5f7f9;
    padding: 15px;
    border-radius: 12px;
}

.participant-info small {
    display: block;
    color: #888;
    margin-bottom: 5px;
}

.participant-info strong {
    display: block;
    word-break: break-word;
}


/* =========================================================
   SECTIONS
========================================================= */

.section {
    margin-bottom: 30px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 18px;
}

.section-title h2 {
    margin: 0 0 4px;
    font-size: 19px;
    color: #20242a;
}

.section-title p {
    margin: 0;
    color: #858c92;
    font-size: 13px;
    line-height: 1.4;
}

.section-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-icon svg {
    width: 22px;
    height: 22px;
}

.section-icon-blue {
    background: #eaf6fc;
    color: #409dcd;
}

.section-icon-green {
    background: #eaf8ef;
    color: #20a05a;
}


/* =========================================================
   RESULTADOS MULTIPLE
========================================================= */

.result-question {
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid #e5e8eb;
    border-radius: 14px;
    padding: 13px;
    margin-bottom: 10px;
}

.result-number {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 10px;
    background: #f1f3f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #666;
}

.result-content {
    flex: 1;
    min-width: 0;
}

.result-question-text {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
    color: #34393f;
}

.result-answer {
    margin-top: 5px;
    font-size: 12px;
    color: #888;
}

.result-status {
    width: 35px;
    height: 35px;
    min-width: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.result-status svg {
    width: 19px;
    height: 19px;
}

.result-correct {
    background: #eaf8ef;
    color: #20a05a;
}

.result-incorrect {
    background: #fff0f0;
    color: #d9534f;
}


/* =========================================================
   VALIDACIONES BOOLEAN
========================================================= */

.validation-question {
    display: flex;
    gap: 12px;
    border: 1px solid #e5e8eb;
    border-radius: 15px;
    padding: 16px;
    margin-bottom: 12px;
}

.validation-number {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 10px;
    background: #f1f3f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #666;
}

.validation-content {
    flex: 1;
    min-width: 0;
}

.validation-text {
    font-weight: 600;
    color: #34393f;
    line-height: 1.5;
    margin-bottom: 14px;
}

.validation-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.validation-button {
    border: 2px solid #e5e8eb;
    background: #fff;
    border-radius: 12px;
    padding: 11px;
    cursor: pointer;
    font-weight: 700;
    color: #555;
    transition: .2s;
}

.validation-button:hover {
    border-color: #b9ddec;
}

.validation-button.selectedYes {
    background: #eaf8ef;
    border-color: #20a05a;
    color: #20a05a;
}

.validation-button.selectedNo {
    background: #fff0f0;
    border-color: #d9534f;
    color: #d9534f;
}

.validation-icon svg {
    width: 18px;
    height: 18px;
    vertical-align: middle;
    margin-right: 5px;
}


/* =========================================================
   EMPTY
========================================================= */

.empty {
    text-align: center;
    padding: 25px;
    background: #f7f8f9;
    border-radius: 14px;
    color: #888;
}


/* =========================================================
   ERROR
========================================================= */

.error {
    background: #fff0f0;
    color: #d9534f;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-size: 14px;
}


/* =========================================================
   BACK
========================================================= */

.back-button {
    width: 100%;
    border: 0;
    background: transparent;
    padding: 15px;
    color: #777;
    cursor: pointer;
}

.back-button:hover {
    color: #409dcd;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 500px) {

    .validator-card {
        padding: 25px 20px;
        border-radius: 20px;
    }

    .participant-info {
        grid-template-columns: 1fr;
    }

    .validation-question {
        padding: 13px;
    }

}

</style>