<template>
    <v-content class="bg-white">
        <v-container fluid fill-height>
            <v-row no-gutters>
                <v-col cols="12">
                    <v-row align="center" justify="center" dense>
                        <v-col cols="12" sm="6" lg="4" xl="3">
                            <v-card class="rounded">
                                <v-row dense justify="center" align="center" class="d-flex flex-column login-form">
                                    <v-img
                                        height="160px"
                                        src="https://img5.goodfon.com/wallpaper/nbig/9/23/chertezh-karandash-linii.jpg"
                                        class="top-image"
                                    >
                                    </v-img>
                                    <v-col cols="12" md="8" align="center" class="avatar">
                                        <v-avatar size="100">
                                            <v-img
                                                src="https://yt3.ggpht.com/a/AGF-l7_P2zifWCyAidGZryYVthqroq-7IsvfPwGSlA=s900-c-k-c0xffffffff-no-rj-mo"
                                            ></v-img>
                                        </v-avatar>
                                    </v-col>
                                    <v-col cols="12" md="8">
                                        <v-card-title primary-title class="justify-center">Форма входа</v-card-title>
                                    </v-col>
                                    <v-col cols="12" md="8" class="px-4">
                                        <v-text-field
                                            v-model="email"
                                            placeholder="Логин"
                                            single-line
                                            filled
                                            dense
                                            rounded
                                            hide-details
                                            autofocus
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="8" class="px-4">
                                        <v-text-field
                                            v-model="password"
                                            placeholder="Пароль"
                                            single-line
                                            filled
                                            dense
                                            rounded
                                            hide-details
                                            autofocus
                                            type="password"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="8" class="px-4">
                                        <v-card-actions class="d-flex flex-row-reverse mt-2 mb-4 pa-0">
                                            <v-btn color="primary" rounded width="100%" @click="logIn">Войти</v-btn>
                                        </v-card-actions>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>
<script>
export default {
    name: 'login',
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        logIn() {
            if (process.browser) {
                this.$axios.$get('/sanctum/csrf-cookie').then(() => {
                    this.$auth
                        .loginWith('local', {
                            data: {
                                email: this.email,
                                password: this.password
                            }
                        })
                        .then(
                            (success) => {
                                console.log(success)
                            },
                            (error) => console.log(error.response.data)
                        )
                })
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.rounded {
    border-radius: 10px !important;
    overflow: hidden;
}
.gradient {
    background: linear-gradient(209deg, rgba(2, 0, 36, 0.4) 0%, rgba(130, 30, 89, 0.4) 41%, rgba(20, 0, 255, 0.4) 100%);
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
