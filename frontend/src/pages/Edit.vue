<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios';

    const route = useRoute();
    const post = ref(null);
    const newPost = ref({ title: '', body: '' }); 
    const router = useRouter();

    const viewPost = async () => {
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${route.params.id}`);
        post.value = response.data;
        newPost.value = { 
                title: response.data.title, 
                body: response.data.body 
            };
    };

    const editPost = async () => {
      await axios.put(`http://127.0.0.1:8000/api/posts/${route.params.id}`, newPost.value);
      router.push('/');
    };

    onMounted(() => {
        viewPost();
    })
</script>

<template>
    <div class="post" v-if="post">
        <h1 class="ps-3">Editing: {{ post.id }}</h1> 
        <h4 class="ps-3">Updated entry</h4>
        <div class="p-5" style="margin-bottom: 20px; border: 1px solid #eee; padding: 20px;">
        <input class="me-2 mb-3 form-control" v-model="newPost.title" placeholder="Title"/>
        <input class="me-2 mb-3 form-control" v-model="newPost.body" placeholder="Body"/>
        <button class="btn btn-primary" type="submit"  @click="editPost">Save changes</button>
    </div>


        <router-link class="ps-3" to="/">Back to Table</router-link>
    </div>

    <div v-else>
        <p>Loading</p>
    </div>
</template>