<template>
    <div>
        <h3 class="text-center">Add Post</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addPost" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="post.title">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="post.description">
                    </div>
                    <div class="form-group">
                        <label>Document</label>
                         <input type="file" name="document" class="form-control" id="inputFileUpload"
                                v-on:change="onFileChange">
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
                post: {}
            }
        },
        methods: {
            onFileChange(e) {
                this.post.file = e.target.files[0];
            },
            addPost() {
                let currentObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                }

                let formData = new FormData();
                formData.append('file', this.post.file);

                this.axios
                    .post('http://localhost:8000/api/post/add', formData, config)
                    .then(response => (
                        this.$router.push({name: 'home'})
                    ))
                    .catch(error => console.log(error))
                    .finally(() => this.loading = false)
            }
        }
    }
</script>