<template>
    <div
        class="bg-white w-[50%] border border-gray-200 rounded-lg dark:bg-neutral-700 dark:border-neutral-700"
        data-hs-input-number=""
    >
        <div class="w-full flex justify-between items-center gap-x-1">
            <div class="grow py-4 px-6">
                <input
                    class="w-full p-0 text-lg bg-transparent border-0 text-gray-800 focus:ring-0 dark:text-white"
                    v-model="count"
                    type="text"
                    value="1"
                />
            </div>
            <div
                class="flex items-center -gap-y-px divide-x divide-gray-200 border-s border-gray-200 dark:divide-neutral-700 dark:border-neutral-700"
            >
                <button
                    type="button"
                    class="size-10 inline-flex justify-center px-2 items-center gap-x-2 text-sm font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800"
                    @click="decCount"
                >
                    <svg
                        class="flex-shrink-0 size-3.5"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M5 12h14"></path>
                    </svg>
                </button>
                <button
                    type="button"
                    class="size-12 inline-flex justify-center px-2 items-center gap-x-2 text-lg font-medium last:rounded-e-lg bg-white text-gray-800 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800"
                    @click="incCount"
                >
                    <svg
                        class="flex-shrink-0 size-3.5"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            count: 1,
        };
    },
    methods: {
        incCount() {
            this.count++;
        },
        decCount() {
            if (this.count > 1) {
                this.count--;
            }
        },
    },
};
</script>
