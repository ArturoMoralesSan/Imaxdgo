<template>

    <form>

        <section class="db-panel mb-8">

            <h3 class="db-panel__title">
                Datos del giveaway
            </h3>

            <div class="md:row mb-4">

                <div class="md:col-1/2">

                    <div class="form-control">

                        <label for="name">
                            Nombre
                        </label>

                        <text-field
                            name="name"
                            v-model="fields.name"
                            maxlength="255"
                            :initial="((giveaway && giveaway.name != null) ? giveaway.name : '')"
                        >
                        </text-field>

                        <field-errors name="name"></field-errors>

                    </div>

                </div>

                <div class="md:col-1/2">

                    <div class="form-control">

                        <label for="active">
                            Estado
                        </label>

                        <select-field
                            name="active"
                            v-model="fields.active"
                            :options="activeOptions"
                            :initial="((giveaway && (giveaway.active === true || giveaway.active == 1)) ? '1' : '0')"
                        >
                        </select-field>

                        <field-errors name="active"></field-errors>

                    </div>

                </div>

            </div>

            <div class="md:row mb-4">

                <div class="md:col">

                    <div class="form-control">

                        <label for="description">
                            Descripción
                        </label>

                        <text-area
                            name="description"
                            v-model="fields.description"
                            rows="4"
                        >{{ giveaway && giveaway.description != null ? giveaway.description : '' }}</text-area>

                        <field-errors name="description"></field-errors>

                    </div>

                </div>

            </div>

            <div class="md:row mb-4">

                <div class="md:col-1/2">

                    <div class="form-control">

                        <label for="starts_at">
                            Fecha de inicio
                        </label>

                        <text-field
                            name="starts_at"
                            v-model="fields.starts_at"
                            type="datetime-local"
                            :initial="formatDate(giveaway && giveaway.starts_at != null ? giveaway.starts_at : '')"
                        >
                        </text-field>

                        <field-errors name="starts_at"></field-errors>

                    </div>

                </div>

                <div class="md:col-1/2">

                    <div class="form-control">

                        <label for="ends_at">
                            Fecha de finalización
                        </label>

                        <text-field
                            name="ends_at"
                            v-model="fields.ends_at"
                            type="datetime-local"
                            :initial="formatDate(giveaway && giveaway.ends_at != null ? giveaway.ends_at : '')"
                        >
                        </text-field>

                        <field-errors name="ends_at"></field-errors>

                    </div>

                </div>

            </div>

        </section>


        <section class="db-panel mb-8">

            <h3 class="db-panel__title">
                Preguntas
            </h3>

            <p class="description mb-6">
                Agrega las preguntas que tendrá el sorteo.
                Puedes utilizar preguntas de validación o de opción múltiple.
            </p>


            <div
                v-for="(question, index) in questions"
                :key="question.key"
                class="mb-8"
            >

                <div class="db-panel">

                    <div class="flex justify-between items-center mb-4">

                        <h4>
                            Pregunta {{ index + 1 }}
                        </h4>

                        <button
                            v-if="questions.length > 1"
                            type="button"
                            class="btn btn--danger"
                            @click="removeQuestion(index)"
                        >

                            <i class="fa-solid fa-trash"></i>

                            Eliminar

                        </button>

                    </div>


                    <div class="md:row mb-4">

                        <div class="md:col-1/3">

                            <div class="form-control">

                                <label>
                                    Pregunta
                                </label>

                                <text-field
                                    :name="'questions' + index + '_question'"
                                    v-model="fields['questions' + index + '_question']"
                                    maxlength="500"
                                    :initial="((typeof questions[index] !== 'undefined' && questions[index].question != null) ? questions[index].question : '')"
                                >
                                </text-field>

                                <field-errors
                                    :name="'questions' + index + '_question'"
                                >
                                </field-errors>

                            </div>

                        </div>


                        <div class="md:col-1/3">

                            <div class="form-control">

                                <label>
                                    Tipo
                                </label>

                                <select-field
                                    :name="'questions' + index + '_type'"
                                    v-model="fields['questions' + index + '_type']"
                                    :options="questionTypes"
                                    :initial="((typeof questions[index] !== 'undefined' && questions[index].type != null) ? questions[index].type : 'boolean')"
                                >
                                </select-field>

                                <field-errors
                                    :name="'questions' + index + '_type'"
                                >
                                </field-errors>

                            </div>

                        </div>
                        <div class="md:col-1/3">

                            <div class="form-control">

                                <label>
                                    Mostrar al usuario
                                </label>

                                <select-field
                                    :name="'questions' + index + '_show_user'"
                                    v-model="fields['questions' + index + '_show_user']"
                                    :options="showUserOptions"
                                    :initial="(
                                        typeof questions[index] !== 'undefined' &&
                                        questions[index].show_user != null
                                    )
                                        ? String(questions[index].show_user)
                                        : '1'
                                    "
                                >
                                </select-field>

                                <field-errors
                                    :name="'questions' + index + '_show_user'"
                                >
                                </field-errors>

                            </div>

                        </div>

                    </div>


                    <div
                        v-if="fields['questions' + index + '_type'] === 'boolean'"
                        class="mb-4"
                    >

                        <div class="description">

                            <i class="fa-solid fa-circle-info"></i>

                            Esta pregunta será respondida como una
                            confirmación y posteriormente será validada
                            por el personal del stand.

                        </div>

                    </div>


                    <div
                        v-if="fields['questions' + index + '_type'] === 'multiple'"
                        class="mb-4"
                    >

                        <h5 class="mb-4">
                            Opciones de respuesta
                        </h5>


                        <div class="md:row mb-4">

                            <div class="md:col-1/2">

                                <div class="form-control">

                                    <label>
                                        Opción A
                                    </label>

                                    <text-field
                                        :name="'questions' + index + '_option_a'"
                                        v-model="fields['questions' + index + '_option_a']"
                                        maxlength="500"
                                        :initial="((typeof questions[index] !== 'undefined' && questions[index].option_a != null) ? questions[index].option_a : '')"
                                    >
                                    </text-field>

                                </div>

                            </div>


                            <div class="md:col-1/2">

                                <div class="form-control">

                                    <label>
                                        Opción B
                                    </label>

                                    <text-field
                                        :name="'questions' + index + '_option_b'"
                                        v-model="fields['questions' + index + '_option_b']"
                                        maxlength="500"
                                        :initial="((typeof questions[index] !== 'undefined' && questions[index].option_b != null) ? questions[index].option_b : '')"
                                    >
                                    </text-field>

                                </div>

                            </div>

                        </div>


                        <div class="md:row mb-4">

                            <div class="md:col-1/2">

                                <div class="form-control">

                                    <label>
                                        Opción C
                                    </label>

                                    <text-field
                                        :name="'questions' + index + '_option_c'"
                                        v-model="fields['questions' + index + '_option_c']"
                                        maxlength="500"
                                        :initial="((typeof questions[index] !== 'undefined' && questions[index].option_c != null) ? questions[index].option_c : '')"
                                    >
                                    </text-field>

                                </div>

                            </div>


                            <div class="md:col-1/2">

                                <div class="form-control">

                                    <label>
                                        Opción D
                                    </label>

                                    <text-field
                                        :name="'questions' + index + '_option_d'"
                                        v-model="fields['questions' + index + '_option_d']"
                                        maxlength="500"
                                        :initial="((typeof questions[index] !== 'undefined' && questions[index].option_d != null) ? questions[index].option_d : '')"
                                    >
                                    </text-field>

                                </div>

                            </div>

                        </div>


                        <div class="form-control">

                            <label>
                                Respuesta correcta
                            </label>

                            <select-field
                                :name="'questions' + index + '_correct_option'"
                                v-model="fields['questions' + index + '_correct_option']"
                                :options="correctOptions"
                                :initial="((typeof questions[index] !== 'undefined' && questions[index].correct_option != null) ? questions[index].correct_option : '')"
                            >
                            </select-field>

                            <field-errors
                                :name="'questions' + index + '_correct_option'"
                            >
                            </field-errors>

                        </div>

                    </div>

                </div>

            </div>


            <div class="text-center">

                <button
                    type="button"
                    class="btn btn--secondary"
                    @click="addQuestion"
                >

                    <i class="fa-solid fa-plus"></i>

                    Agregar pregunta

                </button>

            </div>

        </section>


        <div class="text-center">

            <form-button
                :class="giveaway
                    ? 'btn btn--blue--dashboard btn--wide'
                    : 'btn--success btn--wide'"
            >

                <i
                    v-if="giveaway"
                    class="fa-solid fa-save"
                ></i>

                <i
                    v-else
                    class="fa-solid fa-plus"
                ></i>

                {{ giveaway ? 'Actualizar' : 'Crear' }}

            </form-button>

        </div>

    </form>

