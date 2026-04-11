<template>
    <nav>
        <div>EngiFOlio</div>
        <div>
            <ul>
                <li>
                    <router-link to="/student/dashboard">Dashboard</router-link>
                </li>
                <li>
                    <router-link to="/student/eaCompetency">Competencies</router-link>
                </li>
                <li>
                    <router-link to="/student/career-planning">Goals</router-link>
                </li>
                <li>
                    <router-link to="/student/networking">Networking</router-link>
                </li>
                <li>
                    <router-link to="/student/career-development">CDL</router-link>
                </li>
            </ul>
            <div>
                <img src="" alt="Profile">
            </div>
        </div> 
    </nav>
</template>

<script>
export default {
    name: 'Navbar'
}
</script>