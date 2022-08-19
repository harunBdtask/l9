<template>
    <div>
        <div class="row">
            <div class="col-sm-10">
                <h3>Posts List</h3>
            </div>
            <div class="col-sm-2">
                <router-link to="/store" class="nav-item nav-link btn btn-secondary text-white">Add Post</router-link>
            </div>
        </div>
        <br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Document</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="post in posts" :key="post.id">
                <td>{{ post.id }}</td>
                <td>{{ post.title }}</td>
                <td>{{ post.description }}</td>
                <td style="max-width: 100px;">
                    <img 
                        v-if="post.document_path"
                        width="80px"
                        alt="Image"
                        style="border-radius: 5%"
                        :src="post.document_path"
                    />
                </td>
                <td>{{ post.created_at }}</td>
                <td>{{ post.updated_at }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'store', params: { id: post.id }}" class="btn btn-primary">Edit</router-link>
                        <!-- <router-link :to="{name: 'edit', params: { id: post.id }}" class="btn btn-primary">Edit</router-link> -->
                        <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                posts: []
            }
        },
        created() {
            this.axios
                .get('/api/posts')
                .then(response => {
                    this.posts = response.data;
                });
        },
        methods: {
            deletePost(id) {
                this.axios
                    .delete(`/api/post/delete/${id}`)
                    .then(response => {
                        let i = this.posts.map(item => item.id).indexOf(id); // find index of your object
                        this.posts.splice(i, 1)
                    });
            }
        }
    }
</script>