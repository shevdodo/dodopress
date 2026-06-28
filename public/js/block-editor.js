(function () {
    'use strict';

    const B = window.DD_BLOCKS || {};
    let blocks = [];
    let container = null;
    let activeId = null;

    document.addEventListener('DOMContentLoaded', function () {
        const ta = document.getElementById('blocks-json');
        const ed = document.getElementById('dd-block-editor');
        if (!ta || !ed) return;

        try {
            const e = ta.value ? JSON.parse(ta.value) : [];
            blocks = Array.isArray(e) ? e : e.blocks || [];
            blocks.forEach(function(b) {
                if (!b.id) b.id = 'b_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
            });
        } catch (e) {
            blocks = [];
        }

        container = document.getElementById('dd-blocks-container');
        renderAll();
        initSort();
        initAdd();
        initCollapse();
    });

    function getPreviewUrl(val, explicitUrl) {
        if (explicitUrl) return explicitUrl;
        if (!val) return '';
        if (val.startsWith('http://') || val.startsWith('https://') || val.startsWith('data:')) return val;
        const base = window.DD_STORAGE_URL || '/storage/';
        return base + val;
    }

    function initSort() {
        if (typeof Sortable === 'undefined') return;
        Sortable.create(container, {
            animation: 200,
            easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
            handle: '.dd-block-handle',
            ghostClass: 'dd-block-ghost',
            dragClass: 'dd-block-dragging',
            onEnd: function (e) {
                const m = blocks.splice(e.oldIndex, 1)[0];
                blocks.splice(e.newIndex, 0, m);
                save();
                renderAll();
            }
        });
    }

    function renderAll() {
        if (!container) return;
        container.innerHTML = '';
        blocks.forEach(function (b) {
            container.appendChild(createEl(b));
        });
        attachEv();
        updateEmptyState();
        save();
    }

    function updateEmptyState() {
        const empty = document.getElementById('dd-empty-state');
        if (!empty) return;
        if (blocks.length === 0) {
            empty.classList.remove('hidden');
        } else {
            empty.classList.add('hidden');
        }
    }

    function createEl(block) {
        const def = B[block.type] || {};
        const d = document.createElement('div');
        d.className = 'dd-block-item group';
        d.dataset.blockId = block.id;
        d.innerHTML =
            '<div class="dd-block-header">' +
            '<div class="dd-block-handle">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>' +
            '</div>' +
            '<div class="dd-block-icon">' + (def.icon || '') + '</div>' +
            '<span class="dd-block-name">' + (def.name || block.type) + '</span>' +
            '<div class="dd-block-actions">' +
            '<button type="button" class="dd-btn-edit" title="Edit" data-block-id="' + block.id + '">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>' +
            '</button>' +
            '<button type="button" class="dd-btn-clone" title="Duplicate" data-block-id="' + block.id + '">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>' +
            '</button>' +
            '<button type="button" class="dd-btn-delete" title="Delete" data-block-id="' + block.id + '">' +
            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '<div class="dd-block-preview">' + preview(block) + '</div>';
        return d;
    }

    function preview(block) {
        const def = B[block.type] || {};
        const data = block.data || {};
        const lines = [];
        if (def.fields) {
            Object.keys(def.fields).forEach(function (k) {
                const f = def.fields[k];
                const v = data[k];
                if (v) {
                    const lb = f.label || k;
                    if (f.type === 'textarea' || f.type === 'richtext') {
                        const s = String(v).substring(0, 80) + (String(v).length > 80 ? '...' : '');
                        lines.push('<span class="dd-preview-label">' + lb + ':</span> ' + s.replace(/<[^>]*>/g, ''));
                    } else if (f.type === 'gallery' || f.type === 'repeater') {
                        lines.push('<span class="dd-preview-label">' + lb + ':</span> ' + (Array.isArray(v) ? v.length : 0) + ' item(s)');
                    } else {
                        lines.push('<span class="dd-preview-label">' + lb + ':</span> ' + escapeHtml(String(v)));
                    }
                }
            });
        }
        return lines.length === 0
            ? '<span class="text-gray-400 text-sm italic">(empty)</span>'
            : lines.join('<br>');
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function attachEv() {
        document.querySelectorAll('.dd-btn-edit').forEach(function (b) {
            b.addEventListener('click', function (e) {
                e.stopPropagation();
                openEditor(this.dataset.blockId);
            });
        });
        document.querySelectorAll('.dd-btn-clone').forEach(function (b) {
            b.addEventListener('click', function (e) {
                e.stopPropagation();
                cloneBlock(this.dataset.blockId);
            });
        });
        document.querySelectorAll('.dd-btn-delete').forEach(function (b) {
            b.addEventListener('click', function (e) {
                e.stopPropagation();
                deleteBlock(this.dataset.blockId);
            });
        });
    }

    function initAdd() {
        document.querySelectorAll('.dd-add-block-btn').forEach(function (b) {
            b.addEventListener('click', function () {
                addBlock(this.dataset.blockType);
            });
        });
    }

    function initCollapse() {
        const btn = document.getElementById('dd-collapse-btn');
        if (!btn) return;
        btn.addEventListener('click', function () {
            const toolbar = this.closest('.bg-white');
            const body = toolbar.querySelector('.px-5.py-4');
            if (body) {
                body.classList.toggle('hidden');
                const icon = this.querySelector('svg');
                if (icon) icon.classList.toggle('rotate-180');
            }
        });
    }

    function addBlock(type) {
        const def = B[type];
        if (!def) return;
        blocks.push({
            id: 'b_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            type: type,
            data: JSON.parse(JSON.stringify(def.default || {}))
        });
        renderAll();
        const items = container.querySelectorAll('.dd-block-item');
        if (items.length > 0) {
            items[items.length - 1].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function deleteBlock(id) {
        if (!confirm('Delete this block?')) return;
        blocks = blocks.filter(function (b) { return b.id !== id; });
        renderAll();
    }

    function cloneBlock(id) {
        const i = blocks.findIndex(function (b) { return b.id === id; });
        if (i === -1) return;
        const c = JSON.parse(JSON.stringify(blocks[i]));
        c.id = 'b_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
        blocks.splice(i + 1, 0, c);
        renderAll();
    }

    function openEditor(id) {
        try {
            const i = blocks.findIndex(function (b) { return b.id === id; });
            if (i === -1) return;
            const block = blocks[i];
            const def = B[block.type];
            if (!def) return;

            activeId = id;
            const modal = document.getElementById('dd-block-modal');
            const form = document.getElementById('dd-block-form');
            const title = document.getElementById('dd-block-modal-title');
            if (!modal || !form) return;

            title.textContent = 'Edit: ' + (def.name || block.type);
            form.innerHTML = '';
            form.dataset.blockId = id;

            const data = block.data || {};
            if (def.fields) {
                Object.keys(def.fields).forEach(function (k) {
                    form.appendChild(buildField(k, def.fields[k], data[k]));
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('dd-modal-close').onclick = closeEditor;
            document.getElementById('dd-modal-cancel').onclick = closeEditor;
            document.getElementById('dd-modal-save').onclick = saveEditor;
            modal.querySelector('.fixed.inset-0').onclick = function (e) {
                if (e.target === this) closeEditor();
            };
            document.addEventListener('keydown', escH);
        } catch (err) {
            console.error('Error opening editor:', err);
            alert('Error: ' + err.message);
        }
    }

    function escH(e) {
        if (e.key === 'Escape') closeEditor();
    }

    function closeEditor() {
        const m = document.getElementById('dd-block-modal');
        if (m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        activeId = null;
        document.removeEventListener('keydown', escH);
    }

    function saveEditor() {
        const form = document.getElementById('dd-block-form');
        if (!form || !activeId) return;

        const i = blocks.findIndex(function (b) { return b.id === activeId; });
        if (i === -1) return;

        const block = blocks[i];
        const def = B[block.type];
        if (!def) return;

        const nd = {};
        Object.keys(def.fields).forEach(function (k) {
            const f = def.fields[k];
            if (f.type === 'repeater') {
                const items = [];
                form.querySelectorAll('[data-repeater="' + k + '"]').forEach(function (r) {
                    const item = {};
                    let sfArray = [];
                    if (Array.isArray(f.subfields)) {
                        sfArray = f.subfields;
                    } else if (f.subfields && typeof f.subfields === 'object') {
                        sfArray = Object.values(f.subfields);
                    }
                    sfArray.forEach(function (sf) {
                        const inp = r.querySelector('[name="' + k + '[' + sf.key + '][]"]');
                        if (inp) item[sf.key] = inp.value;
                    });
                    items.push(item);
                });
                nd[k] = items;
            } else if (f.type === 'gallery') {
                const imgs = [];
                form.querySelectorAll('[name="' + k + '[]"]').forEach(function (inp) {
                    if (inp.value) imgs.push(inp.value);
                });
                nd[k] = imgs;
            } else {
                nd[k] = form.querySelector('[name="' + k + '"]')?.value || '';
            }
        });

        block.data = nd;
        renderAll();
        closeEditor();
    }

    function buildField(k, f, v) {
        const w = document.createElement('div');
        w.className = 'dd-field';

        const l = document.createElement('label');
        l.className = 'dd-field-label';
        l.textContent = f.label || k;
        w.appendChild(l);

        switch (f.type) {
            case 'textarea':
            case 'richtext': {
                const ta = document.createElement('textarea');
                ta.name = k;
                ta.className = 'dd-field-input dd-field-textarea';
                ta.rows = 4;
                ta.value = String(v || '');
                w.appendChild(ta);
                break;
            }
            case 'select': {
                const s = document.createElement('select');
                s.name = k;
                s.className = 'dd-field-input';
                if (f.options) {
                    Object.keys(f.options).forEach(function (ov) {
                        const o = document.createElement('option');
                        o.value = ov;
                        o.textContent = f.options[ov];
                        if (String(v) === String(ov)) o.selected = true;
                        s.appendChild(o);
                    });
                }
                w.appendChild(s);
                break;
            }
            case 'image': {
                const iw = document.createElement('div');
                iw.className = 'dd-image-field';
                
                const wrap = document.createElement('div');
                wrap.style.display = 'flex';
                wrap.style.gap = '8px';

                const ii = document.createElement('input');
                ii.type = 'text';
                ii.name = k;
                ii.className = 'dd-field-input';
                ii.style.flex = '1';
                ii.value = String(v || '');
                ii.placeholder = 'Image URL...';
                wrap.appendChild(ii);

                const ib = document.createElement('button');
                ib.type = 'button';
                ib.className = 'dd-btn-media dd-btn-sm';
                ib.textContent = 'Browse';
                ib.onclick = function () {
                    window.dispatchEvent(new CustomEvent('open-global-media', {
                        detail: {
                            callback: function(path, url) {
                                ii.value = path;
                                ip.src = getPreviewUrl(path, url);
                                ip.style.display = 'block';
                            }
                        }
                    }));
                };
                wrap.appendChild(ib);
                iw.appendChild(wrap);

                const ip = document.createElement('img');
                ip.className = 'dd-image-preview';
                ip.style.marginTop = '8px';
                if (v) { ip.src = getPreviewUrl(v); ip.style.display = 'block'; } else { ip.style.display = 'none'; }
                ii.addEventListener('input', function() {
                    ip.src = getPreviewUrl(ii.value);
                    ip.style.display = ii.value ? 'block' : 'none';
                });
                iw.appendChild(ip);
                w.appendChild(iw);
                break;
            }
            case 'gallery': {
                const gw = document.createElement('div');
                gw.className = 'dd-gallery-field';
                const gl = document.createElement('div');
                gl.className = 'dd-gallery-list';
                (Array.isArray(v) ? v : []).forEach(function (u) {
                    gl.appendChild(galItem(k, u));
                });
                gw.appendChild(gl);
                const ga = document.createElement('button');
                ga.type = 'button';
                ga.className = 'dd-btn-media dd-btn-sm';
                ga.textContent = '+ Add Image';
                ga.onclick = function () {
                    window.dispatchEvent(new CustomEvent('open-global-media', {
                        detail: {
                            callback: function(path, url) {
                                gl.appendChild(galItem(k, path, url));
                            }
                        }
                    }));
                };
                gw.appendChild(ga);
                w.appendChild(gw);
                break;
            }
            case 'repeater': {
                const rw = document.createElement('div');
                rw.className = 'dd-repeater-field';
                let sfArray = [];
                if (Array.isArray(f.subfields)) {
                    sfArray = f.subfields;
                } else if (f.subfields && typeof f.subfields === 'object') {
                    sfArray = Object.values(f.subfields);
                }
                const sf = sfArray;
                const items = Array.isArray(v) ? v : [];
                const rl = document.createElement('div');
                rl.className = 'dd-repeater-list';
                items.forEach(function (d) {
                    rl.appendChild(repRow(k, sf, d));
                });
                rw.appendChild(rl);
                const ra = document.createElement('button');
                ra.type = 'button';
                ra.className = 'dd-btn-media dd-btn-sm';
                ra.textContent = '+ Add Item';
                ra.onclick = function () {
                    rl.appendChild(repRow(k, sf, {}));
                };
                rw.appendChild(ra);
                w.appendChild(rw);
                break;
            }
            case 'product_categories': {
                const cw = document.createElement('div');
                cw.className = 'dd-categories-field';
                cw.style.display = 'grid';
                cw.style.gridTemplateColumns = 'repeat(2, minmax(0, 1fr))';
                cw.style.gap = '8px';
                cw.style.marginTop = '8px';
                
                let selectedIds = [];
                if (typeof v === 'string') {
                    selectedIds = v.split(',').map(id => id.trim()).filter(id => id);
                } else if (Array.isArray(v)) {
                    selectedIds = v.map(String);
                }
                
                const hid = document.createElement('input');
                hid.type = 'hidden';
                hid.name = k;
                hid.value = selectedIds.join(',');
                hid.className = 'dd-field-input';
                
                if (window.appProductCategories) {
                    window.appProductCategories.forEach(function(cat) {
                        const lbl = document.createElement('label');
                        lbl.className = 'dd-category-label';
                        lbl.style.display = 'flex';
                        lbl.style.alignItems = 'center';
                        lbl.style.gap = '8px';
                        lbl.style.fontSize = '14px';
                        lbl.style.cursor = 'pointer';
                        
                        const chk = document.createElement('input');
                        chk.type = 'checkbox';
                        chk.value = cat.id;
                        chk.checked = selectedIds.includes(String(cat.id));
                        chk.style.borderRadius = '4px';
                        chk.style.borderColor = '#d1d5db';
                        chk.style.color = '#4f46e5'; // brand color
                        
                        chk.addEventListener('change', function() {
                            if (this.checked) {
                                if (!selectedIds.includes(this.value)) selectedIds.push(this.value);
                            } else {
                                selectedIds = selectedIds.filter(id => id !== this.value);
                            }
                            hid.value = selectedIds.join(',');
                        });
                        
                        lbl.appendChild(chk);
                        lbl.appendChild(document.createTextNode(' ' + cat.name));
                        cw.appendChild(lbl);
                    });
                }
                
                w.appendChild(cw);
                w.appendChild(hid);
                break;
            }
            default: {
                const inp = document.createElement('input');
                inp.type = 'text';
                inp.name = k;
                inp.className = 'dd-field-input';
                inp.value = String(v || '');
                w.appendChild(inp);
            }
        }

        return w;
    }

    function galItem(k, u, explicitUrl) {
        const d = document.createElement('div');
        d.className = 'dd-gallery-item';
        const srcUrl = getPreviewUrl(u, explicitUrl);
        const safeU = String(u || '');
        const safeUrl = String(srcUrl || '');
        d.innerHTML =
            '<input type="hidden" name="' + k + '[]" value="' + safeU.replace(/"/g, '&quot;') + '">' +
            '<img src="' + safeUrl.replace(/"/g, '&quot;') + '" alt="" onerror="this.style.display=\'none\'">' +
            '<button type="button" class="dd-gallery-remove">&times;</button>';
        d.querySelector('.dd-gallery-remove').onclick = function () { d.remove(); };
        return d;
    }

    function repRow(k, sf, data) {
        const d = document.createElement('div');
        d.className = 'dd-repeater-item';
        d.dataset.repeater = k;

        let h = '';
        data = data || {};
        
        // Ensure sf is an array
        let sfArray = [];
        if (Array.isArray(sf)) {
            sfArray = sf;
        } else if (sf && typeof sf === 'object') {
            sfArray = Object.values(sf);
        }

        sfArray.forEach(function (f) {
            const v = data[f.key] !== undefined && data[f.key] !== null ? String(data[f.key]) : '';
            if (f.type === 'textarea') {
                h += '<textarea name="' + k + '[' + f.key + '][]" class="dd-field-input dd-repeater-input" placeholder="' + escapeAttr(f.label) + '">' + v.replace(/"/g, '&quot;') + '</textarea>';
            } else {
                h += '<input type="text" name="' + k + '[' + f.key + '][]" class="dd-field-input dd-repeater-input" value="' + v.replace(/"/g, '&quot;') + '" placeholder="' + escapeAttr(f.label) + '">';
            }
        });
        h += '<button type="button" class="dd-repeater-remove">&times;</button>';
        d.innerHTML = h;
        d.querySelector('.dd-repeater-remove').onclick = function () { d.remove(); };
        return d;
    }

    function escapeAttr(str) {
        return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function save() {
        const ta = document.getElementById('blocks-json');
        if (ta) {
            ta.value = JSON.stringify({ blocks: blocks });
            ta.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    window.DDBlockEditor = {
        addBlock: addBlock,
        openEditor: openEditor,
        closeEditor: closeEditor,
        save: save,
        getBlocks: function () { return blocks; },
        setBlocks: function (nb) { blocks = nb; renderAll(); }
    };
})();
