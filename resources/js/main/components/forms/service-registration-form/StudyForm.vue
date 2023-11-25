<template>
    <div class="box box--lg bg-white b-1 rounded relative">
        <h3>
            Estudio {{ index }}
        </h3>

        <button v-if="index > minStudy"
            class="btn btn--danger btn--xs rounded-0 absolute top-0 right-0"
            type="button"
            @click="$emit('remove', index)"
        >
            Eliminar
        </button>
        <div class="row mb-4">
            <div class="md:col-2/3 sm:col">
                <div class="form-control">
                    <label :for="'study' + index + '-estudio'">Estudio</label>
                    <select-field
                    :name="'study' + index + '_estudio'"
                    v-model="fields['study' + index + '_estudio']"
                    :options="studiesData"
                    :initial="((typeof assignedStudies[index-1] !== 'undefined') ? assignedStudies[index-1].id.toString() : '')"
                    >
                    </select-field>
                    <field-errors :name="'study' + index + '_estudio'"></field-errors>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import SelectField from '../base/SelectField.vue';
    import TextField from '../base/TextField.vue';
    import FieldErrors from '../base/FieldErrors.vue';

    export default {
        components: { SelectField, FieldErrors, TextField },

        props: {
            studiesData: {
                type: [Object, Array],
                required: true
            },
            index: {
                type: Number,
                required: true
            },

            errors: {
                type: Object,
                required: true
            },

            fields: {
                type: Object,
                required: true
            },
            
            minStudy: {
                required: true,
                type: Number
            },
            assignedStudies: {
                required: true,
                type: Array
            },
        },
        data() {
            return {
                noExistAlert: false,

            };
        },
    };
</script>
