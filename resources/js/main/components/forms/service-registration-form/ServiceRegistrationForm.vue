<template>
    <form>
        <div class="row mb-4">
            <div class="col-1/3">
                <div class="form-control">
                    <label for="branch">Sucursal</label>
                    <select-field 
                        name="branch_id" 
                        v-model="fields.branch_id" 
                        :options="branches"
                        @input="GetLastRX"
                        :initial="(ServiceData.branch_id || '')"
                    >
                    </select-field>
                    <field-errors name="branch_id"></field-errors>
                </div>
            </div>
            <div class="col-1/3">
                <div class="form-control">
                    <label for="date">Fecha</label>
                    <date-field name="date"
                        disabled
                        v-model="fields.date"
                        :initial="ServiceData.date || currentDate"
                        :aria-describedby="supportsDates ? '' : 'start-date-description'"
                    ></date-field>
                    <field-errors name="date"></field-errors>

                    <p v-if="! supportsDates" id="start-date-description" class="description">
                        Formato: dd/mm/aaaa
                    </p>
                </div>
            </div>
            <div class="col-1/3">
                <div class="form-control">
                    <label for="rx_prev">RX Anterior</label>
                    <text-field :disabled="inputdisabled" class="field-get-researcher"  name="rx_prev" v-model="fields.rx_prev" maxlength="160" ></text-field>
                    <field-errors name="rx_prev"></field-errors>
                    <ul v-if="!inputdisabled" id="rx_prev-specs" class="description color-gray-darken-1">
                        <li>
                            Agrega un valor al campo.
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        
        
        <div class="form-control">
            <label for="patient">Paciente</label>
            <text-field class="field-get-researcher"  name="patient" v-model="fields.patient" maxlength="160" :initial="ServiceData.patient  || '' "></text-field>
            <field-errors name="patient"></field-errors>
        </div>
        <div class="mb-4">
            <StudyForm v-for="i in fields.studies_count" :key="i"
                :index="i"
                :min-study="minStudy"
                :studies-data="studiesData"
                :assigned-studies="assignedStudies"
                :errors="errors"
                :fields="fields"
                @remove="removeStudies"            
            >
            </StudyForm>

            <p class="pt-4">
                <button v-if="fields.studies_count < study"
                    class="btn btn--light mr-4"
                    type="button"
                    @click="fields.studies_count++"
                >
                    <img class="mr-1 align-top"
                        :src="$root.path + '/img/svg/plus-circle-primary.svg'"
                        alt=""
                        width="20px"
                    >
                    <span class="align-top">Agregar estudios</span>
                </button>

                <span v-if="study > 1 "> Puedes registrar a un máximo de {{ study }} estudios.</span>
                <span v-else> Puedes registrar únicamente un estudio.</span>
            </p>
        </div>
        <div class="mb-4">
            <PaymentForm v-for="i in fields.payments_count" :key="i"
                :index="i"
                :min-payment="minPayment"
                :payments-data="paymentsData"
                :assigned-payments="assignedPayments"
                :errors="errors"
                :fields="fields"
                @removeP="removePayments"
            >
            </PaymentForm>

            <p class="pt-4">
                <button v-if="fields.payments_count < payment"
                    class="btn btn--light mr-4"
                    type="button"
                    @click="fields.payments_count++"
                >
                    <img class="mr-1 align-top"
                        :src="$root.path + '/img/svg/plus-circle-primary.svg'"
                        alt=""
                        width="20px"
                    >
                    <span class="align-top">Agregar pago</span>
                </button>

                <span v-if="payment > 1 "> Puedes registrar a un máximo de {{ payment }} métodos de pago.</span>
                <span v-else> Puedes registrar únicamente un método de pago.</span>
            </p>
        </div>
        <div class="row mb-4">
            <div class="col-1/2">
                <div class="form-control">
                    <label for="print">Impresa</label>
                    <select-field 
                        name="print" 
                        v-model="fields.print" 
                        :options="{'Sí':'Sí', 'No':'No'}"
                        @input="cambiarVariable"
                        :initial="(ServiceData.print || '')"
                    >
                    </select-field>
                    <field-errors name="print"></field-errors>
                </div>
            </div>
            <div class="col-1/2">
                <div class="form-control">
                    <label for="no_rx">N° RX</label>
                    <text-field  :disabled="fields.print == 'No'" class="field-get-researcher"  name="no_rx" v-model="fields.no_rx" maxlength="160" :initial="ServiceData.no_rx || '' "></text-field>
                    <field-errors name="no_rx"></field-errors>
                </div>
            </div>
        </div>
        
        <div class="text-center pt-8">
            <form-button class="btn--primary btn--wide">
                Enviar
            </form-button>
        </div>
   </form>
