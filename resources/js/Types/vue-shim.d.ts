import { ComponentCustomProperties } from 'vue';

declare module "*.vue" {
    import { defineComponent } from "vue";
    const component: ReturnType<typeof defineComponent>;
    export default component;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $t: (key: string, params?: object) => string,
        $choice: (key: string, amount: number, params?: object) => string,
        $filters: {
            escape: (content: string) => string
        },
    }
}
