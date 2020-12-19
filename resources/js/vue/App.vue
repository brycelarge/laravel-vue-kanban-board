<template>
    <div class="w-100">
        <div class="card-header">
            <span>Kanban Board</span>
            <div class="right">
                <button class="bg-green" @click.prevent="addColumn"><i class="fas fa-plus"></i> Add Column</button>
                <button class="bg-red" @click.prevent="dbDump"><i class="fas fa-cloud-download-alt"></i></i> Download DB</button>
            </div>
        </div>
        <div class="flex-grid h-100">
            <column v-for="(column, index) in columns" :column="column" :key="index" :index="index"></column>
        </div>
        <modal name="modal" transition="nice-modal-fade" height="auto" width="50%" style="z-index: 9999;" @closed="resetState">
            <edit-column v-if="selectedColumn && !addingCard && !selectedCard" :column="selectedColumn"></edit-column>
            <edit-column v-if="addingColumn"></edit-column>
            <edit-card v-if="selectedColumn && selectedCard" :column="selectedColumn" :card="selectedCard"></edit-card>
            <edit-card v-if="addingCard" :column="selectedColumn"></edit-card>
        </modal>
        <vue-toastr ref="messages"></vue-toastr>
        <p>The styling is not bootstrap, I use bootstrap daily so my design is inspired from what my eyes are used to seeing daily.</p>
        <p>I have a much better and more complex side project I have been working on if someone has the time to have a zoom or teams meeting with me so I can take you through it?</p>
        <p>Im have just become a father so my time is limited, all in all around 6 hours to do this project in between naps and poop diapers :-)</p>
    </div>
</template>

<script>
    import Column from './Column';
    import EditCard from './EditCard';
    import EditColumn from './EditColumn';
    import {EventBus as Bus} from './EventBus';
    import * as _ from "lodash";

    export default {
        name: "kanban-board",
        components: {
            Column,
            EditCard,
            EditColumn,
        },
        data() {
            return {
                columns: [],
                selectedColumn: null,
                selectedCard: null,
                addingColumn: null,
                addingCard: null,
                loading: false,
            }
        },
        methods: {
            openModal() {
                this.$modal.show('modal');
            },
            closeModal() {
                this.$modal.hide('modal');
            },
            resetState() {
                this.selectedColumn = null;
                this.selectedCard = null;
                this.addingColumn = null;
                this.addingCard = null;
            },
            dbDump() {
                window.location.href = '/db/dump';
            },
            addColumn() {
                this.addingColumn = true;
                this.openModal();
            },
            getColumns() {
                if (this.loading) {
                    return;
                }

                this.loading = true;
                axios.get('/columns')
                    .then((response) => {
                        this.columns = response.data;
                    })
                    .catch((error) => {
                        this.$refs.messages.e('Unable to retrieve saved columns');
                        this.displayAxiosError(error);
                        console.error(error);
                    })
                    .finally(() => this.loading = false);
            },
            persisData() {
                this.closeModal();
                if (this.loading) {
                    return;
                }

                this.loading = true;
                axios.post('/columns/persist', {columns: this.columns})
                    .then((response) => {
                        this.columns = response.data;
                        this.$refs.messages.s('You board has been saved successfully!');
                    })
                    .catch((error) => {
                        this.$refs.messages.e('We were unable to save your board, if you leave the page you could loose data. Click on the save button to try again.');
                        this.displayAxiosError(error);
                        console.error(error);
                    })
                    .finally(() => this.loading = false);
            },
            displayAxiosError(error) {
                // validation errors from laravel
                if (error.response.status === 422) {
                    this.displayValidationErrors(error.response.data.error.errors);
                }

                this.$refs.messages.e(`An unknown error occurred: ${error.response.statusText}`);
            },
            displayValidationErrors(errors) {
                _.forEach(errors, error => {
                    this.$refs.messages.e(error.join('<br>'));
                });
            }
        },
        mounted() {
            this.getColumns();
            this.$refs.messages.defaultPosition = "toast-top-right";

            Bus.$on('edit-column', (column) => {
                this.selectedColumn = column;
                this.openModal();
            });

            Bus.$on('column-added', (column) => {
                this.columns.push(column);
                this.closeModal();
            });

            Bus.$on('column-deleted', (column) => {
                // just in case someone forgets the soft delete
                if (!column.id) {
                    let columnIndex = this.columns.indexOf(column);
                    this.columns.splice(columnIndex, 1);
                }
            });

            Bus.$on('edit-card', (column, card) => {
                this.selectedColumn = column;
                this.selectedCard = card;
                this.openModal();
            });

            Bus.$on('add-card', (column) => {
                this.selectedColumn = column;
                this.addingCard = true;
                this.openModal();
            });

            Bus.$on('close-modal', this.closeModal);
            Bus.$on('persist', this.persisData);
        },
    }
</script>

<style scoped>

</style>
