import type { PageProps, Page } from '@inertiajs/core'

declare module '@inertiajs/vue3' {
    export function usePage(): Page<ManageUserPageProps>
}

interface ManageUserPageProps extends PageProps {
    errors: unknown,
    url: string,
    locale: string,
    auth: UserPageProps|null,
}

type UserPageProps = {
    id: string,
}

type SelectOptions = Array<{ key: string|number, value: string|null }>;
