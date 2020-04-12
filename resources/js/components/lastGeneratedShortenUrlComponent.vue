<template>
    <section>
        <div class="container">

            <div class="section-title">
                <h2>Last generated URL</h2>
            </div>

            <div class="row">
                <div class="col-md-12 shorten-url-items">
                    <div class="shorten-url-item" v-for="item in items">
                        {{ item.long_url }} - <a :href="item.short_url" target="_blank">{{ item.short_url }}</a>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
    export default {
        data: function () {
            return {
                items: []
            }
        },
        mounted() {
            let app = this;
            axios.get('/api/last-shorten-url')
                .then(function (response) {
                    app.items = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
</script>
