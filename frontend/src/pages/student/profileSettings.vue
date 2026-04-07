<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios';

    const route = useRoute();
    const router = useRouter();

    const editProfile = async () => {
      router.push('/profile/username');
    };

    const cancel = async () => {
      router.push('/profile/username');
    };

    onMounted(() => {

    })
</script>

<template>
    <div>
        <h1 class="ps-3">Editing Profile</h1> 
        <div class="p-5" style="margin-bottom: 20px; border: 1px solid #eee; padding: 20px;">
            <div>
                <label class="form-label">Introduction</label>
                <input class="me-2 mb-3 form-control" placeholder="Field"/>
            </div>
            <div>
                <label class="form-label">Elevator Pitch</label>
                <input class="me-2 mb-3 form-control" placeholder="Field"/>
            </div>
            <div>
                <label class="form-label">Networking Questions</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="BIO"></textarea>
            </div>

            <div>
                <button class="btn btn-primary" type="submit"  @click="editProfile">Save changes</button>
                <button class="btn btn-danger" type="submit"  @click="cancel">Cancel</button>
            </div>
        </div>
    </div>
</template>