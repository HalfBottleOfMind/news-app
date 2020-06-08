<template>
    <v-content class="bg-white">
        <v-container fluid fill-height>
            <v-row no-gutters>
                <v-col cols="12">
                    <v-row align="center" justify="center" dense>
                        <v-col cols="12" sm="6" lg="4" xl="3">
                            <v-card class="rounded">
                                <v-row dense justify="center" align="center" class="d-flex flex-column login-form">
                                    <v-img height="160px" src="/images/form_bg.jpg" class="top-image"> </v-img>
                                    <v-col cols="12" md="10" align="center" class="avatar">
                                        <v-avatar size="100">
                                            <v-img src="/images/avatar_placeholder.png"></v-img>
                                        </v-avatar>
                                    </v-col>
                                    <v-col cols="12" md="10">
                                        <v-card-title primary-title class="justify-center py-1">{{
                                            $t('register')
                                        }}</v-card-title>
                                    </v-col>
                                    <v-col cols="12" md="10" class="px-4">
                                        <v-form ref="form" @submit.prevent="register">
                                            <v-text-field
                                                v-model="name"
                                                name="name"
                                                :label="$t('name')"
                                                prepend-inner-icon="mdi-account"
                                                autocomplete="off"
                                                hide-details
                                                single-line
                                                filled
                                                dense
                                                rounded
                                                required
                                            />
                                            <v-text-field
                                                v-model="email"
                                                class="mt-3"
                                                name="email"
                                                :label="$t('email')"
                                                prepend-inner-icon="mdi-email"
                                                autocomplete="off"
                                                hide-details
                                                single-line
                                                filled
                                                dense
                                                rounded
                                                required
                                            />
                                            <v-text-field
                                                v-model="password"
                                                class="mt-3"
                                                name="password"
                                                prepend-inner-icon="mdi-onepassword"
                                                :append-icon="showPass ? 'mdi-eye-off' : 'mdi-eye'"
                                                :type="showPass ? 'text' : 'password'"
                                                :label="$t('password')"
                                                hide-details
                                                single-line
                                                filled
                                                dense
                                                rounded
                                                autocomplete="new-password"
                                                @click:append="showPass = !showPass"
                                            />
                                            <v-text-field
                                                v-model="passwordConfirmation"
                                                class="mt-3"
                                                name="passwordConfirmation"
                                                prepend-inner-icon="mdi-onepassword"
                                                :append-icon="showPass ? 'mdi-eye-off' : 'mdi-eye'"
                                                :type="showPass ? 'text' : 'password'"
                                                :label="$t('passwordConfirmation')"
                                                hide-details
                                                single-line
                                                filled
                                                dense
                                                rounded
                                                autocomplete="new-password"
                                                @click:append="showPass = !showPass"
                                            />
                                            <v-card-actions class="d-flex flex-row-reverse mt-3 mb-4 pa-0">
                                                <v-btn color="primary" rounded type="submit" width="100%">{{
                                                    $t('register')
                                                }}</v-btn>
                                            </v-card-actions>
                                        </v-form>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
        <AlertMessage />
    </v-content>
</template>
<script lang="ts">
import { toRefs, ref, computed, reactive, defineComponent } from '@vue/composition-api'
export default defineComponent({
    name: 'register',
    setup(_props, context) {
        const app = context.root

        const showPass = ref<boolean>(false)

        const data = reactive({
            name: '',
            email: '',
            password: '',
            passwordConfirmation: ''
        })

        async function register() {
            await app.$axios
                .post('/register', data)
                .then((data) => {
                    console.log(data)
                })
                .catch(({ response }) => {
                    app.$store.dispatch('alert/flashErrorMessage', { color: 'error', messages: response.data.errors })
                })
        }

        return {
            ...toRefs(data),
            register,
            showPass
        }
    }
})
</script>
<style lang="scss" scoped>
.rounded {
    border-radius: 10px !important;
    overflow: hidden;
}
.login-form {
    position: relative;
    .top-image {
        margin-bottom: 60px;
    }
    .avatar {
        top: 110px;
        position: absolute;
    }
}
</style>
