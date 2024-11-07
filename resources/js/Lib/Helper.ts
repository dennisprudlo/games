/**
 * #MARK: General
 */

/**
 * Generates a url for a given path.
 */
export const baseUrl = (path: string) => {
    return document.location.origin + (path.startsWith('/') ? path : '/' + path);
}

/**
 * Gets the current locale of the application.
 */
export const getCurrentLocale = () => {
    return document.querySelector('html')?.getAttribute('lang') || 'en'
}

/**
 * Formats a UID of an entity to a truncated version for display.
 */
export const formatEntityId = (id: string) => {
    return '…' + id.substring(id.length - 5);
}

/**
 * Creates an empty range of numbers.
 * @param start The start of the range
 * @param end The end of the range
 * @returns An array of numbers from start to end
 */
export const range = (start: number, end: number) => Array.from({ length: end - start + 1 }, (_, i) => start + i);

/**
 * Generates a random UUID.
 * @returns A random UUID
 */
export const uuid = (): string => {
    // @ts-ignore
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

/**
 * Sends an in app notification
 * @param message The message to send
 * @param error Whether the message is an error message
 */
export const notify = (message: string, error: boolean = false): void => {
    const detail = {
        uuid: uuid(),
        message: message,
        error: error,
    }

    window.dispatchEvent(new CustomEvent('notify', { detail }));
}

/**
 * #MARK: Date Formatting
 */

/**
 * Enforces a date time of either a date object or a string
 */
const toDate = (date: Date|string) => {
    return new Date(date);
}

/**
 * Formats a passed date to a human readable date format.
 */
export const formatDate = (date: Date|string|null, options: Intl.DateTimeFormatOptions = {}) => {
    if (! date) {
        return '';
    }

    return toDate(date).toLocaleDateString(getCurrentLocale(), Object.assign({
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'UTC',
    }, options));
};

/**
 * Formats a passed date to a human readable time format.
 */
export const formatTime = (date: Date|string) => {
    return toDate(date).toLocaleTimeString(getCurrentLocale(), {
        hour: 'numeric',
        minute: 'numeric',
        timeZone: 'UTC',
    });
};

/**
 * Formats a passed date time to a human readable datetime format.
 */
export const formatDateTime = (date: Date|string|null) => {
    if (! date) {
        return '';
    }

    return toDate(date).toLocaleString(getCurrentLocale(), {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

export const getMonthName = (month: number) => {
    const date = new Date(Date.UTC(2000, month, 1));
    return date.toLocaleString('en', { month: 'long' }).toLowerCase();
};

export const getDateForInput = (date: string|null) => {
    if (! date) return '';

    const instance = new Date(date);
    return `${ instance.getFullYear() }-${ String(instance.getUTCMonth() + 1).padStart(2, '0') }-${ String(instance.getUTCDate()).padStart(2, '0') }`;
}

export const getTimeForInput = (date: string|null) => {
    if (! date) return '';

    const instance = new Date(date);
    return `${ instance.getUTCHours().toString().padStart(2, '0') }:${ instance.getUTCMinutes().toString().padStart(2, '0') }`;
}

/**
 * #MARK: Number Formatting
 */

/**
 * Formats a number to a localized number representation.
 * @param number The number to format
 * @param digits The amount of digits to display
 */
export const formatNumber = (number: number, digits: number = 2) => {
    return number.toLocaleString(getCurrentLocale(), {
        maximumFractionDigits: digits,
        minimumFractionDigits: digits
    });
}
