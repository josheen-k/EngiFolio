<template>
    <div class="divider"></div>
</template>

<style scoped>
.divider {
    width: 100%;
    height: 2rem;
    display: flex;
    justify-content: center;
    margin: 3rem 0;
    background: linear-gradient(45deg, #d9bebe, #6b6be4);
    -webkit-mask: url('@/assets/divider.png') no-repeat center;
    -webkit-mask-size: contain;
    mask: url('@/assets/divider.png') no-repeat center;
    mask-size: contain;
}
</style>