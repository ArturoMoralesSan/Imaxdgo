<template>
    <div class="announcement-filter_container">
        <div v-if="breakpointMatches" class="announcement-menu_container">
            <button v-for="category in categoriesMenu"
                v-on:click="AddElMenu(category.name)"
                class="btn announcement-filter-button"
                :class="{ 'announcement-filter-button--active' : categoriesActive.includes(category.name) }">
                {{ category.name }}
            </button>
        </div>
        <div v-else class="announcement-menu_container">
            <button
                v-on:click="toggleFilter"
                class="btn announcement-filter-button">
                <slot name="filter-icon"></slot> Filtro
            </button>

            <form
                class="filter-container"
                :class="{
                    'filter--open': filterIsOpen,
                    'filter--closed': ! filterIsOpen && buttonClicked
                }"
                :aria-hidden="! filterIsVisible"
            >

                <div class="filter-content">
                    <button class="filter-close"
                        v-on:click="closeFilterModal"
                        >
                        <slot name="filter-close"></slot>
                    </button>
                    <div v-for="category in categories" class="filter-checkbox">
                        <label>
                            <input id="categories" type="checkbox" class="checkbox" name="categories" v-model="checkboxActive" :value="category.name">

                            {{ category.name }}
                        </label>
                    </div>
                </div>
                 <button
                    class="btn filter-button btn--success"
                    v-on:click="filterCard"
                    >
                    Aceptar
                </button>
            </form>
        </div>

        <filter-card
            :announcements="callsFiltered"
            :categoriesActive="categoriesActive"
            :breakpoint="480"
            :callCount="callsFiltered.length"
            :widthContainer="100 *callsFiltered.length"
        >
        </filter-card>

    </div>

</template>
<script>
    import FilterCard from './FilterCard.vue';

    export default {
        components: {FilterCard},

        props: {

            /**
             * Viewport breakpoint to change the style to "desktop" view.
             */
            breakpoint: {
                type: Number,
                required: true,
            },
            calls: {
                type: Array,
                required: true,

            },
            categories: {
                type:Array,
                required:true,
            }
        },
        data() {
            return {
                breakpointMatches: false,
                buttonClicked: false,
                filterIsVisible: false,
                filterIsOpen: false,
                callsFiltered: this.calls,
                categoriesMenu: [{name:'Todos'}],
                categoriesActive: ['Todos'],
                checkboxActive: [],

                css: {
                    overflowHidden: 'overflow-hidden'
                }
            };
        },


        mounted: function () {
            this.categories.forEach(item => this.categoriesMenu.push({ name:item.name }));
            this.categories.forEach(item => this.categoriesActive.push(item.name));

            this.fillFilters();

            this.setMatchMedia();
            this.$root.$on('overlayClosed', this.closeFilter);
        },

        methods: {
            AddElMenu (category) {
                if (category == 'Todos') {
                    this.categoriesActive = [];
                    this.categoriesActive.push('Todos');
                    this.categories.forEach(item => this.categoriesActive.push(item.name));
                }

                if(category != 'Todos') {
                    if (this.categoriesActive.includes(category)) {
                        var index = this.categoriesActive.indexOf(category);
                        this.categoriesActive.splice(index, 1);

                    }  else {
                        this.categoriesActive.push(category);
                    }

                    if (this.categoriesActive.includes('Todos')) {
                        var index = this.categoriesActive.indexOf('Todos');
                        this.categoriesActive.splice(index, 1);
                    }
                }

                if (this.categories.length == this.categoriesActive.length) {
                    this.categoriesActive.splice(0, 0, "Todos");
                }

                this.callsFiltered = this.calls.filter(this.callFilter);

            },

            filterCard (e) {
                e.preventDefault();
                this.categoriesActive = this.checkboxActive;

                this.callsFiltered = this.calls.filter(this.callFilter);

                if (this.categories.length == this.categoriesActive.length && this.categoriesActive.includes('Todos', 0)) {
                    this.categoriesActive.splice(0, 1);
                } else if(this.categories.length == this.categoriesActive.length) {
                    this.categoriesActive.splice(0, 0, "Todos");
                }

                this.closeFilter();
            },

            callFilter(call) {
                return this.categoriesActive.includes(call.category.name);
            },


            /**
             * Initialize media query.
             */
            setMatchMedia() {
                const mq = window.matchMedia(`screen and (min-width:${this.breakpoint}px)`);

                mq.addListener(this.reset);

                this.reset(mq);
            },

            /**
             * Close site filter on responsive view.
             */
            closeFilter() {
                this.filterIsOpen = false;
                this.filterIsVisible = false;

                document.body.classList.remove(this.css.overflowHidden);
                this.$root.$emit('closeOverlay');
            },

            /*
             * Diplay site filter on responsive view.
             */
            openFilter() {
                this.filterIsOpen = true;
                this.filterIsVisible = true;

                document.body.classList.add(this.css.overflowHidden);
                this.$root.$emit('showOverlay');
            },

            /**
             * Toggle filter visibility.
             */
            toggleFilter() {
                this.buttonClicked = true;

                if (this.filterIsOpen) {
                    this.closeFilter();
                }
                else {
                    this.openFilter();
                }
            },


            /**
             * Immediately show or hide filter when media match is triggered.
             *
             * @param {Object} e
             */
            reset(e) {
                if (this.filterIsOpen) {
                    document.body.classList.remove(this.css.overflowHidden);
                    this.$root.$emit('closeOverlay');
                }

                this.filterIsOpen = false;
                this.filterIsVisible = e.matches;

                this.breakpointMatches = e.matches;
                this.buttonClicked = false;

                this.fillFilters;
            },

            closeFilterModal(e) {
                e.preventDefault();
                this.closeFilter();
                this.fillFilters();
            },

            fillFilters() {
                this.checkboxActive = this.categoriesActive;
            }
        }
    }


</script>