</template>


<script>

import BaseForm from '../../main/components/forms/base/BaseForm.vue';

export default {

    extends: BaseForm,

    props: {

        giveaway: {

            type: Object,

            default: function () {
                return null;
            }

        }

    },

    data() {

        return {

            questions: [],

            activeOptions: {
                '1': 'Activo',
                '0': 'Inactivo'
            },

            questionTypes: {
                'boolean': 'Validación',
                'multiple': 'Opción múltiple'
            },

            correctOptions: {
                '': 'Selecciona una opción',
                'A': 'Opción A',
                'B': 'Opción B',
                'C': 'Opción C',
                'D': 'Opción D'
            },
            showUserOptions: {
                '1': 'Sí',
                '0': 'No'
            },

        };

    },

    mounted() {

        this.$set(
            this.fields,
            'questions_count',
            0
        );

        if (
            this.giveaway &&
            this.giveaway.questions &&
            this.giveaway.questions.length
        ) {

            this.giveaway.questions.forEach(
                (question, index) => {

                    this.addExistingQuestion(
                        question,
                        index
                    );

                }
            );

        } else {

            this.addQuestion();

        }

    },

    methods: {

        formatDate(value) {

            if (!value) {
                return '';
            }

            return value
                .replace('T', ' ')
                .replace('Z', '')
                .substring(0, 16)
                .replace(' ', 'T');

        },


        createQuestion() {

            return {

                key: Date.now() + Math.random(),

                question: '',

                type: 'boolean',

                show_user: 1,

                option_a: '',

                option_b: '',

                option_c: '',

                option_d: '',

                correct_option: ''

            };

        },


        addExistingQuestion(question, index) {

            const questionData = {

                key: question.id
                    ? question.id
                    : Date.now() + Math.random(),

                question: question.question != null
                    ? question.question
                    : '',

                type: question.type != null
                    ? question.type
                    : 'boolean',

                show_user: question.show_user != null
                        ? question.show_user
                        : 1,

                option_a: question.option_a != null
                    ? question.option_a
                    : '',

                option_b: question.option_b != null
                    ? question.option_b
                    : '',

                option_c: question.option_c != null
                    ? question.option_c
                    : '',

                option_d: question.option_d != null
                    ? question.option_d
                    : '',

                correct_option: question.correct_option != null
                    ? question.correct_option
                    : ''

            };


            this.questions.push(questionData);


            this.$set(
                this.fields,
                'questions' + index + '_question',
                questionData.question
            );

            this.$set(
                this.fields,
                'questions' + index + '_type',
                questionData.type
            );

            this.$set(
                this.fields,
                'questions' + index + '_show_user',
                questionData.show_user
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_a',
                questionData.option_a
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_b',
                questionData.option_b
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_c',
                questionData.option_c
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_d',
                questionData.option_d
            );

            this.$set(
                this.fields,
                'questions' + index + '_correct_option',
                questionData.correct_option
            );


            this.$set(
                this.fields,
                'questions_count',
                this.fields.questions_count + 1
            );

        },


        addQuestion() {

            const index = this.fields.questions_count;

            const question = this.createQuestion();

            this.questions.push(question);


            this.$set(
                this.fields,
                'questions' + index + '_question',
                ''
            );

            this.$set(
                this.fields,
                'questions' + index + '_type',
                'boolean'
            );

            this.$set(
                this.fields,
                'questions' + index + '_show_user',
                1
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_a',
                ''
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_b',
                ''
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_c',
                ''
            );

            this.$set(
                this.fields,
                'questions' + index + '_option_d',
                ''
            );

            this.$set(
                this.fields,
                'questions' + index + '_correct_option',
                ''
            );


            this.$set(
                this.fields,
                'questions_count',
                this.fields.questions_count + 1
            );

        },


        removeQuestion(index) {

            if (this.questions.length <= 1) {
                return;
            }


            this.questions.splice(index, 1);


            const fields = {};

            for (let i = 0; i < this.questions.length; i++) {

                const oldIndex = i >= index
                    ? i + 1
                    : i;

                fields[i] = {

                    question: this.fields[
                        'questions' + oldIndex + '_question'
                    ] || '',

                    type: this.fields[
                        'questions' + oldIndex + '_type'
                    ] || 'boolean',

                    show_user: this.fields[
                        'questions' + oldIndex + '_show_user'
                    ] ?? 1,

                    option_a: this.fields[
                        'questions' + oldIndex + '_option_a'
                    ] || '',

                    option_b: this.fields[
                        'questions' + oldIndex + '_option_b'
                    ] || '',

                    option_c: this.fields[
                        'questions' + oldIndex + '_option_c'
                    ] || '',

                    option_d: this.fields[
                        'questions' + oldIndex + '_option_d'
                    ] || '',

                    correct_option: this.fields[
                        'questions' + oldIndex + '_correct_option'
                    ] || ''

                };

            }


            for (let i = 0; i < this.questions.length; i++) {

                this.$set(
                    this.fields,
                    'questions' + i + '_question',
                    fields[i].question
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_type',
                    fields[i].type
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_show_user',
                    fields[i].show_user
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_option_a',
                    fields[i].option_a
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_option_b',
                    fields[i].option_b
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_option_c',
                    fields[i].option_c
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_option_d',
                    fields[i].option_d
                );

                this.$set(
                    this.fields,
                    'questions' + i + '_correct_option',
                    fields[i].correct_option
                );

            }


            this.$set(
                this.fields,
                'questions_count',
                this.questions.length
            );

        }

    }

};

</script>