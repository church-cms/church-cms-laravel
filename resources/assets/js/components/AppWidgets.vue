<template>
    <div>
        <div v-if="loading" class="text-center py-8 text-gray-500">Loading...</div>
        <div v-else v-html="content"></div>
    </div>
</template>

<script>
export default {
    props: {
        widgetId: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            content: '',
            loading: true
        };
    },
    created() {
        axios.get('/api/widget/' + this.widgetId)
            .then(response => {
                this.content = response.data.content || '';
            })
            .catch(() => {
                this.content = '';
            })
            .finally(() => {
                this.loading = false;
            });
    }
};
</script>
