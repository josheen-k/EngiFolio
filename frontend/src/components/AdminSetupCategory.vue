<template>
    <div class="setup-wrap">
        <!--add new cat button-->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-add" @click="openModal()">Add new</button>
        </div>

        <!--curr categories, same style as student compt page -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 section-toggle"
                @click="sections.current = !sections.current">
                <img class="triangle" :class="{ open: sections.current }" src="@/assets/triangle.png" />
                <span class="c-label">Current</span>
                <span class="txt">{{ currentCats.length }}</span>
            </div>

            <div v-if="sections.current">
                <div v-if="currentCats.length" class="row g-3">
                    <div class="col-6 col-sm-4 col-md-3 col-xl-3" v-for="cat in currentCats" :key="cat.group_id">

                        <div class="compt-card p-3" @click="openView(cat)">
                            <h5 class="compt-id mb-2">{{ cat.display_id }}</h5>
                            <h5 class="compt-label mb-2" :data-tooltip="cat.group_name">{{ cat.group_name }}</h5>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="reflecs-blue rounded-pill px-3 py-1">
                                    {{ comptCount(cat.group_id) }} competenc{{ comptCount(cat.group_id) === 1 ? 'y' :'ies' }}
                                </span>
                                <button class="icon-btn" title="Delete" @click.stop="triggerDelete(cat)">
                                    <img src="@/assets/del.png" class="icon-img" />
                                </button>
                            </div>
                            <p class="txt-lvl mb-0">Updated {{ formatDate(cat.updated_at) }}</p>
                        </div>
                    </div>
                </div>
                <p v-else class="text-secondary ms-2">No current categories.</p>
            </div>
        </div>

        <!-- discontinued -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 section-toggle"
                @click="sections.discontinued = !sections.discontinued">
                <img class="triangle" :class="{ open: sections.discontinued }" src="@/assets/triangle.png" />
                <span class="c-label">Discontinued</span>
                <span class="txt">{{ discontinuedCats.length }}</span>
            </div>

            <div v-if="sections.discontinued">
                <div v-if="discontinuedCats.length" class="row g-3">
                    <div class="col-6 col-sm-4 col-md-3 col-xl-3" v-for="cat in discontinuedCats" :key="cat.group_id">

                        <div class="compt-card p-3" @click="openView(cat)">
                            <h5 class="compt-id mb-1">{{ cat.display_id }}</h5>
                            <h5 class="compt-label mb-2" :data-tooltip="cat.group_name">{{ cat.group_name }}</h5>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="reflecs-red rounded-pill px-3 py-1">
                                    {{ comptCount(cat.group_id) }} competenc{{ comptCount(cat.group_id) === 1 ? 'y' : 'ies'}}
                                </span>
                                <button class="icon-btn" title="Delete" @click.stop="triggerDelete(cat)">
                                    <img src="@/assets/del.png" class="icon-img" />
                                </button>
                            </div>
                            <p class="txt-lvl disc-date mb-0">Discontinued {{ cat.discontinued_date }}</p>
                        </div>
                    </div>
                </div>
                <p v-else class="text-secondary ms-2">No discontinued categories.</p>
            </div>
        </div>
    </div>

    <!-- Add/edit popup -->
    <div v-if="popup.show" class="add-popup" @click.self="popup.show = false">
        <div class="add-popup-box">
            <h2 class="text-center fw-bold border-bottom p-3 add-title">
                {{ popup.editing ? 'Edit Category' : 'Add New Category' }}
            </h2>

            <div class="add-popup-scroll px-4 py-4 d-flex flex-column gap-4">
                <div class="row g-4">
                    <div class="col-4">
                        <label class="form-label field-label">Display ID <span class="req">*</span></label>
                        <input v-model.trim="form.display_id" class="form-control field-input rounded-3"
                        placeholder="eg: CAT1" maxlength="20"/>
                    </div>

                    <div class="col-8">
                        <label class="form-label field-label">Group Name <span class="req">*</span></label>
                        <input v-model.trim="form.group_name" class="form-control field-input rounded-3"
                        placeholder="eg: Engineering Practice" maxlength="100" />
                    </div>
                </div>

                <div>
                    <label class="form-label field-label">Description</label>
                    <textarea v-model.trim="form.description" class="form-control field-input rounded-3" rows="3"
                    placeholder="Describe this category..."/>
                </div>

                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label field-label">Discontinued Date</label>
                        <input v-model="form.discontinued_date" type="date"
                        class="form-control field-input rounded-3" />
                    </div>
                </div>

                <p v-if="popup.error" class="error-text mb-0">{{ popup.error }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
                <span class="scroll-txt"><u>Scroll to see all fields</u></span>
                <div class="d-flex gap-2">
                    <button class="btn btn-filter" @click="popup.show = false">Cancel</button>
                    <button class="btn btn-add" @click="save">{{ popup.editing ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- view category popup-->
    <div v-if="viewPopup.show" class="view-popup" @click.self="viewPopup.show = false">
        <div class="view-popup-box">
            <div class="d-flex align-items-center justify-content-between border-bottom p-3">
                <img class="plus-btn" src="@/assets/back.png" @click="viewPopup.show = false"/>
                <h2 class="view-title mb-0">{{ viewPopup.cat?.group_name }}</h2>

                <div class="d-flex gap-2">
                    <img class="plus-btn" src="@/assets/del.png" title="Delete"
                    @click="triggerDelete(viewPopup.cat); viewPopup.show = false"/>
                    <img class="plus-btn" src="@/assets/edit.png" title="Edit"
                    @click="openModal(viewPopup.cat); viewPopup.show = false"/>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 pt-3 pb-2 flex-wrap px-3">
                <span class="pill-tag">{{ viewPopup.cat?.display_id }}</span>
                <span v-if="viewPopup.cat?.discontinued_date" class="pill-tag pill-tag-disc">
                    Discontinued {{ viewPopup.cat.discontinued_date }}
                </span>
            </div>

            <div class="view-popup-scroll px-4 py-3 d-flex flex-column gap-4">
                <div>
                    <p class="section-label">Description:</p>
                    <p class="body-txt">{{ viewPopup.cat?.description || 'No description added.' }}</p>
                </div>
                <div>
                    <p class="section-label">Competencies:</p>
                    <p class="body-txt">{{ comptCount(viewPopup.cat?.group_id) }} competenc{{ comptCount(viewPopup.cat?.group_id) === 1 ? 'y' : 'ies' }} in this category.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 border-top date-txt">
                <span>Updated {{ formatDate(viewPopup.cat?.updated_at) }}</span>
            </div>
        </div>
    </div>

    <!-- delete confirmation -->
    <div v-if="showDeleteConfirm" class="view-popup" @click.self="showDeleteConfirm = false">
        <div class="delete-box text-center p-4">
            <h5 class="fw-bold mb-2 field-label">Delete this category?</h5>
            <p class="field-desc mb-4">This action cannot be undone.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-filter" @click="showDeleteConfirm = false">Cancel</button>
                <button class="btn btn-add rounded-pill px-4" @click="doDelete">Delete</button>
            </div>
        </div>
    </div>

    <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">{{ popUp.message }}</div>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue'
import api from '@/services/api'

const categories = ref([])
const competencies = ref([])
const sections = reactive({ current: true, discontinued: false })

const currentCats = computed(() => categories.value.filter(c => !c.discontinued_date))
const discontinuedCats = computed(() => categories.value.filter(c => !!c.discontinued_date))
const comptCount = (id) => competencies.value.filter(c => c.group_id === id).length

const formatDate = (d) => {
    if (!d) return '—'
    return new Date(d).toLocaleDateString('en-AU')
}

const fetchAll = async () => {
    try {
        const [catRes, comptRes] = await Promise.all([
            api.get('/competency-groups'),
            api.get('/competency-indicators')
        ])
        categories.value = catRes.data
        competencies.value = comptRes.data
    } catch (e) {
        console.error('Failed to load categories', e)
    }
}

// popup
const popup = reactive({ show: false, editing: null, error: '' })
const blankForm = () => ({ display_id: '', group_name: '', description: '', discontinued_date: '' })
const form = ref(blankForm())
const viewPopup = reactive({ show: false, cat: null })

const openView = (cat) => {
  viewPopup.cat = cat
  viewPopup.show = true
}

const openModal = (cat) => {
    popup.editing = cat
    popup.error = ''

    if (cat) {
        form.value = {
            display_id: cat.display_id,
            group_name: cat.group_name,
            description: cat.description || '',
            discontinued_date: cat.discontinued_date || ''
        }
    } else {
        form.value = blankForm()
    }

    popup.show = true
}

const save = async() => {
    if (!form.value.display_id || !form.value.group_name) {
        popup.error = 'Display ID and Group name are required.' 
        return
    }
    popup.saving = true
    popup.error = ''
    try {
        const p = { ...form.value }
        if (!p.discontinued_date) {
            delete p.discontinued_date
        }
        if (popup.editing) {
            await api.put(`/competency-groups/${popup.editing.group_id}`, p)
            showPopUp('Category updated.', 'success')
        } else {
            await api.post('/competency-groups', p)
            showPopUp('Category created.', 'success')
        }
        popup.show = false
        await fetchAll()
    } catch (e) {
        popup.error = e.response?.data?.message || 'Failed to save'
    } finally {
        popup.saving = false
    }
}

const showDeleteConfirm = ref(false)
const itemToDelete = ref(null)

function triggerDelete(item) {
  itemToDelete.value = item
  showDeleteConfirm.value = true
}

const doDelete = async () => {
  try {
    await api.delete(`/competency-groups/${itemToDelete.value.group_id}`)
    showDeleteConfirm.value = false
    itemToDelete.value = null
    showPopUp('Deleted successfully.', 'success')
    await fetchAll()
  } catch (e) {
    showPopUp('Failed to delete.', 'error')
  }
}

// bubble popup
const popUp = ref({ show: false, message: '', type: '' })
const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, 3000)
}

onMounted(fetchAll)
</script>

<style scoped>
.setup-wrap {
    max-width: 70%;
    margin: 0 auto;
}

.section-toggle {
    cursor: pointer;
    user-select: none;
}

.triangle {
    width: 0.8rem;
    height: 0.8rem;
    transition: transform 0.2s ease;
}

.triangle.open {
    transform: rotate(90deg);
}

.c-label {
    font-family: 'Martian Mono', monospace;
    font-size: clamp(1rem, 3vw, 1.4rem);
    font-weight: 100;
}

.txt {
    font-family: 'Maven Pro', sans-serif;
    background-color: #e7e7e7;
    border-radius: 50%;
    font-size: smaller;
    padding: 0.05rem 0.4rem;
}

.compt-card {
    width: 100%;
    min-height: 8.125rem;
    border-radius: 1.5rem;
    border: 1px solid #bababa;
    background: #ffffff;
    cursor: default;
    transition: box-shadow 0.2s ease;
    position: relative;
    overflow: visible;
}

.compt-card:hover {
    box-shadow: 0 0.25rem 0.75rem #e5e5e5;
}

.compt-card-disc {
    background: #f9f9f9;
    border-color: #d8d8d8;
}

.card-menu {
    position: absolute;
    top: 0.6rem;
    right: 0.6rem;
    display: flex;
    gap: 0.1rem;
}

.icon-btn {
    width: 2rem;
    height: 2rem;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.icon-btn:hover {
    transform: scale(1.1);
}

.icon-img {
    width: 1.6rem;
    height: 1.6rem;
    object-fit: contain;
}

.compt-id {
    font-family: 'Martian Mono', monospace;
    font-size: 0.7rem;
    color: #aaaaaa;
    font-weight: 400;
}

.compt-label {
    font-family: 'Maven Pro', sans-serif;
    font-size: clamp(0.85rem, 2.5vw, 1.1rem);
    font-weight: 100;
    color: #888888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: default;
}

.compt-label::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: calc(100% + 0.4rem);
    left: 50%;
    transform: translateX(-50%);
    background: #727272;
    color: #ffffff;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.75rem;
    white-space: normal;
    width: max-content;
    max-width: 14rem;
    padding: 0.4rem 0.65rem;
    border-radius: 0.5rem;
    box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.2);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.15s ease;
    z-index: 5;
}

