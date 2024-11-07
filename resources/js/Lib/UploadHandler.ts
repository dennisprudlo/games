import { InertiaForm, useForm } from '@inertiajs/vue3';
import { Ref, ref } from 'vue';

type UploadOptions = {
    endpoint?: string,
    method?: string,
    input?: string,
    mimes?: Array<string>,
};

export const useUpload = (options: UploadOptions) => {
    return new UploadHandler(options);
};

type PendingFile = {
    id: string,
    file: File,
    previewUrl: string,
    form: InertiaForm<any>,
}

export class UploadHandler {
    private endpoint: string;
    private method: string;
    private input: string;
    private mimes: Array<string>;

    private files: Ref<Array<PendingFile>>;

    private queueRunning: boolean = false;

    private listeners: Array<(file: PendingFile) => void> = [];

    public processing: Ref<boolean> = ref(false);

    /**
     * @param options The options to be used for the upload handler
     */
    constructor(options: UploadOptions) {
        this.endpoint = options.endpoint || '';
        this.method = options.method || 'post';
        this.input = options.input || 'file';
        this.mimes = options.mimes || [];
        this.files = ref([]);
    }

    /**
     * Sets the endpoint to be used for the upload
     * @param url The URL to be used
     */
    public setEndpoint(url: string): void {
        this.endpoint = url;
    }

    /**
     * Adds a list of files to the upload queue
     * @param files The files to add
     * @param append Additional data to append to the form
     */
    public addFiles(files: Array<File>, append?: object): void {
        files.forEach(file => {
            if (this.mimes.length > 0 && ! this.mimes.includes(file.type)) {
                return;
            }

            this.files.value.push({
                id: Math.random().toString(36).substring(7),
                file: file,
                previewUrl: URL.createObjectURL(file),
                form: useForm(Object.assign(append || {}, {
                    _method: this.method,
                    [this.input]: file
                })),
            });
        });

        if (! this.queueRunning) {
            this.queueRunning = true;
            this.runUploadQueue();
        }
    }

    /**
     * Gets the list of pending files
     * @returns The list of pending files
     */
    public getPendingFiles(): Array<PendingFile> {
        return this.files.value;
    }

    /**
     * Checks if there are pending files in the queue
     */
    public hasPendingFiles(): boolean {
        return this.files.value.length > 0;
    }

    /**
     * Runs the upload queue
     */
    public runUploadQueue(): void {
        if (this.files.value.length === 0) {
            this.queueRunning = false;
            return;
        }

        const pendingFile = this.files.value[0];
        if (! pendingFile) {
            this.queueRunning = false;
            return;
        }

        this.processing.value = true;
        pendingFile.form.post(this.endpoint, {
            preserveScroll: true,
            onSuccess: () => {
                this.listeners.forEach(listener => listener(pendingFile));
            },
            onError: () => {
                //
            },
            onFinish: () => {
                const removeIndex = this.files.value.findIndex(file => file.id === pendingFile.id);
                if (removeIndex !== -1) {
                    this.files.value.splice(removeIndex, 1);
                }

                this.runUploadQueue();

                this.processing.value = false;
            },
        });
    }

    /**
     * Registers a callback to be called when a file has been uploaded
     * @param callback The callback to be called when a file has been uploaded
     */
    public onUploaded(callback: (file: PendingFile) => void): void {
        this.listeners.push(callback);
    }
}
