<script>
    export default {
        props: {
            breakpoint: {
                default: 800
            },
            model: {
                default: () => []
            },
            uploadEvent: {
                type: String,
                default: 'fileUploaded'
            }
        },

        data() {
            return {
                resourceList: this.model,
                wideView: true
            };
        },

        watch: {
            resourceList: function() {
                this.updateRootCount();
                this.updateFile();
            }
        },

        mounted() {
            this.updateRootCount();

            const mq = window.matchMedia(`screen and (min-width:${this.breakpoint}px)`);

            mq.addListener(this.match);

            this.wideView = mq.matches;

            this.$root.$on(this.uploadEvent, this.addItem);
        },

        methods: {

            /**
             * update item to resource list.
             *
             * @param {object} item
             */
            addItem(item) {
                this.resourceList.push(item);

            },

            /**
             * Add item to resource list.
             *
             * @param {object} item
             */
            updatefileList(item) {
                this.resourceList.forEach(function(element) {

                    if (element.id == item.id) {
                        element.link_name = item.link_name;
                        element.icon      = item.icon;
                        element.file      = item.file;
                    }
                });
            },

            /**
             * Add item to resource list.
             *
             * @param {object} item
             */
            updateSpeakerList(item) {
                this.resourceList.forEach(function(element) {
                    if (element.id == item.id) {
                        element.name = item.name;
                    }
                });
            },

            /**
             * Add item to resource list.
             *
             * @param {object} item
             */
            updateReviewList(item) {
                this.resourceList.forEach(function(element) {
                    if (element.id == item.id) {
                        element.name = item.name;
                        element.review = item.review;
                    }
                });
            },

            /**
             * Add class to display table for large viewports.
             *
             * @param {Object} e
             */
            match(e) {
                this.wideView = e.matches;
            },


            /**
             * Remove row after the resource has been deleted.
             *
             * @param {DeleteButton} DeleteButton
             */
            onResourceDelete(DeleteButton) {
                const index = this.resourceList.map(item => item.id).indexOf(DeleteButton.resourceId);

                this.resourceList.splice(index, 1);

                DeleteButton.showSuccess();
            },


            /**
             * Update the count of resource items in root component.
             */
            updateRootCount() {
                this.$root.resourceCount = this.resourceList.length;
            },
            updateFile() {
                var list=[];

                this.resourceList.forEach(function(element) {
                      list.push(element.id);
                });

                this.$emit('updatefile', 'files', list);
                this.$emit('updateprovider', 'review', list);
            }
        }
    };
</script>
