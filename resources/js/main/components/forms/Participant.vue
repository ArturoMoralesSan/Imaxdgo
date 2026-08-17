```vue
<template>
    <div class="participant-app">

        <div class="participant-card">

            <div class="participant-header">

                <img
                    src="/img/imax-logo.png"
                    alt="IMAX"
                    class="participant-logo"
                >

                <div class="participant-progress">

                    <div
                        class="participant-progress-bar"
                        :style="{ width: progress + '%' }"
                    ></div>

                </div>

            </div>


            <transition name="slide" mode="out-in">

                <div
                    v-if="!finished"
                    :key="step + '-' + questionIndex"
                >

                    <!-- PASO 0 -->
                    <div
                        v-if="step === 0"
                        class="participant-step"
                    >

                        <div class="participant-icon participant-icon-blue">

                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <path
                                    d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M3 7h18v5H3z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M12 7v14"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />

                                <path
                                    d="M12 7H8.5A2.5 2.5 0 1 1 11 4.5C11 3 12 3 12 3s1 0 1 1.5A2.5 2.5 0 1 1 15.5 7H12Z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </div>


                        <h1>
                            {{ giveaway.name }}
                        </h1>


                        <div
                            class="participant-description"
                            v-html="formattedDescription"
                        ></div>


                        <button
                            type="button"
                            class="participant-button"
                            @click="nextStep"
                        >

                            <span>
                                Comenzar
                            </span>

                            <svg
                                class="button-icon"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    d="M5 12h14M13 6l6 6-6 6"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </button>

                    </div>


                    <!-- PASO 1 -->
                    <div
                        v-else-if="step === 1"
                        class="participant-step"
                    >

                        <div class="participant-icon participant-icon-instagram">

                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <rect
                                    x="3"
                                    y="3"
                                    width="18"
                                    height="18"
                                    rx="5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="4"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />

                                <circle
                                    cx="17.5"
                                    cy="6.5"
                                    r="1"
                                    fill="currentColor"
                                />

                            </svg>

                        </div>


                        <h2>
                            Tu usuario de Instagram
                        </h2>


                        <p class="participant-text">
                            Escribe tu usuario de Instagram para continuar.
                        </p>


                        <div class="instagram-input">

                            <span>@</span>

                            <input
                                type="text"
                                v-model="instagram"
                                placeholder="usuario"
                                maxlength="255"
                                autocomplete="off"
                                @keyup.enter="nextStep"
                            >

                        </div>


                        <div
                            v-if="error"
                            class="participant-error"
                        >
                            {{ error }}
                        </div>


                        <button
                            type="button"
                            class="participant-button"
                            :disabled="loading"
                            @click="nextStep"
                        >

                            <span v-if="!loading">

                                Continuar

                                <svg
                                    class="button-icon"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        d="M5 12h14M13 6l6 6-6 6"
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
                                        stroke-linecap="round"
                                    />

                                </svg>

                                Verificando...

                            </span>

                        </button>

                    </div>


                    <!-- PREGUNTAS VISIBLES -->
                    <div
                        v-else-if="currentQuestion"
                        class="participant-step"
                    >

                        <div class="question-counter">

                            Reto

                            <strong>
                                {{ questionIndex + 1 }}
                            </strong>

                            de

                            <strong>
                                {{ questions.length }}
                            </strong>

                        </div>


                        <div class="participant-icon participant-icon-blue">

                            <!-- ICONO MULTIPLE -->
                            <svg
                                v-if="currentQuestion.type === 'multiple'"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    d="M4 5h16M4 12h16M4 19h10"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />

                                <circle
                                    cx="4"
                                    cy="5"
                                    r="1"
                                    fill="currentColor"
                                />

                                <circle
                                    cx="4"
                                    cy="12"
                                    r="1"
                                    fill="currentColor"
                                />

                                <circle
                                    cx="4"
                                    cy="19"
                                    r="1"
                                    fill="currentColor"
                                />

                            </svg>


                            <!-- ICONO BOOLEAN -->
                            <svg
                                v-else
                                viewBox="0 0 24 24"
                            >

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />

                                <path
                                    d="m8 12 3 3 5-6"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </div>


                        <h2>
                            {{ currentQuestion.question }}
                        </h2>


                        <!-- OPCIONES MULTIPLE -->
                        <div
                            v-if="currentQuestion.type === 'multiple'"
                            class="answers"
                        >

                            <button
                                v-for="option in multipleOptions"
                                :key="option.value"
                                type="button"
                                class="answer-button"
                                :class="{
                                    'answer-selected':
                                        currentAnswer === option.value
                                }"
                                @click="answerMultiple(option.value)"
                            >

                                <span class="answer-letter">
                                    {{ option.value }}
                                </span>

                                <span class="answer-text">
                                    {{ option.text }}
                                </span>

                                <span
                                    v-if="currentAnswer === option.value"
                                    class="answer-check"
                                >

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

                            </button>

                        </div>


                        <!-- OPCIONES BOOLEAN -->
                        <div
                            v-else
                            class="boolean-options"
                        >

                            <button
                                type="button"
                                class="answer-button answer-boolean"
                                :class="{
                                    'answer-selected':
                                        currentAnswer === 'yes'
                                }"
                                @click="answerBoolean('yes')"
                            >

                                <span class="answer-icon answer-icon-success">

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

                                <span class="answer-text">
                                    Sí
                                </span>

                            </button>


                            <button
                                type="button"
                                class="answer-button answer-boolean"
                                :class="{
                                    'answer-selected':
                                        currentAnswer === 'no'
                                }"
                                @click="answerBoolean('no')"
                            >

                                <span class="answer-icon answer-icon-danger">

                                    <svg viewBox="0 0 24 24">

                                        <path
                                            d="M6 6l12 12M18 6 6 18"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2.5"
                                            stroke-linecap="round"
                                        />

                                    </svg>

                                </span>

                                <span class="answer-text">
                                    No
                                </span>

                            </button>

                        </div>


                        <!-- CONTINUAR -->
                        <div
                            v-if="currentAnswer"
                            class="question-next"
                        >

                            <button
                                type="button"
                                class="participant-button"
                                :disabled="loading"
                                @click="nextQuestion"
                            >

                                <span v-if="!loading">

                                    {{
                                        questionIndex < questions.length - 1
                                            ? 'Continuar'
                                            : 'Finalizar participación'
                                    }}

                                    <svg
                                        class="button-icon"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            d="M5 12h14M13 6l6 6-6 6"
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
                                            stroke-linecap="round"
                                        />

                                    </svg>

                                    Guardando...

                                </span>

                            </button>

                        </div>

                    </div>


                    <!-- SIN PREGUNTAS VISIBLES -->
                    <div
                        v-else
                        class="participant-step"
                    >

                        <div class="participant-icon participant-icon-blue">

                            <svg viewBox="0 0 24 24">

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                />

                                <path
                                    d="M12 8v5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />

                                <circle
                                    cx="12"
                                    cy="16.5"
                                    r="1"
                                    fill="currentColor"
                                />

                            </svg>

                        </div>

                        <h2>
                            No hay preguntas disponibles
                        </h2>

                        <p class="participant-text">
                            No hay preguntas de validación disponibles para este giveaway.
                        </p>

                        <button
                            type="button"
                            class="participant-button"
                            :disabled="loading"
                            @click="finish"
                        >
                            Continuar
                        </button>

                    </div>

                </div>


                <!-- FINALIZADO -->
                <div
                    v-else
                    key="finished"
                    class="participant-step participant-finished"
                >

                    <div class="success-animation">

                        <div class="success-circle">

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

                    </div>


                    <h1>
                        ¡Listo!
                    </h1>


                    <p class="participant-text">
                        Tu participación ha sido registrada correctamente.
                    </p>


                    <p class="folio-label">
                        Tu folio
                    </p>


                    <div class="folio">
                        {{ folio }}
                    </div>


                    <div class="folio-card">

                        <svg
                            viewBox="0 0 24 24"
                            class="folio-card-icon"
                        >

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


                        <p>
                            Muestra este folio al equipo del stand
                            para validar tu información.
                        </p>

                    </div>

                </div>

            </transition>


            <div
                v-if="!finished"
                class="participant-footer"
            >

                <span>
                    {{ progress }}%
                </span>

                <span>
                    IMAX Durango
                </span>

            </div>

        </div>

    </div>
</template>


<script>

export default {

    props: {

        action: {
            type: String,
            required: true
        },

        giveaway: {
            type: Object,
            required: true
        }

    },


    data() {

        return {

            step: 0,

            instagram: '',

            questions: [],

            questionIndex: 0,

            currentAnswer: '',

            answers: {},

            folio: '',

            finished: false,

            loading: false,

            error: '',

            /*
             * URL A LA QUE SE ENVIARÁ AL USUARIO
             * CUANDO RESPONDA "NO"
             */
            instagramUrl: 'https://www.instagram.com/imaxdgo/'

        };

    },


    computed: {

        currentQuestion() {

            if (
                !this.questions.length ||
                this.questionIndex >= this.questions.length
            ) {
                return null;
            }

            return this.questions[this.questionIndex];

        },


        multipleOptions() {

            if (!this.currentQuestion) {
                return [];
            }

            return [
                {
                    value: 'A',
                    text: this.currentQuestion.option_a
                },
                {
                    value: 'B',
                    text: this.currentQuestion.option_b
                },
                {
                    value: 'C',
                    text: this.currentQuestion.option_c
                },
                {
                    value: 'D',
                    text: this.currentQuestion.option_d
                }
            ].filter(function(option) {

                return option.text;

            });

        },


        totalSteps() {

            return this.questions.length;

        },


        progress() {

            if (this.finished) {
                return 100;
            }

            /*
             * Antes de comenzar las preguntas:
             * 0%
             */
            if (this.step < 2) {
                return 0;
            }

            /*
             * No hay preguntas:
             * 0%
             */
            if (!this.questions.length) {
                return 0;
            }

            /*
             * Calculamos únicamente el avance
             * de las preguntas visibles.
             */
            return Math.round(
                ((this.questionIndex + 1) / this.questions.length) * 100
            );

        },


        formattedDescription() {

            if (
                !this.giveaway ||
                !this.giveaway.description
            ) {
                return '';
            }

            var text = this.giveaway.description;

            text = text.replace(/\r\n/g, '\n');
            text = text.replace(/\r/g, '\n');

            var lines = text.split('\n');

            var html = '';
            var inList = false;

            lines.forEach(function(line) {

                line = line.trim();

                if (!line) {
                    return;
                }

                if (line.indexOf('## ') === 0) {

                    if (inList) {
                        html += '</ul>';
                        inList = false;
                    }

                    html +=
                        '<h3>' +
                        line.substring(3) +
                        '</h3>';

                    return;

                }

                if (
                    line.indexOf('* ') === 0 ||
                    line.indexOf('\\* ') === 0
                ) {

                    if (!inList) {
                        html += '<ul>';
                        inList = true;
                    }

                    var item = line
                        .replace(/^\\\*\s*/, '')
                        .replace(/^\*\s*/, '');

                    html += '<li>' + item + '</li>';

                    return;

                }

                if (inList) {
                    html += '</ul>';
                    inList = false;
                }

                html += '<p>' + line + '</p>';

            });

            if (inList) {
                html += '</ul>';
            }

            return html;

        }

    },


    mounted() {

        /*
         * Solamente cargamos las preguntas
         * donde show_user está activado.
         */
        this.questions =
            this.giveaway &&
            this.giveaway.questions
                ? this.giveaway.questions.filter(function(question) {

                    return (
                        question.show_user === true ||
                        question.show_user === 1 ||
                        question.show_user === '1'
                    );

                })
                : [];

    },


    methods: {

        nextStep() {

            this.error = '';

            if (this.step === 0) {

                this.step = 1;

                return;

            }


            if (this.step === 1) {

                if (!this.instagram.trim()) {

                    this.error =
                        'Escribe tu usuario de Instagram.';

                    return;

                }

                /*
                 * Pasamos a preguntas.
                 */
                this.step = 2;

            }

        },


        answerMultiple(value) {

            this.currentAnswer = value;

        },


        answerBoolean(value) {

            /*
             * Si responde NO:
             * no guardamos la respuesta
             * y lo enviamos a Instagram.
             */
            if (value === 'no') {

                this.currentAnswer = '';

                window.location.href = this.instagramUrl;

                return;

            }


            /*
             * Si responde SÍ:
             * continúa normalmente.
             */
            this.currentAnswer = value;

        },


        nextQuestion() {

            if (
                !this.currentAnswer ||
                this.loading
            ) {
                return;
            }


            this.$set(
                this.answers,
                this.currentQuestion.id,
                this.currentAnswer
            );


            if (
                this.questionIndex <
                this.questions.length - 1
            ) {

                this.questionIndex++;

                this.currentAnswer = '';

                return;

            }


            this.finish();

        },


        finish() {

            if (this.loading) {
                return;
            }

            this.loading = true;

            this.error = '';


            var payload = {

                instagram: this.instagram.trim(),

                answers: this.answers

            };


            axios.post(
                this.action,
                payload
            )
            .then(response => {

                this.folio =
                    response.data.folio;

                this.finished = true;

            })
            .catch(error => {

                if (
                    error.response &&
                    error.response.status === 422 &&
                    error.response.data.errors
                ) {

                    var errors =
                        error.response.data.errors;

                    var firstError =
                        Object.keys(errors)[0];

                    this.error =
                        errors[firstError][0];

                } else {

                    this.error =
                        'No fue posible registrar tu participación. Intenta nuevamente.';

                }

            })
            .finally(() => {

                this.loading = false;

            });

        }

    }

};

</script>


<style scoped>

.participant-app {
    min-height: 90vh;
    padding: 25px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}


.participant-card {
    width: 100%;
    max-width: 520px;
    background: #fff;
    border-radius: 28px;
    overflow: hidden;
    box-shadow:
        0 20px 60px rgba(0, 0, 0, .12);
}


.participant-header {
    padding: 25px 25px 12px;
}


.participant-logo {
    display: block;
    max-height: 90px;
    object-fit: contain;
    margin: 0 auto 22px;
}


.participant-progress {
    width: 100%;
    height: 6px;
    background: #edf0f3;
    border-radius: 20px;
    overflow: hidden;
}


.participant-progress-bar {
    height: 100%;
    background: linear-gradient(
        90deg,
        #409dcd,
        #55b8e8
    );
    border-radius: 20px;
    transition: width .45s ease;
}


.participant-step {
    padding: 35px 30px 40px;
    text-align: center;
}


.participant-icon {
    width: 82px;
    height: 82px;
    margin: 0 auto 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}


.participant-icon svg {
    width: 40px;
    height: 40px;
}


.participant-icon-blue {
    background: #eaf6fc;
    color: #409dcd;
}


.participant-icon-instagram {
    background: #f7edf8;
    color: #c13584;
}


.participant-step h1,
.participant-step h2 {
    margin: 0 0 15px;
    font-weight: 700;
    color: #20242a;
}


.participant-step h1 {
    font-size: 28px;
}


.participant-step h2 {
    font-size: 23px;
}


.participant-description {
    text-align: left;
    color: #626970;
    line-height: 1.65;
    margin: 25px 0 30px;
}


.participant-description p {
    margin: 0 0 12px;
}


.participant-description h3 {
    margin: 25px 0 12px;
    font-size: 19px;
    color: #20242a;
}


.participant-description ul {
    margin: 12px 0 20px;
    padding-left: 23px;
}


.participant-description li {
    margin-bottom: 10px;
}


.participant-text {
    color: #747b82;
    line-height: 1.6;
}


.participant-button {
    width: 100%;
    min-height: 54px;
    border: 0;
    border-radius: 15px;
    padding: 14px 20px;
    background: linear-gradient(
        135deg,
        #409dcd,
        #358dbb
    );
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition:
        transform .2s ease,
        box-shadow .2s ease,
        opacity .2s ease;
    box-shadow:
        0 8px 20px rgba(64, 157, 205, .25);
}


.participant-button:hover {
    transform: translateY(-2px);
    box-shadow:
        0 12px 25px rgba(64, 157, 205, .32);
}


.participant-button:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
}


.button-icon {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    margin-left: 7px;
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


.instagram-input {
    display: flex;
    align-items: center;
    margin: 25px 0 15px;
    border: 2px solid #e7eaed;
    border-radius: 15px;
    overflow: hidden;
    transition: border-color .2s;
}


.instagram-input:focus-within {
    border-color: #409dcd;
}


.instagram-input span {
    padding-left: 17px;
    font-size: 20px;
    font-weight: 600;
    color: #8a9198;
}


.instagram-input input {
    width: 100%;
    border: 0;
    outline: none;
    padding: 16px 12px;
    font-size: 17px;
    background: transparent;
}


.participant-error {
    color: #d9534f;
    margin-bottom: 15px;
    font-size: 14px;
}


.question-counter {
    color: #8a9198;
    font-size: 13px;
    margin-bottom: 20px;
}


.answers,
.boolean-options {
    margin-top: 25px;
}


.answer-button {
    width: 100%;
    min-height: 62px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-align: left;
    border: 2px solid #edf0f2;
    background: #fff;
    border-radius: 15px;
    padding: 12px 15px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 15px;
    transition:
        border-color .2s,
        background .2s,
        transform .2s;
}


.answer-button:hover {
    transform: translateY(-1px);
    border-color: #b9ddec;
}


.answer-selected {
    border-color: #409dcd;
    background: #eef8fd;
}


.answer-letter,
.answer-icon {
    min-width: 40px;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f3f5;
    font-weight: 700;
}


.answer-letter {
    border-radius: 50%;
    color: #555;
}


.answer-icon svg {
    width: 22px;
    height: 22px;
}


.answer-icon-success {
    background: #eaf8ef;
    color: #20a05a;
}


.answer-icon-danger {
    background: #fff0f0;
    color: #d9534f;
}


.answer-text {
    flex: 1;
    color: #34393f;
    font-weight: 600;
}


.answer-check {
    color: #409dcd;
}


.answer-check svg {
    width: 24px;
    height: 24px;
}


.question-next {
    margin-top: 20px;
}


.success-animation {
    margin-bottom: 25px;
}


.success-circle {
    width: 95px;
    height: 95px;
    margin: auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9f8ef;
    color: #20a05a;
    animation: successPop .5s ease;
}


.success-circle svg {
    width: 48px;
    height: 48px;
    animation: checkDraw .5s ease .15s both;
}


@keyframes successPop {

    0% {
        transform: scale(.5);
        opacity: 0;
    }

    70% {
        transform: scale(1.08);
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }

}


@keyframes checkDraw {

    from {
        opacity: 0;
        transform: scale(.5);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }

}


.folio-label {
    margin-top: 30px;
    margin-bottom: 8px;
    color: #777;
}


.folio {
    padding: 18px;
    border-radius: 16px;
    background: #f4f7f9;
    color: #20242a;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: 5px;
}


.folio-card {
    margin-top: 25px;
    padding: 18px;
    border-radius: 17px;
    background: #eef8fd;
    color: #409dcd;
}


.folio-card-icon {
    width: 32px;
    height: 32px;
    margin-bottom: 5px;
}


.folio-card p {
    margin: 5px 0 0;
    color: #64717a;
    line-height: 1.5;
    font-size: 14px;
}


.participant-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px 25px;
    border-top: 1px solid #eee;
    color: #999;
    font-size: 12px;
}


.slide-enter-active,
.slide-leave-active {
    transition:
        opacity .25s ease,
        transform .25s ease;
}


.slide-enter {
    opacity: 0;
    transform: translateX(25px);
}


.slide-leave-to {
    opacity: 0;
    transform: translateX(-25px);
}


@media (max-width: 480px) {

    .participant-app {
        padding: 0;
        align-items: stretch;
    }


    .participant-card {
        min-height: 100vh;
        border-radius: 0;
    }


    .participant-step {
        padding: 30px 20px 35px;
    }


    .participant-step h1 {
        font-size: 25px;
    }


    .participant-step h2 {
        font-size: 21px;
    }

}

</style>
