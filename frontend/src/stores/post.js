import axios from 'axios';
const url = 'https://127.0.0.1:8000/api/posts';

export default{
    getPosts(){
        return axios.get(url);
    },
    createPost(){
        return axios.get(url);
    },
    updatePost(){
        return axios.get(url);
    },
    deletePost(){
        return axios.get(url);
    }
};
