import { Ref, ref } from 'vue';
import { InertiaForm, useForm } from '@inertiajs/vue3';

type ModalOptions<TForm extends object> = {
    submit?: (modal: ModalHandler<TForm>, close: () => void) => void,
    open?: (modal: ModalHandler<TForm>) => void,
    close?: (modal: ModalHandler<TForm>) => void,
    form?: TForm|(() => TForm),
    resetOnOpen?: boolean,
    resetOnClose?: boolean,
};

export const useModal = <TForm extends object>(options?: ModalOptions<TForm>) => {
    return new ModalHandler(options);
};

export class ModalHandler<TForm extends object> {

    /**
     * Determines whether the modal is open or not
     */
    public modalIsOpen: Ref<boolean> = ref(false);

    /**
     * The form to be used in the modal
     */
    public form: InertiaForm<TForm>;

    /**
     * The props to be passed to the modal
     */
    public props: {[key: string]: any};

    /**
     * Whether to reset the form when the modal is opened
     */
    public resetOnOpen: boolean;

    /**
     * Whether to reset the form when the modal is closed
     */
    public resetOnClose: boolean;

    /**
     * The handler function to be called when the form is submitted
     */
    private submitHandler: (modal: ModalHandler<TForm>, close: () => void) => void;

    /**
     * The handler function to be called just before the modal is opened
     */
    private openHandler: (modal: ModalHandler<TForm>) => void;

    /**
     * The handler function to be called just before the modal is closed
     */
    private closeHandler: (modal: ModalHandler<TForm>) => void;

    /**
     * @param options The options to be used in the modal
     */
    constructor(options?: ModalOptions<TForm>) {
        this.form = options?.form !== undefined ? useForm(options.form) : useForm({}) as InertiaForm<TForm>;
        this.props = {};
        this.resetOnOpen = options?.resetOnOpen === undefined ? true : options.resetOnOpen;
        this.resetOnClose = options?.resetOnClose === undefined ? true : options.resetOnClose;
        this.submitHandler = options?.submit || (() => {});
        this.openHandler = options?.open || (() => {});
        this.closeHandler = options?.close || (() => {});
    }

    /**
     * Opens the modal
     */
    public open(handle: (handler: ModalHandler<TForm>) => void = () => {}): void {
        handle(this);

        if (this.resetOnOpen) {
            this.form.reset();
            this.form.clearErrors();
        }

        this.openHandler(this);

        this.modalIsOpen.value = true;
    }

    /**
     * Closes the modal
     */
    public close(): void {
        this.closeHandler(this);

        this.modalIsOpen.value = false;

        if (this.resetOnClose) {
            this.form.reset();
            this.form.clearErrors();
        }
    }

    /**
     * Determines whether the modal is open or not
     */
    public isOpen() {
        return this.modalIsOpen.value;
    }

    /**
     * Submits the form
     */
    public submit() {
        this.submitHandler(this, () => this.close());
    }

    /**
     * Determines whether a prop exists
     */
    public has(key: string): boolean {
        return this.props.hasOwnProperty(key);
    }

    /**
     * Set a prop to be passed to the modal
     */
    public set<Type>(key: string, value: Type): void {
        this.props[key] = value;
    }

    /**
     * Get a prop from the modal
     */
    public get<Type>(key: string): Type {
        return this.props[key];
    }

    /**
     * Force gets a value as an any type.
     */
    public fget(key: string): any {
        return this.props[key];
    }

    /**
     * Resets the component state
     */
    public resetState(): void {
        this.props = {};
        this.form.reset();
    }
}
