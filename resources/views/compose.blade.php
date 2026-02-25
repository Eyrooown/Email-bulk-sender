<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="w-full mx-auto sm:px-6 lg:px-8">

        {{-- Recipients --}}
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipients</p>
            <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-b border-base-300">
                    <span class="text-sm font-semibold text-gray-700">To:</span>
                    <button onclick="openCsvModal()" class="flex items-center gap-2 btn btn-xs btn-outline hover-clr-accent rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Import from CSV
                    </button>
                </div>
                {{-- Manual Input --}}
                <div class="px-4 py-3 border-b border-base-300">
                    <div class="flex items-center gap-2">
                        <input
                            type="email"
                            id="manualEmailInput"
                            placeholder="Type an email and press Enter or click Add..."
                            class="input input-sm input-bordered flex-1 focus:outline-none focus:border-base-300"
                            onkeydown="handleManualInputKey(event)"
                        />
                        <button onclick="addManualRecipient()" class="btn btn-sm clr-bg-accent text-white rounded-lg">Add</button>
                    </div>
                    <p id="manualInputError" class="text-xs text-red-500 mt-1 hidden">Please enter a valid email address.</p>
                </div>

                {{-- Chips --}}
                <div class="px-4 py-3 min-h-[52px]">
                    <div class="flex flex-wrap gap-2 items-center" id="recipientChips">
                        <span class="text-sm text-gray-300 italic" id="noRecipientsMsg">No recipients yet. Type an email above or import a CSV.</span>
                    </div>
                </div>
            </div>
        </div>

        <input type="text" id="subjectInput" placeholder="Subject..." class="input input-bordered w-full mb-4" />

        {{-- Compose Editor --}}
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Compose Message</p>
            <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden focus-within:border-red-500 focus-within:shadow-md transition-all">

                {{-- Toolbar --}}
                <div class="flex items-center flex-wrap gap-4 px-3 py-2 bg-base-100 border-b border-base-300">

                    <button onclick="fmt('bold')" class="btn btn-xs btn-ghost font-mono font-bold">B</button>
                    <button onclick="fmt('italic')" class="btn btn-xs btn-ghost font-mono italic">I</button>
                    <button onclick="fmt('underline')" class="btn btn-xs btn-ghost font-mono underline">U</button>
                    <button onclick="fmt('strikeThrough')" class="btn btn-xs btn-ghost font-mono line-through">S</button>

                    <div class="w-px h-5 bg-base-300 mx-1"></div>

                    <button onclick="fmt('insertUnorderedList')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
                    </button>
                    <button onclick="fmt('insertOrderedList')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/></svg>
                    </button>
                    <button onclick="fmt('formatBlock', 'blockquote')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/></svg>
                    </button>

                    <div class="w-px h-5 bg-base-300 mx-1"></div>

                    <button onclick="fmt('justifyLeft')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                    </button>
                    <button onclick="fmt('justifyCenter')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                    </button>
                    <button onclick="fmt('justifyRight')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
                    </button>

                    <div class="w-px h-5 bg-base-300 mx-1"></div>

                    <button onclick="fmt('undo')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.96"/></svg>
                    </button>
                    <button onclick="fmt('redo')" class="btn btn-xs btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.96"/></svg>
                    </button>
                </div>

                {{-- Editable Area --}}
                <div
                    id="editor"
                    contenteditable="true"
                    data-placeholder="Start typing your message here..."
                    oninput="updateWordCount()"
                    class="p-5 min-h-[240px] outline-none text-sm leading-relaxed text-gray-800"
                ></div>

                {{-- Attachment Chips --}}
                <div id="attachmentList" class="hidden flex-wrap gap-2 px-4 py-2 border-t border-base-300"></div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-t border-base-300">
                    <div class="flex items-center gap-3">
                        <span id="wordCount" class="text-xs text-gray-400 font-mono">0 words</span>
                        <div class="w-px h-4 bg-base-300"></div>
                        <button onclick="document.getElementById('fileInput').click()" class="btn btn-xs btn-ghost gap-1 hover-clr-accent">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                            Attach
                        </button>
                        <input type="file" id="fileInput" multiple class="hidden" onchange="handleFiles(this.files)" />
                        <span id="attachCount" class="hidden text-xs clr-accent font-mono"></span>
                    </div>
                    <button onclick="simulateSend()" class="btn btn-sm clr-bg-accent text-white rounded-lg gap-2 p-2">
                        <x-icons.send classes="w-3 h-3" />
                        Send
                    </button>
                </div>
            </div>
        </div>

        {{-- Recipients Table --}}
        <div id="recipientsTableSection" class="hidden mb-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipient List</p>
            <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-xs uppercase text-gray-400">#</th>
                            <th class="text-xs uppercase text-gray-400">Email Address</th>
                            <th class="text-xs uppercase text-gray-400">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="recipientsTableBody"></tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- CSV Modal --}}
    <div id="csvModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 bg-base-100 border-b border-base-300">
                <strong class="text-base text-gray-800">Import Recipients from CSV</strong>
                <button onclick="closeCsvModal()" class="btn btn-xs btn-ghost text-xl">×</button>
            </div>
            <div class="p-5">
                <div id="dropZone" onclick="document.getElementById('csvFileInput').click()"
                    ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)"
                    class="border-2 border-dashed border-base-300 rounded-xl p-8 text-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all mb-4">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="12" y2="12"/><line x1="15" y1="15" x2="12" y2="12"/></svg>
                    <p class="text-sm text-gray-500"><strong>Click to upload</strong> or drag & drop</p>
                    <span class="text-xs text-gray-300">CSV files only</span>
                </div>
                <input type="file" id="csvFileInput" accept=".csv" class="hidden" onchange="handleCsvFile(this.files[0])" />

                <div id="colSelector" class="hidden mb-4">
                    <label class="text-xs font-semibold text-gray-400 uppercase block mb-1">Which column contains emails?</label>
                    <select id="emailColSelect" onchange="previewRecipients()" class="select select-sm select-bordered w-full"></select>
                </div>

                <div class="overflow-x-auto">
                    <table id="csvPreviewTable" class="table table-xs hidden w-full mb-4">
                        <thead id="csvTableHead"></thead>
                        <tbody id="csvTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-5 py-3 bg-base-100 border-t border-base-300">
                <button onclick="closeCsvModal()" class="btn btn-sm btn-ghost">Cancel</button>
                <button id="btnImport" onclick="importRecipients()" class="btn btn-sm clr-bg-accent text-white hidden">Import</button>
            </div>
        </div>
    </div>

    {{-- Sending Overlay --}}
    <div id="overlay" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl px-10 py-8 text-center shadow-2xl">
            <div class="w-10 h-10 border-4 border-base-200 border-t-red-500 rounded-full animate-spin mx-auto mb-4"></div>
            <strong class="block text-lg text-gray-800 mb-1">Sending your email...</strong>
            <p class="text-sm text-gray-400">Please wait a moment</p>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-8 right-8 z-50 bg-gray-900 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 translate-y-20 opacity-0 transition-all duration-300">
        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <strong class="block text-sm mb-0.5">Email sent successfully!</strong>
            <span id="toastMeta" class="text-xs text-gray-400"></span>
        </div>
    </div>

    <script>
        let attachedFiles = [];
        let recipients = [];
        let csvRows = [];
        let csvHeaders = [];
        let showAllRecipients = false;

        function fmt(cmd, val) {
            document.execCommand(cmd, false, val || null);
            document.getElementById('editor').focus();
        }

        function formatBlock(tag) {
            document.execCommand('formatBlock', false, tag);
            document.getElementById('editor').focus();
        }

        function updateWordCount() {
            const text = document.getElementById('editor').innerText.trim();
            const words = text ? text.split(/\s+/).length : 0;
            document.getElementById('wordCount').textContent = words + (words === 1 ? ' word' : ' words');
        }

        function handleFiles(files) {
            Array.from(files).forEach(function(file) { attachedFiles.push(file); });
            renderAttachments();
        }

        function renderAttachments() {
            const list = document.getElementById('attachmentList');
            const countEl = document.getElementById('attachCount');
            if (attachedFiles.length === 0) {
                list.classList.add('hidden'); list.classList.remove('flex');
                list.innerHTML = '';
                countEl.classList.add('hidden');
                countEl.textContent = '';
                return;
            }
            list.classList.remove('hidden'); list.classList.add('flex');
            countEl.classList.remove('hidden');
            countEl.textContent = attachedFiles.length + ' file' + (attachedFiles.length > 1 ? 's' : '');
            list.innerHTML = '';
            attachedFiles.forEach(function(file, i) {
                const chip = document.createElement('div');
                chip.className = 'badge badge-outline gap-1 text-xs';
                chip.innerHTML = '📎 ' + file.name + ' <button onclick="removeFile(' + i + ')" class="ml-1 text-gray-400 hover:text-red-500">×</button>';
                list.appendChild(chip);
            });
        }

        function removeFile(index) {
            attachedFiles.splice(index, 1);
            renderAttachments();
        }

        function openCsvModal() {
            const modal = document.getElementById('csvModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCsvModal() {
            const modal = document.getElementById('csvModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            resetCsvModal();
        }

        function resetCsvModal() {
            csvRows = []; csvHeaders = [];
            document.getElementById('csvFileInput').value = '';
            document.getElementById('dropZone').style.display = 'block';
            document.getElementById('colSelector').classList.add('hidden');
            document.getElementById('csvPreviewTable').classList.add('hidden');
            document.getElementById('btnImport').classList.add('hidden');
            document.getElementById('emailColSelect').innerHTML = '<option value="">— Select column —</option>';
            document.getElementById('csvTableHead').innerHTML = '';
            document.getElementById('csvTableBody').innerHTML = '';
        }

        function onDragOver(e) { e.preventDefault(); document.getElementById('dropZone').classList.add('border-red-400', 'bg-red-50'); }
        function onDragLeave() { document.getElementById('dropZone').classList.remove('border-red-400', 'bg-red-50'); }
        function onDrop(e) {
            e.preventDefault();
            document.getElementById('dropZone').classList.remove('border-red-400', 'bg-red-50');
            const file = e.dataTransfer.files[0];
            if (file && file.name.endsWith('.csv')) parseCsv(file);
            else alert('Please drop a CSV file.');
        }

        function handleCsvFile(file) { if (file) parseCsv(file); }

        function parseCsv(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const lines = e.target.result.trim().split(/\r?\n/);
                if (lines.length < 2) { alert('CSV must have at least a header and one row.'); return; }
                csvHeaders = lines[0].split(',').map(function(h) { return h.trim().replace(/^"|"$/g, ''); });
                csvRows = lines.slice(1).map(function(line) { return line.split(',').map(function(c) { return c.trim().replace(/^"|"$/g, ''); }); });

                const sel = document.getElementById('emailColSelect');
                sel.innerHTML = '<option value="">— Select column —</option>';
                csvHeaders.forEach(function(h, i) {
                    const opt = document.createElement('option');
                    opt.value = i; opt.textContent = h;
                    if (/email/i.test(h)) opt.selected = true;
                    sel.appendChild(opt);
                });

                document.getElementById('dropZone').style.display = 'none';
                document.getElementById('colSelector').classList.remove('hidden');

                const thead = document.getElementById('csvTableHead');
                const tbody = document.getElementById('csvTableBody');
                thead.innerHTML = '<tr>' + csvHeaders.map(function(h) { return '<th>' + h + '</th>'; }).join('') + '</tr>';
                tbody.innerHTML = '';
                csvRows.slice(0, 5).forEach(function(row) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = csvHeaders.map(function(_, i) { return '<td>' + (row[i] || '') + '</td>'; }).join('');
                    tbody.appendChild(tr);
                });
                if (csvRows.length > 5) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td colspan="' + csvHeaders.length + '" class="text-gray-400 italic text-xs">...and ' + (csvRows.length - 5) + ' more rows</td>';
                    tbody.appendChild(tr);
                }
                document.getElementById('csvPreviewTable').classList.remove('hidden');
                if (sel.value !== '') previewRecipients();
            };
            reader.readAsText(file);
        }

        function previewRecipients() {
            const colIndex = document.getElementById('emailColSelect').value;
            if (colIndex === '') { document.getElementById('btnImport').classList.add('hidden'); return; }
            const count = csvRows.filter(function(row) { return row[colIndex] && row[colIndex].includes('@'); }).length;
            const btn = document.getElementById('btnImport');
            btn.classList.remove('hidden');
            btn.textContent = 'Import ' + count + ' Recipient' + (count !== 1 ? 's' : '');
        }

        function importRecipients() {
            const colIndex = parseInt(document.getElementById('emailColSelect').value);
            const imported = csvRows.map(function(row) { return row[colIndex] ? row[colIndex].trim() : ''; }).filter(function(e) { return e && e.includes('@'); });
            imported.forEach(function(email) {
                if (!recipients.includes(email)) {
                    recipients.push(email);
                }
            });
            renderRecipients();
            renderRecipientsTable();
            closeCsvModal();
        }

        function renderRecipients() {
            const container = document.getElementById('recipientChips');
            const noMsg = document.getElementById('noRecipientsMsg');
            if (recipients.length === 0) {
                container.innerHTML = '';
                container.appendChild(noMsg);
                noMsg.style.display = 'inline';
                showAllRecipients = false;
                return;
            }
            noMsg.style.display = 'none';
            container.innerHTML = '';
            const limit = 4;
            const showAll = showAllRecipients || recipients.length <= limit;
            const visible = showAll ? recipients : recipients.slice(0, limit);
            visible.forEach(function(email, i) {
                const chip = document.createElement('div');
                chip.className = 'badge badge-info gap-1 text-xs py-3 px-2';
                chip.innerHTML = '<span class="w-4 h-4 rounded-full bg-blue-700 text-white text-[9px] font-bold flex items-center justify-center">' + email[0].toUpperCase() + '</span>' + email + '<button onclick="removeRecipient(' + i + ')" class="ml-1 hover:text-red-500">×</button>';
                container.appendChild(chip);
            });
            if (!showAll && recipients.length > limit) {
                const more = document.createElement('div');
                more.className = 'badge badge-ghost cursor-pointer text-xs py-3';
                more.textContent = '+' + (recipients.length - limit) + ' more';
                more.onclick = function() { showAllRecipients = true; renderRecipients(); };
                container.appendChild(more);
            }
            if (showAll && recipients.length > limit) {
                const collapse = document.createElement('div');
                collapse.className = 'badge badge-ghost cursor-pointer text-xs py-3';
                collapse.textContent = 'Show less';
                collapse.onclick = function() { showAllRecipients = false; renderRecipients(); };
                container.appendChild(collapse);
            }
        }

        function removeRecipientByEmail(email) {
            recipients = recipients.filter(function(e) { return e !== email; });
            renderRecipients();
            renderRecipientsTable();
        }

        function handleManualInputKey(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addManualRecipient();
    }
}

    function addManualRecipient() {
        const input = document.getElementById('manualEmailInput');
        const error = document.getElementById('manualInputError');
        const email = input.value.trim();

        // Basic email validation
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        if (!valid) {
            error.classList.remove('hidden');
            return;
        }

        // Check duplicate
        if (recipients.includes(email)) {
            error.textContent = 'This email is already added.';
            error.classList.remove('hidden');
            return;
        }

        error.classList.add('hidden');
        error.textContent = 'Please enter a valid email address.';
        recipients.push(email);
        input.value = '';
        renderRecipients();
        renderRecipientsTable();
    }

    function renderRecipientsTable() {
        const section = document.getElementById('recipientsTableSection');
        const tbody = document.getElementById('recipientsTableBody');
        if (recipients.length === 0) {
                section.classList.add('hidden');
                tbody.innerHTML = '';
                return;
            }
            section.classList.remove('hidden');
            tbody.innerHTML = '';
            recipients.forEach(function(email, i) {
                const tr = document.createElement('tr');
                tr.innerHTML =
                    '<td class="text-xs text-gray-400 font-mono">' + (i + 1) + '</td>' +
                    '<td><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">' + email[0].toUpperCase() + '</div>' + email + '</div></td>' +
                    '<td><span class="badge badge-success badge-sm">Pending</span></td>' +
                    '<td><button onclick="removeRecipientByEmail(\'' + email + '\')" class="btn btn-xs btn-ghost hover:text-red-500">×</button></td>';
                tbody.appendChild(tr);
            });
        }

        function simulateSend() {
            if (recipients.length === 0) { alert('Please add at least one recipient.'); return; }
            if (!document.getElementById('editor').innerText.trim()) { alert('Please write something before sending.'); return; }

            const overlay = document.getElementById('overlay');
            overlay.classList.remove('hidden'); overlay.classList.add('flex');

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('subject', document.getElementById('subjectInput') ? document.getElementById('subjectInput').value : '(No Subject)');
            formData.append('body', document.getElementById('editor').innerHTML);

            recipients.forEach(function(email) {
                formData.append('recipients[]', email);
            });

            attachedFiles.forEach(function(file) {
                formData.append('attachments[]', file);
            });

            fetch('{{ route("compose.store") }}', {
                method: 'POST',
                body: formData,
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                overlay.classList.add('hidden'); overlay.classList.remove('flex');

                if (data.success) {
                    const toast = document.getElementById('toast');
                    const fileCount = attachedFiles.length;
                    document.getElementById('toastMeta').textContent = 'Sent to ' + recipients.length + ' recipient' + (recipients.length > 1 ? 's' : '') + (fileCount > 0 ? ' · ' + fileCount + ' attachment' + (fileCount > 1 ? 's' : '') : '');
                    toast.classList.remove('translate-y-20', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');

                    document.getElementById('editor').innerHTML = '';
                    attachedFiles = []; recipients = [];
                    renderAttachments(); renderRecipients(); renderRecipientsTable(); updateWordCount();

                    setTimeout(function() {
                        toast.classList.add('translate-y-20', 'opacity-0');
                        toast.classList.remove('translate-y-0', 'opacity-100');
                    }, 4000);
                }
            })
            .catch(function() {
                overlay.classList.add('hidden'); overlay.classList.remove('flex');
                alert('Something went wrong. Please try again.');
            });
        }
    </script>

</x-app-layout>
