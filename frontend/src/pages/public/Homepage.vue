<template>
    <div class="page">
        <nav class="nav-wrapper">
            <div>
                <!-- <router-link to="/"> -->
                    <div class="navLogo"></div>
                <!-- </router-link> -->
            </div>

            <div class="nav-bar">
                <div class="nav-item">
                    <button class="btn-signin-custom" @click="signIn">Sign in</button>
                </div>
            </div>
        </nav>

        <section class="container-lg py-5 mt-5">

            <div class="row align-items-center g-5">
                <div class="col-12 col-md-6">
                    <span class="hero-tag mb-4 d-inline-block">A PORTFOLIO PERSONALISED TO TRACK YOUR PROGRESS</span>
                    <h1 class="hero-title mb-4">
                        The&nbsp;&nbsp;&nbsp;smarter&nbsp;&nbsp;&nbsp;way<br>to&nbsp;&nbsp;&nbsp;navigate&nbsp;&nbsp;&nbsp;your<br><span
                            class="hero-highlight">ENGINEERING </span>journey</h1>
                    <p class="hero-desc mb-4">To meet Engineers Australia accreditation requirements, engineering
                        students must be given opportunities throughout
                        their program to reflect on their development of the Engineers Australia Stage 1 competencies.
                        With <span>EngiFolio</span> students can
                        track their professional development, record competency progress, and build a structured
                        portfolio aligned with industry
                        accreditation standards.
                    </p>

                    <div class="d-flex flex-wrap gap-5 ms-3 mt-5">
                        <a href="#key-features" class="btn btn-dark btn-hero px-5 py-2">Explore features</a>
                        <button class="btn btn-outline-dark btn-hero px-5 py-2">See demo</button>
                    </div>
                </div>

                <div class="col-12 col-md-6 d-flex justify-content-center">
                    <div class="hero-img">
                        <img src="@/assets/engi-dude-home.png">
                    </div>
                </div>
            </div>
        </section>

        <Divider />

        <section id="key-features" class="container-lg py-5">
            <h2 class="f-title text-center mb-5">Key&nbsp;&nbsp;&nbsp;Features</h2>
            <div class="row row-cols-1 row-cols-md-3 g-5">

                <div class="col" v-for="f in features" :key="f.name">
                    <div class="f-card text-center d-flex flex-column align-items-center gap-2 mt-5">
                        <div class="f-icon"><img :src="f.icon" :alt="f.name"></div>
                        <h3 class="f-name">{{ f.name }}</h3>
                        <p class="f-desc">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Divider from '@/components/Divider.vue';
export default {
    name: 'Homepage',
    components: { Divider },
    data() {
        return {
            features: [
                {
                    icon: new URL('@/assets/feature1.png', import.meta.url).href,
                    name: 'Create a Profile',
                    desc: 'Add your personal information, upcoming actions for personal development and links to your resume and other files.'
                },
                {
                    icon: new URL('@/assets/feature2.png', import.meta.url).href,
                    name: 'Set your SMART Goals',
                    desc: 'Create action steps, write your learnings and stay on track.'
                },
                {
                    icon: new URL('@/assets/feature3.png', import.meta.url).href,
                    name: 'Log Upcoming Events & Industry Contacts',
                    desc: 'Keep track of each event you attend, prepare questions to ask, record contact information, and note your progress.'
                },
                {
                    icon: new URL('@/assets/feature4.png', import.meta.url).href,
                    name: 'Record Evidence of Stage 1 Competencies',
                    desc: 'See all the competencies and record your evidence for each one by uploading a file or using a link.'
                },
                {
                    icon: new URL('@/assets/feature5.png', import.meta.url).href,
                    name: 'Access your Career Development Learning pages',
                    desc: 'View a list of your CDLs and complete them accordingly.'
                },
                {
                    icon: new URL('@/assets/feature6.png', import.meta.url).href,
                    name: 'View a Summary & Export Data',
                    desc: 'See your stats for each year and choose what you would like to export with file type.'
                },
            ],
        };
    },
    methods: {
        signIn() {
            this.$router.push('/login');
        }
    }
};
</script>

<style scoped>
.page {
    font-family: 'Martel', sans-serif;
    color: #1a1a2e;
    background: #fff;
    min-height: 100vh;
    overflow-x: hidden;
}

.nav-wrapper {
    width: 100vw;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #140f50;
    padding: 0;
}

.navLogo {
    width: 6.25rem;
    height: 3.75rem;
    margin: 0 1.5rem;
    background: linear-gradient(45deg, #d9bebe, #6b6be4);
    -webkit-mask: url('@/assets/engiFolio.png') no-repeat center;
    -webkit-mask-size: contain;
    mask: url('@/assets/engiFolio.png') no-repeat center;
    mask-size: contain;
}

.nav-bar {
    display: flex;
    list-style-type: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0 1.5rem;
}

.btn-signin-custom {
    /* background-color: #ffffff; */
    background: transparent;
    color: #a7a7a7;
    border: none;
    border-radius: 30px;
    padding: 6px 18px;
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1.2rem;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.3s ease;
}

.btn-signin-custom:hover {
    /* background-color: #e0e0e0; */
    color: #ffffff;
}

.btn {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 30px;
    font-size: 1.25rem;
    background-color: none;
}

.hero-tag {
    border: 1.5px solid #22222275;
    border-radius: 20px;
    padding: 6px 25px;
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    letter-spacing: 0.05rem;
    color: #22222275;
}

.hero-highlight {
    color: #142a93;
    font-family: 'Kode Mono', monospace;
    font-size: 2.8rem;
}

.hero-title,
.f-title {
    font-size: 3.75rem;
    font-family: 'Martel', sans-serif;
    color: #2b2b2b;
    line-height: 4rem;
}

.hero-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.2rem;
    color: #2b2b2b;
    text-align: justify;
    width: 90%;
}

.f-icon>img {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

.f-name {
    font-family: 'Kode Mono', monospace;
    font-size: 2rem;
}

.f-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.2rem;
    color: #555555;
}
</style>