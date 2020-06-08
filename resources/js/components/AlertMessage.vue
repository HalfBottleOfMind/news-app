<template>
    <v-snackbar v-model="show" :color="color" multi-line bottom right :timeout="10000" class="ma-4">
        <div v-if="message">
            {{ message }}
        </div>
        <div v-if="errorMessages">
            <ul v-for="(message, index) in errorMessages" :key="index">
                <li>{{ message }}</li>
            </ul>
        </div>
        <v-btn text @click="show = false">
            {{ $t('close') }}
        </v-btn>
    </v-snackbar>
</template>

<script lang="ts">
import { computed, defineComponent } from '@vue/composition-api'
export default defineComponent({
    name: 'AlertMessage',
    setup(_props, context) {
        const store = context.root.$store

        const show = computed({
            get: (): boolean => store.getters['alert/getShow'],
            set: (value) => {
                store.dispatch('alert/setShow', value)
            }
        })

        const color = computed({
            get: (): boolean => store.getters['alert/getColor'],
            set: (value) => {
                store.dispatch('alert/setColor', value)
            }
        })

        const message = computed({
            get: (): boolean => store.getters['alert/getMessage'],
            set: (value) => {
                store.dispatch('alert/setMessage', value)
            }
        })

        const errorMessages = computed({
            get: (): boolean => store.getters['alert/getErrorMessages'],
            set: (value) => {
                store.dispatch('alert/setErrorMessages', value)
            }
        })

        return {
            show,
            color,
            message,
            errorMessages
        }
    }
})
</script>
