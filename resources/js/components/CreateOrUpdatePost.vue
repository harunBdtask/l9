<template>
    <div>
        <h3 class="text-center">Post Form</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit="storeData">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="form.title">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="form.description">
                    </div>
                    <div class="form-group">
                        <label>Document</label>
                        <input type="file" class="form-control" v-on:change="onFileChange" name="document">
                        <img
                            v-if="this.form.document_path"
                            width="30%"
                            alt="Image"
                            style="border-radius: 5%"
                            :src="imagePreview"
                        />
                    </div>
                    <br/>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                form: {
                    title: '',
                    description: '',
                    document_path: '',
                    document: null,
                    actionId: null,
                },
            }
        },
        created() {
            this.form.actionId = this.$route.params.id;
            if (this.form.actionId) {
                axios.get(`/api/post/edit/${this.form.actionId}`)
                    .then((response) => {
                        this.form = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
        computed: {
            imagePreview(){
                return '/' + this.form.document_path;
            },
        },
        methods: {
            onFileChange(e){
                this.form.document = e.target.files[0];
            },
            storeData(e) {
                e.preventDefault();
                let currentObj = this;
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
    
                let formData = new FormData();
                for (const [key, value] of Object.entries(this.form)) {
                    if ((this.form[key]) !== null || '') {
                        formData.append(key, this.form[key]);
                    }
                }

                axios.post('/api/post/save', formData, config)
                .then(function (response) {
                    currentObj.$router.push({name: 'home'})
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
        },
    }
</script>