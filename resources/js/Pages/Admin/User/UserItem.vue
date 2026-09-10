<template>
    <tr
        class="cursor-pointer focus-within:bg-green-50 focus:outline-none"
        role="link"
        tabindex="0"
        @click="openUser"
        @keydown.enter="openUser"
        @keydown.space.prevent="openUser"
    >
        <th
            scope="row"
            class="whitespace-nowrap font-semibold text-slate-900"
        >
            <a :href="`/admin/resource/users/${user.id}`">
                {{ user.id }}
            </a>
        </th>
        <td class="px-4 py-3">{{ user.name }}</td>
        <td class="px-4 py-3">{{ user.email }}</td>
        <td>
            <span class="inline-flex rounded-full px-2.5 py-1 text-sm font-semibold" :class="user.isAdmin ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600'">
                {{ user.isAdmin ? "Да" : "Нет" }}
            </span>
        </td>

        <td
            class="flex items-center justify-end gap-2"
            @click.stop
            @keydown.stop
        >
            <button
                :id="`${user.id}-button`"
                :data-dropdown-toggle="`${user.id}`"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-600"
                type="button"
            >
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                    />
                </svg>
            </button>
            <div
                :id="`${user.id}`"
                class="z-10 hidden w-44 divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white shadow-xl"
            >
                <div class="py-1">
                    <button
                        @click="deleteUser(user.id)"
                        class="flex w-full px-4 py-2.5 text-sm text-red-700 hover:bg-red-50"
                    >
                        Удалить
                    </button>
                </div>
            </div>
            <button @click="approveUser(user.id)" v-if="!user.approved" class="inline-flex min-h-10 items-center rounded-lg bg-green-700 px-3 py-2 text-sm font-semibold text-white transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">Подтвердить</button>
        </td>
    </tr>
</template>

<script setup>
const props = defineProps({
    user: {
        type: Object,
        default: null,
    }});

function openUser() {
    window.location.href = `/admin/resource/users/${props.user.id}`;
}

function deleteUser(id){
    axios.delete(`/api/v1/admin/users/${id}`)
        .then(res => console.log(res.data))
        .catch(err => console.log(err))
}

function approveUser(id){
    axios.put(`/api/v1/admin/users/${id}/approve`)
        .then(res => {
            console.log(res.data);
            window.location.reload();
        })
        .catch(err => console.log(err))
}

</script>
