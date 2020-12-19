<template>
    <div class="col-auto" v-if="!column.deleted">
        <div class="card-header bg-grey">
            <span>{{ column.title }}</span>
            <div class="right">
                <button class="bg-blue" @click="edit" title="Edit the column"><i class="fas fa-edit"></i></button>
                <button class="bg-red" @click="deleteColumn" title="Delete the column"><i class="fas fa-trash"></i></button>
                <button class="bg-green" @click.prevent="addCard" title="Add new card"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <draggable class="h-100" :list="column.cards" draggable=".card" group="cards" @change="persist">
            <card v-for="(card, index) in column.cards" :card="card" :index="index" :column="column" :key="index"></card>
        </draggable>
    </div>
</template>

<script>
    import Card from './Card';
    import {EventBus as Bus} from './EventBus';
    import Draggable from 'vuedraggable'

    export default {
        name: "column",
        components: {
            Card,
            Draggable,
        },
        props: {
            column: {type: Object},
            index: {type: Number, default: -1},
        },
        data() {
            return {
                cards: this.column.cards,
                selectedColumn: null,
                selectedCard: null,
                addingColumn: null,
                addingCard: null,
            }
        },
        methods: {
            edit() {
                Bus.$emit('edit-column', this.column);
            },
            addCard() {
                Bus.$emit('add-card', this.column);
            },
            persist() {
                Bus.$emit('persist');
            },
            deleteColumn() {
                if (this.column.id) {
                    this.column.deleted = true;
                    this.persist();
                } else {
                    Bus.$emit('column-deleted', this.column);
                }
            }
        },
    }
</script>

<style scoped>

</style>