.compt-label:hover::after {
    opacity: 1;
}

.txt-lvl {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.85rem;
    color: #888888;
}

.disc-date {
    color: #c08080;
}

.reflecs-blue {
    background: #e2f8ff;
    color: #1a6a86;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.85rem;
}

.reflecs-red {
    background: #ffe3e3;
    color: #b03030;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.85rem;
}

.add-popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(0.375rem);
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
}

.add-popup-box {
    background: #ffffff;
    border-radius: 1.25rem;
    width: 100%;
    max-width: 45rem;
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.add-popup-scroll {
    overflow-y: auto;
    flex: 1;
}

.add-popup-scroll::-webkit-scrollbar {
    width: 0.375rem;
}

.add-popup-scroll::-webkit-scrollbar-thumb {
    background: #e0e0e0;
    border-radius: 2px;
}

.add-title {
    font-family: 'Martel', serif;
    font-size: 1.6rem;
    color: #2b2b2b;
}

.view-popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(0.375rem);
    z-index: 4;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
}

.view-popup-box {
    background: #ffffff;
    border-radius: 1.25rem;
    width: 100%;
    max-width: 45rem;
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.view-popup-scroll {
    overflow-y: auto;
    flex: 1;
}

.view-title {
    font-family: 'Martel', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #2b2b2b;
}

.plus-btn {
    width: 2rem;
    height: 2rem;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.plus-btn:hover { 
    transform: scale(1.1); 
}

.pill-tag {
    border: 0.09rem solid #d0d0d0;
    border-radius: 999px;
    padding: 0.25rem 1rem;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    color: #444444;
    background: #ffffff;
}

.pill-tag-disc {
    border-color: #f0c0c0;
    color: #c08080;
    background: #fff5f5;
}

.section-label {
    font-family: 'Martel', sans-serif;
    font-size: 1rem;
    text-decoration: underline;
    color: #222222;
    margin-bottom: 0.5rem;
}

.body-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.85rem;
    line-height: 1.75;
    color: #444444;
    margin-bottom: 0;
}

.date-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.9rem;
    color: #888888;
}

