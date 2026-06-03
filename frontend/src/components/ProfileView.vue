<template>
    <div class="profile-wrap" v-if="profile">
        <!--header row: left (name+avatar), right (academic info card)-->
        <div class="row align-items-start g-5 mb-5">
            <!--preferred name above av -->
            <div class="col-12 col-md-auto text-center av-col">
                <h1 class="sec-title mb-3">{{ profile.preferred_name || profile.user.first_name }}</h1>
                <img :src="profile.profile_image_url || defaultAvatar" @error="(e) => e.target.src = defaultAvatar"
                    alt="Profile Picture" class="profile-pic" />
            </div>

            <!--academic card-->
            <div class="col-12 col-md ps-md-5">
                <h2 class="sec-title mb-3">Academic Information</h2>
                <div class="academic-card">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">First name</span>
                                <span class="info-value">{{ profile.user.first_name }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">Last name</span>
                                <span class="info-value">{{ profile.user.last_name }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">Preferred name</span>
                                <span class="info-value">{{ profile.preferred_name || '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- bottom row: degree, specialisation -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">Year Started</span>
                                <span class="info-value">{{ profile.year_started }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">Degree undertaking</span>
                                <span class="info-value">{{ profile.degree_title }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info">
                                <span class="info-label">Specialisation chosen</span>
                                <span class="info-value">{{ profile.specialisation }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--personal intro-->
        <section class="mb-5">
            <h2 class="sec-title text-center mb-4">Personal Introduction</h2>
            <div class="intro-card">
                <img src="@/assets/quote-open.png" alt="quote-open" class="quote-img quote-open" />
                <p class="intro-txt">{{ profile.personal_intro }}</p>
                <img src="@/assets/quote-close.png" alt="quote-close" class="quote-img quote-close" />
            </div>
        </section>

        <!--links-->
        <section class="mb-5">
            <h2 class="sec-title mb-4">Professional Links</h2>
            <div class="d-flex flex-column gap-3">
                <div class="link-row" v-for="link in profile.links" :key="link.link_id">
                    <span class="link-label">{{ link.link_label }}</span>
                    <div class="link">
                        <div class="connect-line"></div>
                        <div class="connect-dot"></div>
                    </div>
                    <a :href="link.link_url" class="link-url">{{ link.link_url }}</a>
                </div>
            </div>
        </section>

        <!--buttons-->
        <div class="d-flex gap-3 justify-content-center">
            <router-link :to="{ name: 'export', params: { id: route.params.id } }" class="btn btn-filter">Export Data</router-link>
            <router-link :to="{ name: 'profile-settings', params: { id: route.params.id } }" class="btn btn-ql">Edit Profile</router-link>
        </div>
    </div>

    <div v-else-if="loading" class="text-center py-5 loading">
        <p>Loading profile...</p>
    </div>

    <div v-else class="container py-5">
        <div class="alert alert-warning" role="alert">Profile not found.</div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router'
import defaultAvatar from '@/assets/placeholder-av.webp';
import api from "@/services/api";

// Route used for route parameters to get id
const route = useRoute();

// Store profile data rendered by vue and loading status
const profile = ref(null);
const loading = ref(true);

// Calls the backend api route to fetch the profile data
const loadProfile = async () => {
    try {
        const response = await api.get(`/profile/${route.params.id}`);
        profile.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error("Error while fetching profile:", error);
    }
};

// Call load profile immediately
loadProfile();

</script>

<style scoped>
.profile-wrap {
    max-width: 55%;
    margin: 0 auto;
    padding: 3rem 1.5rem;
}

.av-col {
    width: 14rem;
}

.profile-pic {
    width: 10.5rem;
    height: 10.5rem;
    border-radius: 50%;
    object-fit: cover;
    border: 2.5px solid #dddddd;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.sec-title {
    font-family: 'Martel', serif;
    font-size: 2.0rem;
    color: #303030c5;
    text-align: center;
}

.academic-card {
    background: #f7f7f7;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 1.5rem 1.75rem;
}

.info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.info-label {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.9rem;
    color: #888888;
}

.info-value {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.1rem;
    color: #4f4f4f;
}

.intro-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 2.5rem 3.5rem;
    text-align: center;
}

.intro-txt {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #888888;
    line-height: 1.8;
    margin: 0.3rem;
}

.quote-img {
    position: absolute;
    width: 2rem;
    height: auto;
    opacity: 65%;
}

.quote-open {
    top: 1.1rem;
    left: 1.4rem;
}

.quote-close {
    bottom: 1.1rem;
    right: 1.4rem;
}

.link-row {
    display: flex;
    align-items: center;
}

.link-label {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #707070;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 2rem;
    padding: 0.45rem 1.4rem;
    min-width: 12rem;
    text-align: center;
}

.link {
    display: flex;
    align-items: center;
    flex: 0.6;
}

.connect-line {
    flex: 1;
    height: 1px;
    background: #cccccc;
}

.connect-dot {
    width: 0.45rem;
    height: 0.45rem;
    border-radius: 50%;
    background: #bbbbbb;
    flex-shrink: 0;
}

.link-url {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #444444;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 0.45rem 1.4rem;
    text-decoration: none;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-filter {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    background: #e6e6e6;
    padding: 0.5rem 2rem;
}

.btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    border-radius: 1.5rem;
    padding: 0.5rem 2rem;
}

.btn-ql:hover {
    color: #ffffff;
    background: #333333;
}

.btn-filter:hover {
    background: #666666;
    color: #ffffff;
}

.loading {
    min-height: calc(100vh);
}

@media (max-width: 768px) {
    .profile-wrap {
      max-width: 100%;
      padding: 2rem 1rem;
    }

    .av-col {
        width: 100%;
    }
  
    .intro-card {
      padding: 2rem 1.5rem;
    }
  
    .link-label {
      min-width: 7rem;
      font-size: 0.85rem;
    }
  
    .link-url {
      font-size: 0.85rem;
    }
  }
</style>