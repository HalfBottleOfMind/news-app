import Vue from 'vue'
import { AxiosStatic } from 'axios'

export interface RootState {}

export interface MetaOrderBy {
    name: string
    value: string
    directionAsc: boolean
}

export interface Storage {
    setUniversal(key: string, value: any, isJson?: boolean): string
    getUniversal(key: string, isJson?: boolean): any
    syncUniversal(key: string, defaultValue: any, isJson?: boolean): any
    setState(key: string, val: any): string
    getState(key: string): string
    watchState(key: string, handler: (newValue: any) => void): any
    setCookie(key: string, val: any, options?: object): any
    getCookie(key: string, isJson?: boolean): any
    setLocalStorage(key: string, val: any, isJson?: boolean): any
    getLocalStorage(key: string, isJson?: boolean): any
}

export interface Auth<T = any> {
    ctx: any
    $state: any
    $storage: Storage
    user: Partial<T>
    loggedIn: boolean
    loginWith(strategyName: string, ...args: any): Promise<never>
    login(...args: any): Promise<never>
    logout(): Promise<never>
    fetchUser(): Promise<never>
    fetchUserOnce(): Promise<never>
    hasScope(scopeName: string): boolean
    setToken(strategyName: string, token?: string): string
    getToken(strategyName: string): string
    syncToken(strategyName: string): string
    onError(handler: (error: Error, name: string, endpoint: any) => void): any
    setUser(user?: Partial<T>): any
    reset(): Promise<never>
    redirect(name: string): any
    onRedirect(listener: () => void): void
    strategy(): string
    registerStrategy(strategyName: string, strategy: object): void
    setStrategy(strategyName: string): void
    setUserToken(token: string): Promise<void>
    getRefreshToken(strategyName: string): string
    setRefreshToken(strategyName: string, token?: string): string
    syncRefreshToken(strategyName: string): string
}

declare module 'vue/types/vue' {
    interface Vue {
        $axios: AxiosStatic
    }
}