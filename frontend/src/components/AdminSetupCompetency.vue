<template>
    <div class="setup-wrap">

        <!-- top toolbar with actions -->
        <div class="comp-toolbar mb-3">
            <div class="comp-toolbar-right">
                <!--edit 1, edit multiple and delete actions-->
                <button v-if="selected.size > 0" class="btn btn-add" @click="confirmMassDelete">Delete ({{selected.size }})</button>
                <button v-if="selected.size === 1" class="btn btn-filter" @click="openAdd(getSingleSelected())">Edit</button>
                <button v-if="selected.size > 1" class="btn btn-filter" @click="openMassEdit">Edit ({{ selected.size}})</button>

                <!--add new compt-->
                <button class="btn btn-add" @click="openAdd()">Add new</button>
                <!--sort-->
                <div class="filter-wrap" ref="sortRef">
                    <button class="btn btn-filter" @click="sortDdOpen = !sortDdOpen">Sort</button>
                    <div v-if="sortDdOpen" class="filter-dd">

                        <p class="filter-heading">Sort by</p>
                        <div class="d-flex flex-column gap-1 mb-3">
                            <label class="filter-option" v-for="opt in sortByOptions" :key="opt.value">
                                <input type="radio" :value="opt.value" v-model="sortBy" class="filter-radio" @click="sortOrder = 'desc'"/>{{ opt.label }}
                            </label>
                        </div>

                        <p class="filter-heading">Order</p>
                        <div class="d-flex flex-column gap-1">
                            <label class="filter-option">
                                <input type="radio" value="desc" v-model="sortOrder" class="filter-radio"/>
                                {{ sortBy === 'name' ? 'A to Z' : 'Newest to Oldest' }}
                            </label>

                            <label class="filter-option">
                                <input type="radio" value="asc" v-model="sortOrder" class="filter-radio"/>
                                {{ sortBy === 'name' ? 'Z to A' : 'Oldest to Newest' }}
                            </label>
                        </div>

                        <div class="d-flex gap-2 mt-3 justify-content-end">
                            <button class="btn btn-filter-sm" @click="clearSort">Clear</button>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="search-wrap">
                    <img src="@/assets/search.png" class="search-icon" alt="search"/>
                    <input v-model="search" class="search-input" placeholder="Search competencies..." type="text"/>
                    <button v-if="search" class="search-clear" @click="search = ''">×</button>
                </div>
            </div>
        </div>

        <!-- table-->
        <div class="table-scroll">
            <table class="compt-table">
                <thead>
                    <tr>
                        <th class="check-col"><input type="checkbox" :checked="allSelected" @change="toggleSelectAll"/></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Indicators</th>
                        <th>Discontinued</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="loading">
                        <td colspan="9" class="empty-state">Loading...</td>
                    </tr>
                    <tr v-else-if="loadError">
                        <td colspan="9" class="empty-state">{{ loadError }}</td>
                    </tr>

                    <tr v-for="c in sortedCompts" :key="c.indicator_id"
                    :class="{ 'row-disc': c.discontinued_date, 'row-selected': selected.has(c.indicator_id) }"
                    @click="openViewModal(c)">

                        <td class="check-col" @click.stop>
                            <input type="checkbox" :checked="selected.has(c.indicator_id)"@change="toggleSelect(c.indicator_id)"/>
                        </td>
                        <td><span class="id-pill">{{ c.display_id }}</span></td>
                        <td class="name-cell">{{ c.indicator_name }}</td>
                        <td><span class="group-tag">{{ catName(c.group_id) }}</span></td>
                        <td class="expand-cell">{{ c.description?.slice(0, 60) }}{{ c.description?.length > 60 ? '...' : '' }}</td>

                        <td class="expand-cell">
                            <span v-if="c.attainment_indicators?.length">
                                {{ c.attainment_indicators.map(a => a.attainment_indicator || a).join(' · ').slice(0, 60) }}{{ c.attainment_indicators.map(a => a.attainment_indicator || a).join(' · ').length > 60 ? '...' : '' }}
                            </span>
                            <span v-else class="txt-muted">n/a</span>
                        </td>

                        <td class="date-cell" :class="{ 'disc-date': c.discontinued_date }">{{ c.discontinued_date || 'n/a' }}</td>
                        <td class="date-cell">{{ formatDate(c.created_at) }}</td>
                        <td class="date-cell">{{ formatDate(c.updated_at) }}</td>
                    </tr>
                    <tr v-if="!loading && !loadError && sortedCompts.length === 0">
                        <td colspan="9" class="empty-state">No competencies found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--add new popup-->
    <div v-if="addModal.show" class="add-popup" @click.self="addModal.show = false">
        <div class="add-popup-box">
            <h2 class="text-center fw-bold border-bottom p-3 add-title">{{ addModal.editing ? 'Edit competency' : 'Add new competency' }}</h2>
            <div class="add-popup-scroll px-4 py-4 d-flex flex-column gap-4">

                <div class="row g-4">
                    <div class="col-5">
                        <label class="form-label field-label">Category <span class="req">*</span></label>
                        <select v-model.number="f.group_id" class="form-select field-select rounded-3">
                            <option value="" disabled>Select category</option>
                            <option v-for="g in categories" :key="g.group_id" :value="g.group_id">
                                {{ g.display_id }} - {{ g.group_name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-3">
                        <label class="form-label field-label">Display ID <span class="req">*</span></label>
                        <input v-model.trim="f.display_id" class="form-control field-input rounded-3"
                        placeholder="eg: 1.1" maxlength="5" />
                    </div>
                    <div class="col-4">
                        <label class="form-label field-label">Discontinued Date</label>
                        <input v-model="f.discontinued_date" type="date" class="form-control field-input rounded-3" />
                    </div>
                </div>

                <div>
                    <label class="form-label field-label">Competency Name <span class="req">*</span></label>
                    <input v-model.trim="f.indicator_name" class="form-control field-input rounded-3"
                    placeholder="Name of this competency" maxlength="255"/>
                </div>

                <div>
                    <label class="form-label field-label">Description <span class="req">*</span></label>
                    <textarea v-model.trim="f.description" class="form-control field-input rounded-3" rows="3" placeholder="Describe what this competency covers..."/>
                </div>

                <div>
                    <label class="form-label field-label">Reference Link</label>
                    <input v-model.trim="f.indicator_link" class="form-control field-input rounded-3"
                    placeholder="https://example-reference.com" type="url"/>
                </div>

                <div>
                    <label class="form-label field-label mb-2">Indicators of Attainment</label>
                    <div class="d-flex flex-column gap-2">
                        <div v-for="(att, idx) in f.attainment_indicators" :key="idx"class="d-flex gap-3 align-items-center">

                            <span class="att-num">{{ idx + 1 }}.</span>
                            <input v-model.trim="f.attainment_indicators[idx]" class="form-control field-input rounded-3"
                            :placeholder="`Indicator ${idx + 1}`" />

                            <button class="del-btn" @click="f.attainment_indicators.splice(idx, 1)">
                                <img src="@/assets/delete.png" />
                            </button>
                        </div>
                        <button class="btn btn-filter-sm rounded-pill px-3 py-1 align-self-start mt-1"
                            @click="f.attainment_indicators.push('')">Add indicator</button>
                    </div>
                </div>

                <p v-if="addModal.error" class="error-text mb-0">{{ addModal.error }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
                <span class="scroll-txt"><u>Scroll to see all fields</u></span>
                <div class="d-flex gap-2">
                    <button class="btn btn-filter" @click="addModal.show = false">Cancel</button>
                    <button class="btn btn-add" @click="saveCompt">{{ addModal.editing ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </div>

    <!--view popup-->
    <div v-if="viewPopup.show" class="add-popup" @click.self="viewPopup.show = false">
        <div class="view-popup-box">
            <div class="d-flex align-items-center justify-content-between border-bottom p-3">

                <img class="plus-btn" src="@/assets/back.png" @click="viewPopup.show = false" />
                <h2 class="view-title mb-0">Competency {{ viewPopup.compt?.display_id }}</h2>

                <div class="d-flex gap-2">
                    <img class="plus-btn" src="@/assets/del.png" title="Delete" @click="triggerDelete(viewPopup.compt); viewPopup.show = false" />
                    <img class="plus-btn" src="@/assets/edit.png" title="Edit" @click="openAdd(viewPopup.compt); viewPopup.show = false" />
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 pt-3 pb-2 flex-wrap px-3">
                <span class="pill-tag">{{ catFullName(viewPopup.compt?.group_id) }}</span>
                <span v-if="viewPopup.compt?.discontinued_date" class="pill-tag pill-tag-disc">
                    Discontinued {{ viewPopup.compt.discontinued_date }}
                </span>
            </div>

            <div class="view-popup-scroll px-4 py-3 d-flex flex-column gap-4">
                <div>
                    <p class="section-label">Name:</p>
                    <p class="body-txt">{{ viewPopup.compt?.indicator_name }}</p>
                </div>
                <div>
                    <p class="section-label">Description:</p>
                    <p class="body-txt">{{ viewPopup.compt?.description }}</p>
                </div>
                <div v-if="viewPopup.compt?.indicator_link">
                    <p class="section-label">Reference Link:</p>
                    <a :href="viewPopup.compt.indicator_link" target="_blank" class="body-txt link-txt">{{ viewPopup.compt.indicator_link }}</a>
                </div>
                <div>
                    <p class="section-label">Indicators of Attainment:</p>
                    <ul v-if="viewPopup.compt?.attainment_indicators?.length" class="ps-3">
                        <li class="body-txt" v-for="(a, i) in viewPopup.compt.attainment_indicators" :key="i">{{ a.attainment_indicator || a }}</li>
                    </ul>
                    <p v-else class="body-txt">No indicators added yet.</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 border-top date-txt">
                <span>Created {{ formatDate(viewPopup.compt?.created_at) }}</span>
                <span>Updated {{ formatDate(viewPopup.compt?.updated_at) }}</span>
            </div>
        </div>
    </div>

    <!--mass edit popup-->
    <div v-if="massEditPopup.show" class="add-popup" @click.self="massEditPopup.show = false">
        <div class="add-popup-box">
            <h2 class="text-center fw-bold border-bottom p-3 add-title">Edit {{ selected.size }} Competencies</h2>
            <div class="add-popup-scroll px-4 py-4 d-flex flex-column gap-4">
                <p class="body-txt">- Mass editing is applicable for following fields only -</p>

                <div>
                    <label class="field-label">Change Category</label>
                    <select v-model.number="massForm.group_id" class="form-select field-select rounded-3">
                        <option :value="null">Keep existing</option>
                        <option v-for="g in categories" :key="g.group_id" :value="g.group_id">
                            {{ g.display_id }} - {{ g.group_name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label field-label">Discontinued Date</label>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="checkbox" v-model="massForm.clearDate" id="clearDate"/>
                        <p class="body-txt mb-0" for="clearDate">Clear existing dates</p>
                    </div>
                    <input v-model="massForm.discontinued_date" type="date" class="form-control field-input rounded-3" :disabled="massForm.clearDate"/>
                </div>
                <p v-if="massEditPopup.error" class="error-text mb-0">{{ massEditPopup.error }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
                <span class="scroll-txt"><u>Scroll to see all fields</u></span>
                <div class="d-flex gap-2">
                    <button class="btn btn-filter" @click="massEditPopup.show = false">Cancel</button>
                    <button class="btn btn-add" @click="saveMassEdit">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!--confirm delete popup-->
    <div v-if="showDeleteConfirm" class="view-popup" @click.self="showDeleteConfirm = false">
        <div class="delete-box text-center p-4">
            <h5 class="fw-bold mb-2 field-label">{{ isMassDelete ? `Delete ${selected.size} ${selected.size === 1 ? 'competency' : 'competencies'}?` : 'Delete this competency?' }}</h5>
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
import { onClickOutside } from '@vueuse/core'
import api from '@/services/api'

const categories = ref([])
const competencies = ref([])

const loading = ref(false)
const loadError = ref('')

const selected = ref(new Set())
const search = ref('')

const sortBy = ref('updated')
const sortOrder = ref('desc')
const sortRef = ref(null)
const sortDdOpen = ref(false)

const sortByOptions = [
    { value: 'name', label: 'Name' },
    { value: 'updated', label: 'Date Updated' },
    { value: 'created', label: 'Date Created' },
]

function clearSort() {
    sortBy.value = 'updated'
    sortOrder.value = 'desc'
    sortDdOpen.value = false
}

onClickOutside(sortRef, () => sortDdOpen.value = false)

const catName = (id) => {
    const g = categories.value.find(g => g.group_id === id)
    if (g) {
        return g.display_id
    }
    return '-'
}

const catFullName = (id) => {
    const g = categories.value.find(g => g.group_id === id)
    if (g) {
        return g.display_id + ' - ' + g.group_name
    }
    return '-'
}

const formatDate = (d) => {
    if (!d) {
        return '-'
    }
    const date = new Date(d)
    return date.toLocaleDateString('en-AU')
}

const getSingleSelected = () => {
    const id = [...selected.value][0]
    const competency = competencies.value.find(
        c => c.indicator_id === id
    )
    if (competency) {
        return competency
    }
    return null
}

const allSelected = computed(() => {
    if (sortedCompts.value.length === 0) {
        return false
    }
    const everySelected = sortedCompts.value.every(
        c => selected.value.has(c.indicator_id)
    )
    return everySelected
})

const filteredCompts = computed(() => {
    const q = search.value.toLowerCase()
    if (!q) {
        return competencies.value
    }
    return competencies.value.filter(c =>
        c.display_id?.toLowerCase().includes(q) ||
        c.indicator_name?.toLowerCase().includes(q) ||
        c.description?.toLowerCase().includes(q)
    )
})

const sortedCompts = computed(() => {
    const list = [...filteredCompts.value]
    const asc = sortOrder.value === 'asc'

    if (sortBy.value === 'name') {
        return list.sort((a, b) => asc
        ? (b.indicator_name || '').localeCompare(a.indicator_name || '')
        : (a.indicator_name || '').localeCompare(b.indicator_name || ''))
    }

    const field = sortBy.value === 'created' ? 'created_at' : 'updated_at'
    return list.sort((a, b) => {
        const dateA = new Date(a[field])
        const dateB = new Date(b[field])
        if (asc) {
            return dateA - dateB
        } else {
            return dateB - dateA
        }
    })
})

const toggleSelectAll = () => {
    selected.value = allSelected.value ? new Set() : new Set(sortedCompts.value.map(c => c.indicator_id))
}
const toggleSelect = (id) => {
    const s = new Set(selected.value)
    s.has(id) ? s.delete(id) : s.add(id)
    selected.value = s
}

const fetchAll = async () => {
    loading.value = true; loadError.value = ''
    try {
        const [catRes, comptRes] = await Promise.all([
            api.get('/competency-groups'),
            api.get('/competency-indicators')
        ])
        categories.value = catRes.data
        competencies.value = comptRes.data
    } catch (e) {
        loadError.value = e.response?.data?.message || 'Failed to load data'
    } finally {
        loading.value = false
    }
}

// add/edit modal
const addModal = reactive({ show: false, editing: null, error: '' })
const blankForm = () => ({ 
    group_id: '', 
    display_id: '', 
    indicator_name: '', 
    description: '', 
    indicator_link: '',
    discontinued_date: '', 
    attainment_indicators: [] 
})
const f = ref(blankForm())

const openAdd = (c = null) => {
    addModal.editing = c
    addModal.error = ''
    if (c) {
        const indicators = c.attainment_indicators || []
        const cleanedIndis = indicators.map(a =>
            a.attainment_indicator || a
        )

        f.value = {
            group_id: c.group_id,
            display_id: c.display_id,
            indicator_name: c.indicator_name,
            description: c.description || '',
            indicator_link: c.indicator_link || '',
            discontinued_date: c.discontinued_date || '',
            attainment_indicators: cleanedIndis
        }
    } else {
        f.value = blankForm()
    }
    addModal.show = true
}

const saveCompt = async () => {
    if (!f.value.group_id || !f.value.display_id || !f.value.indicator_name || !f.value.description) {
        addModal.error = 'Category, Display ID, Name and Description are required!'
        return
    }
    addModal.error = ''
    try {
        const p = { ...f.value, attainment_indicators: f.value.attainment_indicators.filter(a => a.trim()) }
        if (!p.discontinued_date) {
           p.discontinued_date = null
        }
        if (!p.indicator_link) {
            delete p.indicator_link
        }

        if (addModal.editing) {
            await api.put(`/competency-indicators/${addModal.editing.indicator_id}`, p)
            showPopUp('Competency updated.', 'success')
        } else {
            await api.post('/competency-indicators',p)
            showPopUp('Competency created.', 'success')
        }

        addModal.show = false
        selected.value = new Set()
        await fetchAll()
    } catch (e) {
        addModal.error = e.response?.data?.message || 'Failed to save'
    }
}

// view modal
const viewPopup = reactive({ show: false, compt: null })
const openViewModal = (c) => { 
    viewPopup.compt = c
    viewPopup.show = true 
}

// delete
const showDeleteConfirm = ref(false)
const itemToDelete = ref(null)
const isMassDelete = ref(false)

function triggerDelete(item) {
    itemToDelete.value = item
    isMassDelete.value = false
    showDeleteConfirm.value = true
}

function confirmMassDelete() {
    isMassDelete.value = true
    showDeleteConfirm.value = true
}

const doDelete = async () => {
    try {
        if (isMassDelete.value) {
            await Promise.all([...selected.value].map(id => api.delete(`/competency-indicators/${id}`)))
            selected.value = new Set()
            showPopUp('Competencies deleted.', 'success')
        } else {
            await api.delete(`/competency-indicators/${itemToDelete.value.indicator_id}`)
            showPopUp('Competency deleted.', 'success')
        }
        showDeleteConfirm.value = false
        itemToDelete.value = null
        await fetchAll()
    } catch (e) {
        showPopUp('Failed to delete.', 'error')
    }
}

// mass edit
const massEditPopup = reactive({ show: false, error: '' })
const massForm = ref({ group_id: null, discontinued_date: '', clearDate: false })

const openMassEdit = () => {
    massForm.value = { group_id: null, discontinued_date: '', clearDate: false }
    massEditPopup.error = ''
    massEditPopup.show = true
}

const saveMassEdit = async () => {
    massEditPopup.error = ''
    try {
        await Promise.all([...selected.value].map(id => {
            const p = {}
            if (massForm.value.group_id) {
                p.group_id = massForm.value.group_id
            }
            if (massForm.value.clearDate) {
                p.discontinued_date = null
            } else if (massForm.value.discontinued_date) {
                p.discontinued_date = massForm.value.discontinued_date
            }
            return api.put(`/competency-indicators/${id}`, p)
        }))
        selected.value = new Set()
        massEditPopup.show = false
        showPopUp('Competencies updated.', 'success')
        await fetchAll()
    } catch (e) {
        massEditPopup.error = e.response?.data?.message || 'Failed to update'
    }
}

// popup
const popUp = ref({ show: false, message: '', type: '' })
const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, 3000)
}

onMounted(fetchAll)
</script>

<style scoped>
.setup-wrap {
    max-width: 80%;
    margin: 0 auto;
}

.comp-toolbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.comp-toolbar-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.filter-wrap {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.filter-dd {
    position: absolute;
    top: calc(100% + 0.5rem);
    right: 0;
    background: #ffffff;
    border: 0.09rem solid #e0e0e0;
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    min-width: 12rem;
    box-shadow: 0 0.5rem 1.5rem #e5e5e5;
    z-index: 20;
}

.filter-heading {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.75rem;
    font-weight: bold;
    color: #888888;
    margin-bottom: 0.4rem;
}

.filter-option {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.85rem;
    color: #333333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.filter-radio {
    cursor: pointer;
}

.search-wrap {
    display: flex;
    align-items: center;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 2rem;
    padding: 0.35rem 0.75rem;
    gap: 0.4rem;
}

.search-wrap:focus-within {
    border-color: #888888;
    background: #ffffff;
}

.search-icon {
    width: 1rem;
    height: 1rem;
    object-fit: contain;
}

.search-input {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.8rem;
    color: #333333;
    background: transparent;
    border: none;
    outline: none;
    width: 10rem;
}

.search-input::placeholder {
    color: #979797;
}

.search-clear {
    background: none;
    border: none;
    font-size: 0.8rem;
    color: #aaaaaa;
    cursor: pointer;
    transition: color 0.2s ease;
}

.search-clear:hover {
    color: #555555;
}

.table-scroll {
    width: 100%;
    overflow-x: auto;
    border-radius: 0.9rem;
    border: 1px solid #dddddd;
    box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.06);
}

.compt-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #ffffff;
}

.compt-table th, .compt-table td {
    padding: 0.75rem 0.7rem;
    vertical-align: middle;
    border-bottom: 1px solid #e6e6e6;
}

.compt-table th {
    background: #f3f3f3;
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.82rem;
    color: #333333;
    font-weight: 500;
    white-space: nowrap;
}

.compt-table tbody tr:nth-child(even) {
    background: #fcfcfc;
}

.compt-table tbody tr:hover {
    background: #f5f5f5;
}

.row-selected {
    background: #f0f0f0 !important;
    outline: 2px solid #d0d0d0;
    outline-offset: -1px;
}

.check-col {
    width: 2.5rem;
    text-align: center;
}

.id-pill {
    font-family: 'Maven Pro', monospace;
    font-size: 0.8rem;
    background: #e8e8e8;
    border-radius: 0.4rem;
    padding: 0.15rem 0.5rem;
    white-space: nowrap;
}

.group-tag {
    font-family: 'Maven Pro', monospace;
    font-size: 0.8rem;
    background: #f0f0f0;
    border-radius: 0.4rem;
    padding: 0.15rem 0.5rem;
    color: #555555;
    white-space: nowrap;
}

.name-cell {
    min-width: 10rem;
    font-size: 0.9rem;
    font-family: 'Maven Pro', sans-serif;
    cursor: pointer;
}

.expand-cell {
    max-width: 14rem;
    font-size: 0.85rem;
    color: #555555;
    font-family: 'Maven Pro', sans-serif;
}

.date-cell {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.9rem;
    color: #888888;
    white-space: nowrap;
}

.disc-date {
    color: #c08080 !important;
}

.txt-muted {
    color: #aaaaaa;
    font-size: 0.85rem;
}

.empty-state {
    text-align: center;
    color: #707070;
    padding: 1.5rem;
    font-family: 'Maven Pro', sans-serif;
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

.link-txt {
    color: #1a6a86;
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

.field-input, .field-select {
    border: 0.1rem solid #e0e0e0;
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    border-radius: 0.5rem;
}

.field-input:focus, .field-select:focus {
    border-color: #c4c4c4;
    box-shadow: 0 0 0 0.02rem #2b2b2b;
}

.req {
    color: #b42318;
}

.att-num {
    font-family: 'Martian Mono', monospace;
    font-size: 0.8rem;
    color: #aaaaaa;
    min-width: 1.4rem;
}

.del-btn {
    width: 1.75rem;
    height: 1.75rem;
    border: none;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s ease;
    flex-shrink: 0;
}

.del-btn>img {
    width: 1.5rem;
    height: 1.5rem;
    object-fit: contain;
}

.del-btn:hover {
    transform: scale(1.1);
}

.scroll-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.9rem;
    color: #888888;
}

.btn {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    font-size: 1rem;
    cursor: pointer;
    border: none;
    padding: 0.4rem 1.2rem;
}

.btn-filter {
    background: #e6e6e6;
    color: #222222;
}

.btn-filter:hover {
    background: #666666;
    color: #ffffff;
}

.btn-add {
    color: #ffffff;
    background: #555555;
}

.btn-add:hover {
    background: #333333;
    color: #ffffff;
}

.btn-add:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-filter-sm {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 1.5rem;
    font-size: 0.8rem;
    background: #e6e6e6;
    color: #222222;
    border: none;
    cursor: pointer;
    padding: 0.25rem 0.75rem;
}

.btn-filter-sm:hover {
    background: #666666;
    color: #ffffff;
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

@media (max-width: 768px) {
    .comp-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .setup-wrap {
        max-width: 100%;
    }
}
</style>