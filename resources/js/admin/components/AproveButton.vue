<template>
    <button :class="classes" type="button"
        @click.prevent="showConfirmation"
    >
        <slot>Aprobar</slot>
    </button>
</template>

<script>
import merge from 'deepmerge';

export default {
    props: {
        url: {
            type: String,
            required: true
        },
        classes: {
            type: String,
            required: false,
            default: 'btn btn--sm btn-nowrap btn--approve'
        },
        resourceId: {
            type: Number,
            required: true
        },
        options: {
            type: Object,
            required: false
        }
    },

    data() {
        return {
            defaults: {
                colors : {
                    confirm : '#16a34a', // verde
                    cancel  : '#d33'
                },
                localization : {
                    es : {
                        title             : '¿Aprobar solicitud?',
                        text              : 'Esta acción permitirá a otros usuarios eliminar el registro.',
                        cancelButtonText  : 'Cancelar',
                        confirmText       : 'Sí, aprobar',
                        errorText         : 'Mensaje recibido:',
                        errorTitle        : 'No se pudo aprobar la solicitud.',
                        successText       : 'La solicitud fue aprobada correctamente.',
                        successTitle      : 'Aprobado'
                    },
                    en : {
                        title             : 'Approve request?',
                        text              : 'This action will allow other users to delete the record.',
                        cancelButtonText  : 'Cancel',
                        confirmText       : 'Approve',
                        errorText         : 'Message:',
                        errorTitle        : 'The request could not be approved.',
                        successText       : 'The request has been approved.',
                        successTitle      : 'Approved'
                    }
                },
                lang : document.documentElement.lang.toLowerCase().substr(0, 2),
                onApprove : this.onApprove
            },
            settings: {},
            localization: {}
        };
    },

    created() {
        this.settings = merge(this.defaults, this.options || {});
        this.localization = this.settings.localization[this.settings.lang] || this.settings.localization.en;
    },

    methods: {
        approveResource() {
            return new Promise((resolve, reject) => {
                window.axios.post(this.url)
                    .then(response => {
                        if (response.status === 200) {
                            return resolve();
                        }
                        this.showError(response.statusText);
                    })
                    .catch(this.showError.bind(this));
            });
        },

        onApprove(ApproveButton) {
            ApproveButton.showSuccess();
        },

        showConfirmation() {
            window.swal({
                title               : this.localization.title,
                text                : this.localization.text,
                type                : 'warning',
                showCancelButton    : true,
                cancelButtonText    : this.localization.cancelButtonText,
                confirmButtonColor  : this.settings.colors.confirm,
                confirmButtonText   : this.localization.confirmText,
                showLoaderOnConfirm : true,
                preConfirm          : this.approveResource.bind(this)
            })
            .then((response) => {
                if (response.dismiss) {
                    return;
                }
                this.settings.onApprove(this)
            })
        },

        showError(error) {
            window.swal({
                title              : this.localization.errorTitle,
                text               : this.localization.errorText + ' ' + error,
                type               : 'error',
                confirmButtonColor : this.settings.colors.confirm,
                confirmButtonText  : this.localization.confirmText
            });
        },

        showSuccess() {
            window.swal({
                title              : this.localization.successTitle,
                text               : this.localization.successText,
                type               : 'success',
                confirmButtonColor : this.settings.colors.confirm,
                confirmButtonText  : this.localization.confirmText
            }).then(() => {
                location.reload(); // refresca tabla
            });
        }
    }
};
</script>
