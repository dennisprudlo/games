<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const notificationQueue = ref([]);
const flashedNotifications = computed(() => usePage().props.notifications);
const queueWorking = ref(false);

window.addEventListener('notify', event => {
    notificationQueue.value.push(event.detail);

    shiftNotificationQueue();
});

const notificationAnimation = ref('-translate-y-full');
const notificationToShow = ref(null);

const shiftNotificationQueue = () => {
    if (queueWorking.value || notificationQueue.value.length === 0) return;

    queueWorking.value = true;
    const notification = notificationQueue.value.shift();
    notificationToShow.value = notification;
    notificationAnimation.value = 'translate-y-0';

    //
    // The notification will get an additional 100ms display time for each word in the message
    const additionalLength = notification.message.split(' ').length * 200;
    const displayTime = (notificationQueue.value.length > 0 ? 1500 : 2500) + additionalLength;

    setTimeout(() => {
        notificationAnimation.value = '-translate-y-full';
        setTimeout(() => {
            notificationToShow.value = null
            queueWorking.value = false;
            shiftNotificationQueue();
        }, 250);
    }, displayTime);
}

watch(flashedNotifications, newVal => {
    newVal && newVal.forEach(notification => {
        notificationQueue.value.push(notification);
    });

    shiftNotificationQueue();
}, {
    immediate: true
});
</script>

<template>
    <div class="fixed inset-0 z-50 pointer-events-none">
        <div :class="notificationAnimation" class="p-2 flex flex-col items-center transition-transform">
            <div v-if="notificationToShow" class="max-w-[550px] w-full flex justify-center" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="bg-white/80 backdrop-blur rounded-md overflow-hidden shadow-lg pointer-events-auto" :class="{ 'shadow-success/10': !notificationToShow.error, 'shadow-danger/10': notificationToShow.error }">
                    <div :class="{ 'bg-success/15 text-success': !notificationToShow.error, 'bg-danger/15 text-danger': notificationToShow.error }" class="text-sm px-4 py-2 text-center font-medium leading-tight">
                        <span v-text="notificationToShow.message"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
