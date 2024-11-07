import { route as routeFn } from 'ziggy';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

export {};

declare global {
    const route: typeof routeFn;

    interface Window {
        Lang: {
            get: (key: string, params?: object) => string;
            choice: (key: string, count: number, params?: object) => string;
        };
        // Pusher: Pusher;
        // Echo: Echo;
    }
}
