<template>
    <div>
        <div class="row">
            <label>Card Title:</label>
            <input class="form-input" type="text" ref="card_title" min="3" required v-model="card.title" @keypress.enter.prevent="update">
        </div>
        <div class="row">
            <label>Card Description:</label>
            <input class="form-input" type="text" v-model="card.description" @keypress.enter.prevent="update">
        </div>
        <div class="row">
            <div class="col">
                <button class="bg-green w-100" @click.prevent="update">
                    <span v-if="card.isDefault"><i class="fas fa-plus"></i> Add</span>
                    <span v-else><i class="fas fa-edit"></i> Update</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus as Bus} from './EventBus';

    export default {
        name: "edit-card",
        props: {
            // setup default card so this component can be used to capture and edit
            card: {
                type: Object,
                default() {
                    return {
                        title: null,
                        description: null,
                        id: null,
                        deleted: false,
                    };
                },
            },
            column: {type: Object},
        },
        methods: {
            update() {
                if (!this.card.title || this.card.title.toString().length < 3) {
                    return;
                }

                if (!this.card.id) {
                    // no db id then its new so lets push to the array
                    this.column.cards.push(this.card);
                }

                Bus.$emit('persist');
            }
        },
        mounted() {
            this.$refs.card_title.focus();
        }
    }
</script>

<style scoped>

</style>
