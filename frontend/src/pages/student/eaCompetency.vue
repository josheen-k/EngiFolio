<template>
  <Navbar/>

  <div>
    <aside>
      <nav>
        <ul>
          <li>CURRENT</li>
          <li>DISCONTINUED</li>
          <li>DRAFTS</li>
          <li>FEEDBACK</li>
        </ul>
      </nav>
    </aside>

    <main>
      <h1>Current Competencies</h1>

      <section>
        <h2>Knowledge and Skill Base</h2>
        <span><b>6</b></span>
        <ul>
          <li>Competency 1.1</li>
          <li>Competency 1.2</li>
          <li>Competency 1.3</li>
          <li>Competency 1.4</li>
          <li>Competency 1.5</li>
          <li>Competency 1.6</li>
        </ul>
      </section>

      <section>
        <h2>Engineering Application Ability</h2>
        <span><b>4</b></span>
        <ul>
          <li>Competency 2.1</li>
          <li>Competency 2.2</li>
          <li>Competency 2.3</li>
          <li>Competency 2.4</li>
        </ul>
      </section>

      <section>
        <h2>Professional and Personal Attributes</h2>
        <span><b>6</b></span>
        <ul>
          <li>Competency 1.1</li>
          <li>Competency 1.2</li>
          <li>Competency 1.3</li>
          <li>Competency 1.4</li>
          <li>Competency 1.5</li>
          <li>Competency 1.6</li>
        </ul>
      </section>
    </main>
  </div>

  <Footer/>
</template>

<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

</script>