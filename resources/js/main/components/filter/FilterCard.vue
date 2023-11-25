<template>
    <div class="announcement-container">
        <div id="announcement-card_container"  ref="card_container" class="announcement-card_container row">
            <div v-for="(announcement) in announcements"
                class="announcement-card"
                ref="card_element"
            >
                <div class="announcement-image_container">
                    <img :src="$root.path+'/archivos/convocatorias/'+announcement.slug+'/'+announcement.cover" class="announcement-image" alt="convocatoria UJED">
                </div>
                <div class="announcement-data">
                    <span class="announcement-card__category">{{ announcement.category.name }}</span>
                    <h3 v-if="announcement.category.key_name == 'ujed'" class="h5 announcement-card-title">{{ announcement.title }}</h3>
                    <p v-if="announcement.category.key_name == 'ujed'" class="announcement-card-description">
                        Periodo del {{ announcement.fecha_inicio }} al {{ announcement.fecha_termino }}
                    </p>
                    <p class="announcement-card-description">
                        {{ announcement.description }}
                    </p>
                    <ul class="list-bare">
                        <li v-if="announcement.category.key_name == 'ujed'">
                            <a class="announcement-link" :href="'convocatoria-registro/'+announcement.slug">Registro</a>
                        </li>
                        <li>
                            <a class="announcement-link" target="_blank" :href="'convocatoria-archivo/'+announcement.slug+'/'+announcement.document">Ver convocatoria</a>
                        </li>
                        <li v-for="file in announcement.call_files">
                            <a class="announcement-link" target="_blank" :href="'convocatoria-archivo/'+announcement.slug+'/'+file.document">{{ file.title }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            announcements: {
                type: Array,
                required: true
            },
            categoriesActive: {
                type:Array,
                required:true
            },

            callCount: {
                type: Number,
                required: true,
            },
            breakpoint: {
                type: Number,
                required: true,
            },
            widthContainer: {
                type:Number,
                required: true
            }
        },

        data() {
            return {
                activeCall: 0,
                breakpointMatches: false,
                cardWidth: 0,
                sensitivity: 25,
                callManager: null
            };
        },

        mounted: function () {
            this.setMatchMedia();
        },

        watch: {
            widthContainer: function() {
                if (!this.breakpointMatches) {
                    this.resetCall();
                }
            }
        },

        methods: {

            /**
             * Initialize media query.
             */

            setMatchMedia() {
                const mq = window.matchMedia(`screen and (min-width:${this.breakpoint}px)`);

                mq.addListener(this.reset);
                mq.addListener(this.swiper);
                this.reset(mq);
                this.swiper();
            },

            swiper() {
                var containerCall  = this.$refs.card_container;
                if (!this.breakpointMatches) {

                    window.addEventListener('resize', this.getWidthcard);

                    if (! this.callManager) {

                        this.callManager = new window.hammer.Manager(containerCall);
                        this.callManager.add(new window.hammer.Pan({threshold: 0, pointers: 0 }));
                        this.callManager.on('pan', function(e) {
                            this.calculatePercentage(e);
                        }.bind(this));

                    }

                    this.callManager.get('pan').set({ enable: true });
                    this.resetCall();

                } else {
                    if (this.callManager) {
                        this.callManager.get('pan').set({ enable: false });
                        window.removeEventListener('resize', this.getWidthcard);
                        this.activeCall = 0;
                        containerCall.style = '';
                    }
                }
            },

            /**
             * Immediately show or hide filter when media match is triggered.
             *
             * @param {Object} e
             */
            reset(e) {
                this.breakpointMatches = e.matches;
            },

            resetCall() {
                this.$refs.card_container.style.width = this.widthContainer+'%';
                this.activeCall = 0;
                if (this.widthContainer != 0) {
                    Vue.nextTick(function () {
                        this.gettranslate();
                    }.bind(this));
                }
            },

            calculatePercentage(e) {

                var percentage = 100 / this.callCount * e.deltaX / window.innerWidth;
                var margin = (window.innerWidth - this.cardWidth) / 2;
                var marginvw = (margin / window.innerWidth) * 100;
                var vw = (this.cardWidth / window.innerWidth) * 100;
                var transformPercentage = percentage - vw  * this.activeCall+ marginvw;
                this.$refs.card_container.style.transform = 'translateX(' + transformPercentage +'vw)';

                if(e.isFinal) {
                    if(e.velocityX > 1) {
                        this.goToCall(this.activeCall - 1);
                    } else if(e.velocityX < -1) {
                        this.goToCall(this.activeCall + 1)
                    } else {
                        if(percentage <= -(this.sensitivity / this.callCount))
                            this.goToCall(this.activeCall + 1);
                        else if(percentage >= (this.sensitivity / this.callCount))
                            this.goToCall(this.activeCall - 1);
                        else
                            this.goToCall(this.activeCall);
                    }
                }
            },

            goToCall(number) {
                if(number < 0)
                    this.activeCall = 0;
                else if(number > this.callCount - 1)
                    this.activeCall = this.callCount - 1
                else
                    this.activeCall = number;

                this.$refs.card_container.classList.add( 'is-animating' );
                var margin = (window.innerWidth - this.cardWidth) / 2;
                var marginvw = (margin / window.innerWidth) * 100;
                var vw = (this.cardWidth / window.innerWidth) * 100;

                var percentage = (-vw * this.activeCall)+marginvw;
                this.$refs.card_container.style.transform = 'translateX(' + percentage + 'vw)';
                var callcontainer = this.$refs.card_container;
                clearTimeout(callcontainer);

                callcontainer = setTimeout(function() {
                   this.$refs.card_container.classList.remove('is-animating');
                }.bind(this), 400);
            },

            gettranslate() {
                this.cardWidth = this.$refs.card_element[0].clientWidth;
                var margin = (window.innerWidth - this.cardWidth) / 2;
                var marginvw = (margin / window.innerWidth) * 100;
                this.$refs.card_container.style.transform = 'translateX('+marginvw+'vw)';
            },

            getWidthcard() {
                this.cardWidth = this.$refs.card_element[0].clientWidth;
            },
        },

    }
</script>