</template>

<script>
    import BaseForm from '../base/BaseForm.vue';
    import StudyForm from './StudyForm.vue';
    import PaymentForm from './PaymentForm.vue';

    export default {
        extends: BaseForm,

        components: { StudyForm, PaymentForm },
        props: {
            study: {
                required: true,
                type: Number
            },
            minStudy: {
                required: true,
                type: Number
            },
            studiesData: {
                required: true,
                type: [Array, Object]
            },
            assignedStudies: {
                required: true,
                type: Array
            },

            payment: {
                required: true,
                type: Number
            },
            minPayment: {
                required: true,
                type: Number
            },
            paymentsData: {
                required: true,
                type: [Array, Object]
            },
            assignedPayments: {
                required: true,
                type: Array
            },
            branches: {
                required: true,
                type: [Array, Object]
            },
            
            ServiceData: {
                required: true,
                type: [Object, Array]
            },

        },
        data() {
            return {
                inputdisabled : true,
                currentDate: null,
                firstTime: null,
                fields: {
                    studies_count: this.minStudy,
                    payments_count: this.minPayment,
                    service_id : this.ServiceData.id || null,
                }
            };
        },
        
        created() {
            this.current_dateTime();
        },
        mounted() {
            if(this.ServiceData.length != 0) {
                this.GetLastRX();
            }
            
            if (this.assignedStudies.length != 0) {
                this.fields.studies_count = this.assignedStudies.length;
            }
            if (this.assignedPayments.length != 0) {
                this.fields.payments_count = this.assignedPayments.length;
            }

        },
        watch: {
            firstTime: function(val) {
                this.fields._method = val === false ? 'patch' : 'post';
            }
        },

        methods: {
            cambiarVariable() {
                if(this.fields.print == 'No') {
                    this.fields.no_rx = '';
                }
            },

            GetLastRX() {
                window.axios.post(this.$root.path + '/admin/ultimo_rx/' +this.fields.branch_id)
                .then(response => {
                    if (response.data != 0) {
                        this.fields.rx_prev = response.data;
                        this.inputdisabled = true;
                    } else {
                        this.fields.rx_prev = '';
                        this.inputdisabled = false;

                    }
                        
                });
            },

            current_dateTime: function () {
                const today = new Date();
                    const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                    this.currentDate = date;

            },
            /**
             * Copy all author's fields from one card to another.
             *
             * @param {Integer} source
             * @param {Integer} target
             */
            copyAuthorFields(source, target) {
                const regex = new RegExp('^study' + source + '_');

                this.deleteAuthorFields(target);

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        this.$set(this.fields, field.replace(source, target), this.fields[field]);
                    }
                }
            },

            /**
             * Copy all author's fields from one card to another.
             *
             * @param {Integer} source
             * @param {Integer} target
             */
             copyPaymentFields(source, target) {
                const regex = new RegExp('^payment' + source + '_');

                this.deleteAuthorFields(target);

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        this.$set(this.fields, field.replace(source, target), this.fields[field]);
                    }
                }
            },


            /**
             * Delete all fields for the given author.
             *
             * @param {Integer} index
             */
            deleteAuthorFields(index) {
                const regex = new RegExp('^study' + index + '_');

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        delete this.fields[field];
                    }
                }
            },

            /**
             * Delete all fields for the given author.
             *
             * @param {Integer} index
             */
             deletePaymentFields(index) {
                const regex = new RegExp('^payment' + index + '_');

                for (let field in this.fields) {
                    if (regex.test(field)) {
                        delete this.fields[field];
                    }
                }
            },


            /**
             * Copy all necessary author fields to move their index
             * and then remove the last card.
             *
             * @param {Integer} index
             */
            removeStudies(index) {
                for (let i = 0; i < this.fields.studies_count - index; i ++) {
                    this.copyAuthorFields(index + i + 1, index + i);
                }

                this.fields.studies_count--;

                this.deleteAuthorFields(this.fields.studies_count + 1);
            },
            /**
             * Copy all necessary author fields to move their index
             * and then remove the last card.
             *
             * @param {Integer} index
             */
             removePayments(index) {
                for (let i = 0; i < this.fields.payments_count - index; i ++) {
                    this.copyPaymentFields(index + i + 1, index + i);
                }

                this.fields.payments_count--;

                this.deletePaymentFields(this.fields.payments_count + 1);
            }
        }
    };
</script>
