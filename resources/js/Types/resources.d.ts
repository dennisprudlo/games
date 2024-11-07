/**
 * #MARK: Pagination
 */

export type PaginationLinks = {
    first: string,
    last: string,
    prev: string|null,
    next: string|null,
}

export type PaginationMeta = {
    total: number,
    from: number|null,
    to: number|null,
    current_page: number,
    last_page: number,
    per_page: number,
    path: string,
}

export type PaginatedResource<PaginatedType> = {
    data: Array<PaginatedType>
    links: PaginationLinks,
    meta: PaginationMeta,
}

/**
 * #MARK: Models
 */

export type UserResource = {
    id: string,
}

/**
 * #MARK: Other
 */

export type GameResource = {
    id: string,
    title: string,
    short_description: string,
    description: string,
    players: Array<number>,
    type: {
        id: string,
        title: string,
        icon: string,
    },
    requires_authentication: boolean,
    starred: boolean,
    trivia: Array<string>,
}
