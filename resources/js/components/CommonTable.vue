<template>
    <v-data-table :headers="headers" :items="items" sort-by="calories" class="elevation-1">
        <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>My CRUD</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.name" label="Dessert name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.calories" label="Calories"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.fat" label="Fat (g)"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.carbs" label="Carbs (g)"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)">
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script lang="ts">
import { ref, computed, onMounted, onUnmounted, defineComponent } from '@vue/composition-api'

export default defineComponent({
    name: 'CommonTable',
    // props: {
    //     headers: {
    //         type: Array,
    //         required: true
    //     }
    // }
    setup(_props, context) {
        
        const dialog = ref<boolean>(false)

        const headers = ref<Array<Object>>([
            {
                text: 'Dessert (100g serving)',
                align: 'start',
                sortable: false,
                value: 'name'
            },
            { text: 'Calories', value: 'calories' },
            { text: 'Fat (g)', value: 'fat' },
            { text: 'Carbs (g)', value: 'carbs' },
            { text: 'Protein (g)', value: 'protein' },
            { text: 'Actions', value: 'actions', sortable: false }
        ])

        const items = ref<Array<Object>>([
            {
                name: 'Frozen Yogurt',
                calories: 159,
                fat: 6.0,
                carbs: 24,
                protein: 4.0
            },
            {
                name: 'Ice cream sandwich',
                calories: 237,
                fat: 9.0,
                carbs: 37,
                protein: 4.3
            }
        ])

        const editedIndex = ref<number>(-1)

        const formTitle = computed(() => {
            return editedIndex.value === -1 ? 'New Item' : 'Edit Item'
        })

        const editedItem = ref<Object>({
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0
        })

        const defaultItem: Object = {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0
        }

        function editItem(item: any) {
            editedIndex.value = items.value.indexOf(item)
            editedItem.value = Object.assign({}, item)
            dialog.value = true
        }

        function deleteItem(item: any) {
            const index = items.value.indexOf(item)
            confirm('Are you sure you want to delete this item?') && items.value.splice(index, 1)
        }

        function save() {
            if (editedIndex.value > -1) {
                Object.assign(items.value[editedIndex.value], editedItem.value)
            } else {
                items.value.push(editedItem.value)
            }
            close()
        }

        function close() {
            dialog.value = false
            context.root.$nextTick(() => {
                editedItem.value = Object.assign({}, defaultItem)
                editedIndex.value = -1
            })
        }

        return {
            dialog,
            headers,
            items,
            formTitle,
            editedItem,
            editItem,
            deleteItem,
            save,
            close
        }
    }
})
</script>
