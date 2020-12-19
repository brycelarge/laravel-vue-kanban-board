<template>
    <div class="card" v-if="!card.deleted">
        <div class="card-header bg-light-grey">
            <span>{{ card.title }}</span>
            <div class="right">
                <button class="bg-blue" @click="edit" title="Edit the card"><i class="fas fa-edit"></i></button>
                <button class="bg-red" @click="deleteCard" title="Delete the card"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body">{{ card.description }}</div>
    </div>
</template>

<script>
    import {EventBus as Bus} from './EventBus';

    export default {
        name: "card",
        props: {
            card: {type: Object},
            column: {type: Object},
            index: {type: Number, default: -1},
        },
        methods: {
            edit() {
                Bus.$emit('edit-card', this.column, this.card);
            },
            deleteCard() {
                if (this.card.id) {
                    this.card.deleted = true;
                    Bus.$emit('persist');
                } else {
                    this.column.cards.splice(this.index, 1);
                }
            }
        },
    }
</script>

<style scoped>

</style>
