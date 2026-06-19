<template>
    <body class="main-section" v-if="profile">
        <section class="cert-sec mb-5">
            <h2 class="sec-title mb-2">Achievement Certifications</h2>
            <div v-if="profile.achievement_certs && profile.achievement_certs.length">
                <div class="carousel">
                    <!--prev card button-->
                    <button class="nav-btn nav-prev" @click="prevAch" :disabled="achIndex === 0">
                        <img src="@/assets/back.png" class="nav-icon" alt="previous"/>
                    </button>

                    <!-- cards lineup-->
                    <div class="lineup">
                        <div class="cert-card" v-for="(cert, i) in profile.achievement_certs"
                        :key="cert.achievement_cert_id" :class="cardClass(i, achIndex)" @click="achIndex= i">

                            <div class="cert-card-inner">
                                <div class="cert-header">
                                    <img src="@/assets/cert.png" class="cert-icon" alt="certificate"/>
                                    <p class="cert-label">Certificate of Achievement</p>
                                </div>
                                <h3 class="cert-title">{{ cert.title }}</h3>
                                <p class="cert-body">{{ cert.body }}</p>
                                <div class="cert-footer">
                                    <span class="cert-date">Issued {{ formatDate(cert.issued_date) }}</span>
                                    <span class="cert-date cert-expiry" v-if="cert.expiry_date">Expires {{ formatDate(cert.expiry_date) }}</span>
                                    <a v-if="cert.file_path" :href="cert.file_path" target="_blank" class="cert-link">View file</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--next card button-->
                    <button class="nav-btn nav-next" @click="nextAch" :disabled="achIndex >= profile.achievement_certs.length - 1">
                       <img src="@/assets/next.png" class="nav-icon" alt="next"/>
                    </button>
                </div>
            </div>
            <p v-else class="empty-txt">No achievement certifications yet, click edit to add new.</p>
        </section>

        <section class="cert-sec mb-5">
            <h2 class="sec-title mb-2">Attainment Certifications</h2>
            <div v-if="profile.attainment_certs && profile.attainment_certs.length">
                <div class="carousel">
                    <!--prev card button-->
                    <button class="nav-btn nav-prev" @click="prevAtt" :disabled="attIndex === 0">
                        <img src="@/assets/back.png" class="nav-icon" alt="previous"/>
                    </button>

                    <!-- cards lineup-->
                    <div class="lineup">
                        <div class="cert-card" v-for="(cert, i) in profile.attainment_certs"
                        :key="cert.attainment_cert_id" :class="cardClass(i, attIndex)" @click="attIndex = i">

                            <div class="cert-card-inner">
                                <div class="cert-header">
                                    <img src="@/assets/cert.png" class="cert-icon" alt="certificate"/>
                                    <p class="cert-label">Certificate of Attainment</p>
                                </div>
                                <h3 class="cert-title">{{ cert.title }}</h3>
                                <p class="cert-body">{{ cert.body }}</p>
                                <div class="cert-footer">
                                    <span class="cert-date">Issued {{ formatDate(cert.issued_date) }}</span>
                                    <span class="cert-date cert-expiry" v-if="cert.expiry_date">Expires {{ formatDate(cert.expiry_date) }}</span>
                                    <a v-if="cert.file_path" :href="cert.file_path" target="_blank" class="cert-link">View file</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--next card button-->
                    <button class="nav-btn nav-next" @click="nextAtt" :disabled="attIndex >= profile.attainment_certs.length - 1">
                        <img src="@/assets/next.png" class="nav-icon" alt="next"/>
                    </button>
                </div>
            </div>
            <p v-else class="empty-txt">No attainment certifications yet, click edit to add new.</p>
        </section>

        <div class="d-flex gap-3 justify-content-center mt-4 mb-5">
            <router-link :to="`/certification-settings/${$route.params.id}`" class="btn btn-ql">Edit Certifications</router-link>
        </div>
    </body>


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
import api from "@/services/api";

// Used for accessing the route parameters for backend call
const route = useRoute();

// Store profile data rendered by vue and loading status
const profile = ref(null);
const loading = ref(true);

// Keep track of the index for the current certificates
const achIndex = ref(0)
const attIndex = ref(0)

// Reduce achievement index by one if possible
const prevAch = () => { 
    if (achIndex.value>0) {
        achIndex.value--
    }
}

// Increase achievement index by one if possible
const nextAch = () =>{ 
    if (achIndex.value < profile.value?.achievement_certs?.length - 1) {
        achIndex.value++ 
    }
}

// Reduce attainment index by one if possible
const prevAtt = () => { 
    if (attIndex.value>0) {
        attIndex.value-- 
    }
}

// Increase attainment index by one if possible
const nextAtt = () => { 
    if (attIndex.value < profile.value?.attainment_certs?.length - 1) {
        attIndex.value++ 
    }
}

// Return position class relative to the active index
const cardClass = (i, activeIndex) => {
    const diff = i-activeIndex
    if (diff===0) {
        return 'card-center'
    }
    if (diff===-1) {
        return 'card-prev'
    }
    if (diff===1) {
        return 'card-next'
    }
    return 'card-hidden'
}

