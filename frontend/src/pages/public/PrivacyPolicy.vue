<template>
    <div class="page">
        <nav class="nav-wrapper">
            <div>
                <button class="navLogo" @click="goHome"></button>
            </div>
        </nav>

        <section class="container-lg py-5 mt-5">
            <div class="row align-items-center">
                <h1 class="hero-title mb-4">Privacy Policy</h1>
                <p class="hero-desc mb-4">
                    <b>EngiFolio</b> (referred to as <b>we</b>, <b>us</b> or <b>our</b>) is committed to providing quality services to you and this policy outlines our ongoing obligations to you in respect of how we manage your Personal Information.
                </p>
                <p class="hero-desc mb-4">
                    We have adopted the Australian Privacy Principles (APPs) contained in the Privacy Act 1988 (Cth) (the Privacy Act). The NPPs govern the way in which we collect, use, disclose, store, secure and dispose of your Personal Information.
                </p>
                <p class="hero-desc mb-4">
                    A copy of the Australian Privacy Principles may be obtained from the website of The Office of the Australian Information Commissioner at <a href="https://www.oaic.gov.au/">https://www.oaic.gov.au/</a>.
                </p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">What is Personal Information and why do we collect it?</h2>
                <p class="hero-desc mb-4">Personal Information is information or an opinion that identifies an individual. Examples of Personal Information we collect includes names, addresses, email addresses, phone and facsimile numbers.</p>
                <p class="hero-desc mb-4">This Personal Information is obtained in many ways including via our website <a href="http://localhost:5173/">http://localhost:5173/</a> and from third parties. We don’t guarantee website links or policy of authorised third parties.</p>
                <p class="hero-desc mb-4">We collect your Personal Information for the primary purpose of providing our services to you, providing information to our clients and marketing. We may also use your Personal Information for secondary purposes closely related to the primary purpose, in circumstances where you would reasonably expect such use or disclosure. You may unsubscribe from our mailing/marketing lists at any time by contacting us in writing.</p>
                <p class="hero-desc mb-4">When we collect Personal Information we will, where appropriate and where possible, explain to you why we are collecting the information and how we plan to use it.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Sensitive Information</h2>
                <p class="hero-desc mb-4">Sensitive information is defined in the Privacy Act to include information or opinion about such things as an individual's racial or ethnic origin, political opinions, membership of a political association, religious or philosophical beliefs, membership of a trade union or other professional body, criminal record or health information.</p>
                <p class="hero-desc mb-4">Sensitive information will be used by us only:</p>
                <ul class="hero-list" mb-4>
                    <li>For the primary purpose for which it was obtained</li>
                    <li>For a secondary purpose that is directly related to the primary purpose</li>
                    <li>With your consent; or where required or authorised by law.</li>
                </ul>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Third Parties</h2>
                <p class="hero-desc mb-4">Where reasonable and practicable to do so, we will collect your Personal Information only from you. However, in some circumstances we may be provided with information by third parties. In such a case we will take reasonable steps to ensure that you are made aware of the information provided to us by the third party.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Disclosure of Personal Information</h2>
                <p class="hero-desc mb-4">Your Personal Information may be disclosed in a number of circumstances including the following:</p>
                <ul class="hero-list" mb-4>
                    <li>Third parties where you consent to the use or disclosure; and</li>
                    <li>Where required or authorised by law.</li>
                </ul>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Security of Personal Information</h2>
                <p class="hero-desc mb-4">Your Personal Information is stored in a manner that reasonably protects it from misuse and loss and from unauthorized access, modification or disclosure.</p>
                <p class="hero-desc mb-4">When your Personal Information is no longer needed for the purpose for which it was obtained, we will take reasonable steps to destroy or permanently de-identify your Personal Information. However, most of the Personal Information is or will be stored in client files which will be kept by us for a minimum of 7 years.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Access to your Personal Information</h2>
                <p class="hero-desc mb-4">You may access the Personal Information we hold about you and to update and/or correct it, subject to certain exceptions. If you wish to access your Personal Information, please contact us in writing.</p>
                <p class="hero-desc mb-4">EngiFolio will not charge any fee for your access request, but may charge an administrative fee for providing a copy of your Personal Information.</p>
                <p class="hero-desc mb-4">In order to protect your Personal Information we may require identification from you before releasing the requested information.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Maintaining the Quality of your Personal Information</h2>
                <p class="hero-desc mb-4">It is an important to us that your Personal Information is up to date. We  will  take reasonable steps to make sure that your Personal Information is accurate, complete and up-to-date. If you find that the information we have is not up to date or is inaccurate, please advise us as soon as practicable so we can update our records and ensure we can continue to provide quality services to you.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Policy Updates</h2>
                <p class="hero-desc mb-4">This Policy may change from time to time and is available on our website.</p>
                <p class="hero-desc mb-4"></p>

                <h2 class="hero-subtitle mb-4">Privacy Policy Complaints and Enquiries</h2>
                <p class="hero-desc mb-4">If you have any queries or complaints about our Privacy Policy please contact the project group.</p>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    name: 'Homepage',
    methods: {
        goHome() {
            this.$router.push('/');
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

.hero-title {
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
    /* width: 90%; */
}

.hero-list {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.2rem;
    color: #2b2b2b;
    text-align: justify;
    list-style-position: inside;
}
</style>