.delete-box {
    background: #ffffff;
    border-radius: 1.25rem;
    max-width: 22.5rem;
    width: 100%;
    box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.field-label {
    font-family: 'Martel', sans-serif;
    font-size: 1rem;
    color: #222222;
}

.field-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    line-height: 1.5;
    color: #444444;
}

.field-input {
    border: 0.1rem solid #e0e0e0;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    border-radius: 0.5rem;
}

.field-input:focus {
    border-color: #c4c4c4;
    box-shadow: 0 0 0 0.02rem #2b2b2b;
}

.req {
    color: #b42318;
}

.scroll-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.9rem;
    color: #888888;
}

.btn-filter {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    font-size: 1rem;
    background: #e6e6e6;
    color: #222222;
    border: none;
}

.btn-filter:hover {
    background: #666666;
    color: #ffffff;
}

.btn-add {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    border: none;
    padding: 0.4rem 1.2rem;
}

.btn-add:hover {
    background: #333333;
    color: #ffffff;
}

.btn-add:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.error-text {
    color: #b42318;
    font-size: 0.88rem;
}

.popUp-msg {
    position: fixed;
    top: 5rem;
    left: 0;
    right: 0;
    margin-inline: auto;
    width: max-content;
    padding: 0.75rem 2rem;
    border-radius: 2rem;
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.15rem;
    z-index: 100;
}

.popUp-msg.success {
    background: #5d5d5d;
    color: #fff;
}

.popUp-msg.error {
    background: #db7979;
    color: #fff;
}
</style>