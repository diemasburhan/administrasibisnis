@extends('layouts.admin')

@section('title', 'Menu Navigasi - Admin Panel')
@section('page_title', 'Pengelola Menu Navigasi')

@section('styles')
<style>
    .menu-manager-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 25px;
        margin-top: 15px;
    }
    .accordion-card {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-lg);
        margin-bottom: 15px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .accordion-header {
        background-color: var(--bg-light);
        padding: 12px 18px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--hairline);
    }
    .accordion-body {
        padding: 15px 18px;
    }
    .links-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-md);
        padding: 10px;
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .link-checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        cursor: pointer;
    }
    /* Menu structure panel */
    .menu-structure-card {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline);
        border-radius: var(--rounded-lg);
        padding: 25px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }
    .menu-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 15px;
        margin-bottom: 25px;
        min-height: 200px;
        padding: 10px;
        background-color: var(--bg-light);
        border: 1px dashed var(--hairline-strong);
        border-radius: var(--rounded-md);
    }
    .menu-item-node {
        background-color: var(--bg-white);
        border: 1px solid var(--hairline-strong);
        border-radius: var(--rounded-md);
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
        position: relative;
    }
    .menu-item-node.submenu {
        margin-left: 40px;
        border-left: 4px solid var(--primary);
    }
    .menu-item-node.submenu::before {
        content: "";
        position: absolute;
        left: -20px;
        top: 50%;
        width: 15px;
        height: 1px;
        background-color: var(--hairline-strong);
    }
    .menu-item-details {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-grow: 1;
    }
    .menu-item-label {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .menu-item-type-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background-color: var(--bg-light);
        border: 1px solid var(--hairline);
        color: var(--slate);
        text-transform: uppercase;
    }
    .menu-item-controls {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .menu-item-controls button {
        background-color: var(--bg-light);
        border: 1px solid var(--hairline-strong);
        border-radius: 4px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--slate);
        font-size: 0.8rem;
        transition: all 0.15s ease;
    }
    .menu-item-controls button:hover:not(:disabled) {
        background-color: var(--primary);
        color: var(--on-primary);
        border-color: var(--primary);
    }
    .menu-item-controls button:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    .menu-item-controls button.btn-delete:hover {
        background-color: var(--danger);
        color: var(--on-primary);
        border-color: var(--danger);
    }
    /* Expanded edit form fields */
    .menu-item-edit-fields {
        display: none;
        border-top: 1px solid var(--hairline);
        padding-top: 10px;
        margin-top: 10px;
        width: 100%;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .menu-item-node.expanded {
        flex-direction: column;
        align-items: flex-start;
    }
    .menu-item-node.expanded .menu-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="menu-manager-grid">
    <!-- Left Column: Add Menu Items -->
    <aside class="menu-add-sidebar">
        <!-- Accordion 1: Pages -->
        <div class="accordion-card">
            <div class="accordion-header" onclick="toggleAccordion('bodyPages')">
                <span>Halaman Statis</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-body" id="bodyPages">
                <div class="links-list" id="pagesList">
                    @forelse($pages as $page)
                        <label class="link-checkbox-item">
                            <input type="checkbox" class="page-chk" data-title="{{ $page->title }}" data-url="/page/{{ $page->slug }}">
                            <span>{{ $page->title }}</span>
                        </label>
                    @empty
                        <div style="font-size:0.85rem; color:var(--muted)">Belum ada halaman diterbitkan.</div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-secondary btn-sm w-100" onclick="addCheckedItems('page-chk')">
                    Tambah ke Menu
                </button>
            </div>
        </div>

        <!-- Accordion 2: Posts -->
        <div class="accordion-card">
            <div class="accordion-header" onclick="toggleAccordion('bodyPosts')">
                <span>Berita / Post</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-body" id="bodyPosts">
                <div class="links-list" id="postsList">
                    @forelse($posts as $post)
                        <label class="link-checkbox-item">
                            <input type="checkbox" class="post-chk" data-title="{{ $post->title }}" data-url="/post/{{ $post->slug }}">
                            <span>{{ \Illuminate\Support\Str::limit($post->title, 40) }}</span>
                        </label>
                    @empty
                        <div style="font-size:0.85rem; color:var(--muted)">Belum ada berita diterbitkan.</div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-secondary btn-sm w-100" onclick="addCheckedItems('post-chk')">
                    Tambah ke Menu
                </button>
            </div>
        </div>

        <!-- Accordion 3: Custom Links -->
        <div class="accordion-card">
            <div class="accordion-header" onclick="toggleAccordion('bodyCustom')">
                <span>Tautan Kustom (Custom Link)</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-body" id="bodyCustom">
                <div class="form-group" style="margin-bottom: 10px;">
                    <label style="font-size: 0.85rem;">URL Tautan</label>
                    <input type="text" id="customUrl" class="form-control form-control-sm" value="http://" placeholder="https://google.com">
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem;">Label Navigasi</label>
                    <input type="text" id="customLabel" class="form-control form-control-sm" placeholder="Nama Menu">
                </div>
                <button type="button" class="btn btn-secondary btn-sm w-100" onclick="addCustomLink()">
                    Tambah ke Menu
                </button>
            </div>
        </div>
    </aside>

    <!-- Right Column: Menu Structure -->
    <main class="menu-structure-card">
        <div style="border-bottom:1px solid var(--hairline); padding-bottom:15px; margin-bottom:15px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="font-size: 1.25rem; color: var(--primary-deep); margin:0;">Struktur Menu Navigasi</h3>
                <p style="margin:5px 0 0 0; color:var(--muted); font-size:0.85rem;">Susun hierarki menu di bawah ini. Tekan ikon edit untuk mengubah label.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="saveMenuStructure()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Menu
            </button>
        </div>

        <!-- Dynamic Menu list container -->
        <div class="menu-items-list" id="menuItemsList">
            <!-- Rendered by JS -->
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Accordion toggle helper
    function toggleAccordion(id) {
        const body = document.getElementById(id);
        if (body.style.display === 'none') {
            body.style.display = 'block';
        } else {
            body.style.display = 'none';
        }
    }

    // Default load layout
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('bodyPosts').style.display = 'none';
        document.getElementById('bodyCustom').style.display = 'none';
        renderMenuStructure();
    });

    // Flatten representation of hierarchical items for rendering and editing
    // A flat list with `isSubmenu: boolean` is easier to arrange with simple up/down/indent buttons.
    let flatMenuItems = [];

    // Parse the loaded hierarchical items into flat list
    const initialItems = @json($menuItems);
    initialItems.forEach(item => {
        flatMenuItems.push({
            label: item.label,
            url: item.url,
            isSubmenu: false
        });
        if (item.children && Array.isArray(item.children)) {
            item.children.forEach(child => {
                flatMenuItems.push({
                    label: child.label,
                    url: child.url,
                    isSubmenu: true
                });
            });
        }
    });

    // Render structure
    function renderMenuStructure() {
        const listContainer = document.getElementById('menuItemsList');
        listContainer.innerHTML = '';

        if (flatMenuItems.length === 0) {
            listContainer.innerHTML = `<div style="text-align:center; padding:40px; color:var(--muted)">
                <i class="fa-regular fa-compass fa-2x" style="margin-bottom:10px; display:block"></i>
                Menu masih kosong. Tambahkan item di sidebar kiri.
            </div>`;
            return;
        }

        flatMenuItems.forEach((item, idx) => {
            const isSub = item.isSubmenu;
            const isFirst = idx === 0;
            const isLast = idx === flatMenuItems.length - 1;
            
            // Can indent: only if it's not the first item, and the item itself is not already a submenu
            const canIndent = idx > 0 && !item.isSubmenu;
            // Can outdent: only if it is currently a submenu
            const canOutdent = item.isSubmenu;

            const html = `
                <div class="menu-item-node ${isSub ? 'submenu' : ''}" id="node-${idx}">
                    <div class="menu-top-row" style="display:flex; align-items:center; width:100%; justify-content:space-between;">
                        <div class="menu-item-details">
                            <span class="menu-item-label" id="label-text-${idx}">${item.label}</span>
                            <span class="menu-item-type-badge">${isSub ? 'Submenu' : 'Main Menu'}</span>
                            <small style="color:var(--muted); font-size:0.8rem; max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">${item.url}</small>
                        </div>
                        
                        <div class="menu-item-controls">
                            <button type="button" title="Ubah Nama/URL" onclick="toggleEditFields(${idx})">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button type="button" title="Pindah Atas" onclick="moveItem(${idx}, -1)" ${isFirst ? 'disabled' : ''}>
                                <i class="fa-solid fa-chevron-up"></i>
                            </button>
                            <button type="button" title="Pindah Bawah" onclick="moveItem(${idx}, 1)" ${isLast ? 'disabled' : ''}>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <button type="button" title="Jadikan Submenu" onclick="indentItem(${idx})" ${!canIndent ? 'disabled' : ''}>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            <button type="button" title="Jadikan Main Menu" onclick="outdentItem(${idx})" ${!canOutdent ? 'disabled' : ''}>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <button type="button" class="btn-delete" title="Hapus Item" onclick="removeItem(${idx})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Edit Fields -->
                    <div class="menu-item-edit-fields" id="edit-fields-${idx}">
                        <div class="form-group">
                            <label style="font-size:0.8rem; font-weight:600">Label Navigasi</label>
                            <input type="text" class="form-control form-control-sm" id="edit-label-${idx}" value="${item.label}" oninput="updateItemLabel(${idx}, this.value)">
                        </div>
                        <div class="form-group">
                            <label style="font-size:0.8rem; font-weight:600">URL Tautan</label>
                            <input type="text" class="form-control form-control-sm" id="edit-url-${idx}" value="${item.url}" oninput="updateItemUrl(${idx}, this.value)">
                        </div>
                    </div>
                </div>
            `;
            listContainer.insertAdjacentHTML('beforeend', html);
        });
    }

    // Toggle Edit Form fields inside item block
    function toggleEditFields(idx) {
        const node = document.getElementById(`node-${idx}`);
        const fields = document.getElementById(`edit-fields-${idx}`);
        node.classList.toggle('expanded');
        if (fields.style.display === 'grid') {
            fields.style.display = 'none';
        } else {
            fields.style.display = 'grid';
        }
    }

    function updateItemLabel(idx, val) {
        flatMenuItems[idx].label = val;
        document.getElementById(`label-text-${idx}`).innerText = val;
    }

    function updateItemUrl(idx, val) {
        flatMenuItems[idx].url = val;
    }

    // Add Checked Items from pages/posts list
    function addCheckedItems(chkClass) {
        const chks = document.querySelectorAll(`.${chkClass}:checked`);
        chks.forEach(chk => {
            flatMenuItems.push({
                label: chk.getAttribute('data-title'),
                url: chk.getAttribute('data-url'),
                isSubmenu: false
            });
            chk.checked = false; // Uncheck
        });
        
        renderMenuStructure();
        showToast(`Berhasil menambahkan ${chks.length} tautan ke menu.`);
    }

    // Add Custom Link
    function addCustomLink() {
        const urlInput = document.getElementById('customUrl');
        const labelInput = document.getElementById('customLabel');

        if (!labelInput.value.trim()) {
            alert('Silakan isi Label Navigasi.');
            return;
        }

        flatMenuItems.push({
            label: labelInput.value.trim(),
            url: urlInput.value.trim(),
            isSubmenu: false
        });

        // Reset
        urlInput.value = 'http://';
        labelInput.value = '';

        renderMenuStructure();
        showToast('Berhasil menambahkan tautan kustom.');
    }

    // Move Item Up / Down
    function moveItem(idx, dir) {
        const targetIdx = idx + dir;
        if (targetIdx < 0 || targetIdx >= flatMenuItems.length) return;

        const temp = flatMenuItems[idx];
        flatMenuItems[idx] = flatMenuItems[targetIdx];
        flatMenuItems[targetIdx] = temp;

        renderMenuStructure();
    }

    // Indent / Outdent
    function indentItem(idx) {
        flatMenuItems[idx].isSubmenu = true;
        renderMenuStructure();
    }

    function outdentItem(idx) {
        flatMenuItems[idx].isSubmenu = false;
        renderMenuStructure();
    }

    // Remove Item
    function removeItem(idx) {
        if (confirm('Hapus item menu ini?')) {
            flatMenuItems.splice(idx, 1);
            renderMenuStructure();
        }
    }

    // Serialize Flat Menu structure back into Nested Menu structure for Backend saving
    function saveMenuStructure() {
        const nestedMenu = [];
        let currentParent = null;

        flatMenuItems.forEach(item => {
            if (!item.isSubmenu) {
                currentParent = {
                    label: item.label,
                    url: item.url,
                    children: []
                };
                nestedMenu.push(currentParent);
            } else {
                if (currentParent) {
                    currentParent.children.push({
                        label: item.label,
                        url: item.url
                    });
                } else {
                    // Fallback to top-level if no parent exists
                    nestedMenu.push({
                        label: item.label,
                        url: item.url
                    });
                }
            }
        });

        // Send to backend via AJAX
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch("{{ route('admin.menus.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                menu_structure: JSON.stringify(nestedMenu)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
            } else {
                showToast('Terjadi kesalahan saat menyimpan menu.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Koneksi terputus. Gagal menghubungi server.', 'danger');
        });
    }
</script>
@endsection
