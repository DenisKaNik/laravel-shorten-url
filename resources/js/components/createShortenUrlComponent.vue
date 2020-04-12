<template>
    <div class="subscribe">
        <h4>Paste the URL to be shortened!</h4>
        <form @submit.prevent="submit">
            <div class="subscribe-form">
                <input type="text" name="textUrl" placeholder="Enter the link here in the format: http(s)://domain-name.com" v-model="fields.textUrl" />
                <button type="submit">Create</button>
            </div>
            <div class="mt-2">
                <div class="loading" v-if="loading">Loading</div>
                <div class="error-message" v-if="errors && errors.textUrl">{{ errors.textUrl[0] }}</div>
                <div class="sent-message" v-if="result && result.short_url">{{ result.short_url }}</div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fields: {},
                errors: {},
                result: {},
                loading: false,
            }
        },
        methods: {
            submit() {
                this.loading = true;
                this.errors = {};
                this.result = {};
                axios.post('/api/create-shorten-url', this.fields)
                    .then(response => {
                        if (response.status === 200) {
                            this.result = response.data || {};
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    })
                    .finally(() => (this.loading = false));
            },
        },
    }
</script>
