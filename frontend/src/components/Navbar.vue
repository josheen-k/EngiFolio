<template>
    <nav class="navbar px-4 px-md-5 py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-5">
            <div class="logo">
                <img src="@/assets/engifolio-logo.png" alt="EngiFolio" class="logo-img" />
            </div>
            <ul class="navbar-nav flex-row gap-2">
                <li class="nav-item">
                    <router-link class="nav-link" to="/student/dashboard">Dashboard</router-link>
                </li>
                <li>
                    <router-link class="nav-link" to="/student/eaCompetency">Competencies</router-link>
                </li>
                <li>
                    <router-link class="nav-link" to="/student/career-planning">Goals</router-link>
                </li>
                <li>
                    <router-link class="nav-link" to="/student/networking">Networking</router-link>
                </li>
                <li>
                    <router-link class="nav-link" to="/student/career-development">CDL</router-link>
                </li>
            </ul>
        </div>
        <div>
            <img class="rounded-circle av-img"
                src="https://img.freepik.com/free-photo/young-woman-attend-courses-girl-student-studying-holding-notebooks-showing-thumb-up-approval-recommending-company-standing-blue-background_1258-70145.jpg"
                alt="Profile">
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Navbar'
}
</script>

<style scoped>
.logo-img {
    height: 2.5rem;
    object-fit: contain;
}

.nav-link {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1.25rem;
    color: #444444;
    border-radius: 1.8rem;
    padding: 0.5rem 1rem;
}

.nav-link:hover {
    background: #f0f0f0;
    color: #222222;
}

.av-img {
    width: 3.5rem;
    height: 3.5rem;
    object-fit: cover;
    border: 2px solid #c5c5c5;
}
</style>