// Format the certificate dates into a better visual representation
const formatDate = (rawDate) => {
    if (rawDate) {
        // Takes a raw text string and passes it to the date constructor 
        const d = new Date(rawDate)
        // Formats the date data into AU order
        // Day and year are represented by a number and the month is a short abbreviation (E.g., 1 Jan 2026) 
        return d.toLocaleDateString('en-AU', { day: 'numeric', month: 'short', year: 'numeric' })
    } else {
        return ''
    }
}

// Fetches profile data from backend asynchronously without interrupting main code
const loadProfile = async () => {
    try {
        // Pause execution here while waiting for a response without blocking the rest of the browser
        const response = await api.get(`/profile/${route.params.id}`);
        profile.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error("Error while fetching profile:", error);
    }
};

// Load profile data immediately
loadProfile();

</script>

<style scoped>
.main-section {
    max-width: 85%;
    margin: 0 auto;
    padding: 3rem 1.5rem;
}

.sec-title {
    font-family: 'Martel', serif;
    font-size: 2rem;
    color: #303030c5;
    text-align: center;
}

.carousel {
    display: flex;
    align-items: center;
    position: relative;
}

.lineup {
    flex: 1;
    position: relative;
    height: 20rem;
    overflow: hidden;
}

.cert-card {
    position: absolute;
    width: 25rem;
    height: 15rem;
    top: 50%;
    left: 50%;
    transition: transform 0.5s ease, opacity 0.5s ease, scale 0.5s ease;
    cursor: pointer;
    z-index: 0;
}

.card-center {
    transform: translate(-50%, -50%) translateX(0) scale(1);
    opacity: 1;
    z-index: 3;
}

.card-prev {
    transform: translate(-50%, -50%) translateX(-95%) scale(0.7);
    opacity: 0.5;
    z-index: 2;
}

.card-next {
    transform: translate(-50%, -50%) translateX(95%) scale(0.7);
    opacity: 0.5;
    z-index: 2;
}

.card-hidden {
    transform: translate(-50%, -50%) translateX(-300%) scale(0.5);
    opacity: 0;
    pointer-events: none;
    z-index: 1;
}

.cert-card-inner {
    background: #f7f7f7;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 1.5rem 1.6rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    transition: box-shadow 0.2s ease;
}

.card-center .cert-card-inner {
    box-shadow: 0 0.5rem 1.5rem #e5e5e5;
    border-color: #bbbbbb;
}

.cert-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}
 
.cert-icon {
    width: 1.4rem;
    height: 1.4rem;
    object-fit: contain;
    opacity: 0.6;
    flex-shrink: 0;
}
.cert-label {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.9rem;
    color: #aaaaaa;
    margin: 0;
}

.cert-title {
    font-family: 'Martel', serif;
    font-size: 1.1rem;
    color: #333333;
    margin: 0;
}

.cert-body {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #666666;
    line-height: 1.5;
    flex: 1;
    margin: 0;
    overflow: hidden;
}

.cert-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: auto;
}

.cert-date {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    color: #aaaaaa;
}

.cert-expiry {
    color: #c08080;
}

.cert-link {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.78rem;
    color: #5a9fb0;
    text-decoration: none;
    white-space: nowrap;
}

.cert-link:hover {
    text-decoration: underline;
}

.nav-btn {
    background: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.2s ease;
    z-index: 2;
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.nav-icon {
    width: 2.5rem;
    height: 2.5rem;
    object-fit: contain;
}

.empty-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    text-align: center;
    color: #aaaaaa;
}

.btn-filter {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    background: #e6e6e6;
    padding: 0.5rem 2rem;
}

.btn-filter:hover {
    background: #666666;
    color: #ffffff;
}

.btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    border-radius: 1.5rem;
    padding: 0.5rem 2rem;
    text-decoration: none;
}

.btn-ql:hover {
    color: #ffffff;
    background: #333333;
}

.loading {
    min-height: calc(100vh);
}

@media (max-width: 767px) {
    .main-section {
        max-width: 100%;
        padding: 2rem 1rem;
    }

    .sec-title {
        font-size: 1.4rem;
    }

    .cert-card {
        width: 100%;
        height: 80%;
    }

    .card-prev, .card-next {
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, -50%) scale(0.8);
    }

    .card-center {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    .card-center .cert-card-inner {
        box-shadow: none;
    }

    .carousel {
        flex-wrap: wrap;
        justify-content: center;
    }

    .lineup {
        order: 1;
        width: 100%;
        flex: none;
        height: 18rem;
    }

    .nav-btn {
        order: 2;
        padding: 0.3rem;
        margin: 0.5rem 1rem 0;
    }

    .nav-icon {
        width: 2.5rem;
        height: 2.5rem;
    }

    .cert-label {
        font-size: 0.9rem;
    }

    .cert-title {
        font-size: 1rem;
    }

    .cert-body {
        font-size: 0.8rem;
        overflow-y: auto;
    }
}
</style>