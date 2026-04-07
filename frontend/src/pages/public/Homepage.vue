<template>
    <div class="page">
        <nav class="navbar px-4 px-md-5 py-3 d-flex justify-content-between align-items-center">

            <div class="logo">
                <span class="logo-engi">ENGI</span>
                <span class="logo-folio">FOLIO</span>
            </div>

            <button class="btn btn-dark btn-signin px-4">Sign in</button>
        </nav>

        <section class="container-lg py-5">

            <div class="row align-items-center g-5">
                <div class="col-12 col-md-6">
                    <span class="hero-tag mb-4 d-inline-block">A PORTFOLIO PERSONALISED TO TRACK YOUR PROGRESS</span>
                    <h1 class="hero-title mb-4">The smarter way<br>to navigate your<br><span class="hero-higlight">ENGINEERING </span>journey</h1>
                    <p class="hero-desc mb-4">To meet Engineers Australia accreditation requirements,engineering students must be given opportunities throughout
                        their program to reflect on their development of the Engineers Australia Stage 1 competencies. With <span>EngiFolio</span> students can
                        track their professional development, record competency progress, and build a structured portfolio aligned with industry
                        accreditation standards.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                    <button class="btn btn-dark btn-hero px-4 py-2">Explore features</button>
                    <button class="btn btn-outline-dark btn-hero px-4 py-2">See demo</button>
                </div>
                </div>
            </div>

            <div class="col-12 col-md-6 d-flex justify-content-center">
                <div class="hero-img">
                    <img src="@/assets/engi-dude-home.png">
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section class="container-lg py-5">
            <h2 class="f-title text-center mb-5"><span class="f-key">Key</span> Features</h2>
            <div class="row row-cols-1 row-cols-md-3 g-5">

                <div class="col" v-for="f in features" :key="f.title">
                    <div class="f-card text-center d-flex flex-column align-items-center gap-2">
                        <div class="f-icon">{{ f.icon }}</div>
                        <h3 class="f-name">{{ f.name }}</h3>
                        <p class="f-desc">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <footer class="d-flex justify-content-between flex-wrap gap-3 px-4 px-md-5 py-4">
            <span>Copyright EngiFolio 2026</span>

            <div class="logo">
                <span class="logo-engi">ENGI</span>
                <span class="logo-folio">FOLIO</span>
            </div>

            <span><u>Privacy Policy</u></span>
        </footer>

    </div>
</template>

<script>
export default {
    name:'Homepage',
    data() {
        return {
            features: [
                {
                    icon: ':)',
                    title: 'Create a Profile',
                    desc: 'Add your personal information, upcoming actions for personal development and links to your resume and other files.'
                },
                {
                    icon: ':(',
                    title: 'Set your SMART Goals',
                    desc: 'Create action steps, write your learnings and stay on track.'
                },
                {
                    icon: ':/',
                    title: 'Log Upcoming Events & Industry Contacts',
                    desc: 'Keep track of each event you attend, prepare questions to ask, record contact information, and note your progress.'
                },
                {
                    icon: ':0',
                    title: 'Record Evidence of Stage 1 Competencies',
                    desc: 'See all the competencies and record your evidence for each one by uploading a file or using a link.'
                },
                {
                    icon: ':3',
                    title: 'Access your Career Development Learning (CDL) pages',
                    desc: 'View a list of your CDLs and complete them accordingly.'
                },
                {
                    icon: ':P',
                    title: 'View a Summary & Export Data',
                    desc: 'See your stats for each year and choose what you would like to export with file type.'
                },
            ],
        };
    },
};
</script>