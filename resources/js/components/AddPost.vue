<template>
    <div>
        <h3 class="text-center">Add Post</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit="addPost" enctype="multipart/form-data">
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
                    </div>
                    <br/>
                    <button type="submit" class="btn btn-primary">Add Post</button>
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
                    document: null,
                },
            }
        },
        methods: {
             onFileChange(e){
                this.form.document = e.target.files[0];
            },
            addPost(e) {
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

                axios.post('/api/post/add', formData, config)
                .then(function (response) {
                    currentObj.$router.push({name: 'home'})
                })
                .catch(function (error) {
                    console.log(error);
                });

            }
        }
    }
</script>