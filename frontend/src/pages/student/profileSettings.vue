<script setup>
    import { ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import axios from 'axios';


    const router = useRouter();
    const route = useRoute();
    const profile = ref(null);
    const loading = ref(true);

    const loadProfile = async () => {
        try {
            const response = await axios.get(`http://127.0.0.1:8000/api/profile/${route.params.id}`);
            profile.value = response.data.profile || response.data;
        } catch (error) {
            console.error("Error while fetching profile:", error);
        } finally {
            loading.value = false;
        }
    };

    const saveChanges = async () => {
        await axios.put(`http://127.0.0.1:8000/api/profile/${route.params.id}`, profile.value);
        router.push({ name: 'profile', params: { id: route.params.id } });
    };

    const cancel = () => {
        router.push({ name: 'profile', params: { id: route.params.id } });
    };

  onMounted(() => {
    loadProfile();
  })
</script>

<template>
    <div v-if="profile">
        <h1 class="ps-3">Editing Profile</h1> 
        <div class="p-5" style="margin-bottom: 20px; border: 1px solid #eee; padding: 20px;">
            <div>
                <label class="form-label">First Name</label>
                <input v-model="profile.first_name" class="me-2 mb-3 form-control" />
            </div>
            <div>
                <label class="form-label">Last Name</label>
                <input v-model="profile.last_name" class="me-2 mb-3 form-control" />
            </div>
            <div>
                <label class="form-label">Preferred Name</label>
                <input v-model="profile.preferred_name" class="me-2 mb-3 form-control" />
            </div>
            <div>
                <label class="form-label">Degree Title</label>
                <input v-model="profile.degree_title" class="me-2 mb-3 form-control" />
            </div>
            <div>
                <label class="form-label">Personal Intro</label>
                <textarea v-model="profile.personal_intro" class="form-control" rows="3"></textarea>
            </div>
            <div>
                <label class="form-label">Upcoming Actions</label>
                <textarea v-model="profile.upcoming_actions" class="form-control" rows="3"></textarea>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary me-2 ps-3" @click="saveChanges">Save changes</button>
                <button class="btn btn-secondary ps-3" @click="cancel">Cancel</button>
            </div>
        </div>
    </div>
    <div v-else>Loading...</div>
</template>