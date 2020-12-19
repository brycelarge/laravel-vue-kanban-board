<template>
    <div>
        <div class="row">
            <label>Column Title:</label>
            <input class="form-input" type="text" ref="column_title" v-model="column.title" autofocus @keypress.enter.prevent="update">
        </div>
        <div class="row">
            <div class="col">
                <button class="bg-green w-100" @click.prevent="update">
                    <span v-if="column.isDefault"><i class="fas fa-plus"></i> Add</span>
                    <span v-else><i class="fas fa-edit"></i> Update</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus as Bus} from './EventBus';

    export default {
        name: "edit-column",
        props: {
            // setup default column so this component can be used to capture and edit
            column: {
                type: Object,
                default() {
                    return {
                        title: null,
                        cards: [],
                        id: null,
                        deleted: false,
                    };
                },
            },
            index: {
                type: Number,
                default: -1,
            }
        },
        methods: {
            update() {
                if (!this.column.title || this.column.title.toString().length < 3) {
                    return;
                }

                if (!this.column.id) {
                    // no db id then its new so lets push to the array
                    Bus.$emit('column-added', this.column);
                }

                Bus.$emit('persist');
            }
        },
        mounted() {
            this.$refs.column_title.focus();
        }
    }
</script>

<style scoped>

</style>
