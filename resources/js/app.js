import './bootstrap';

// Compose page: unsaved changes detection and save-to-draft on navigation
document.addEventListener('livewire:init', () => {
    window.Alpine.data('composePage', () => ({
        dirty: false,
        pendingHref: null,
        showModal: false,

        init() {
            window.addEventListener('beforeunload', (e) => {
                if (this.dirty) e.preventDefault();
            });
            document.addEventListener('click', (e) => this.handleLinkClick(e));
            this.$watch('dirty', () => {});
        },

        handleLinkClick(e) {
            const a = e.target.closest('a[href]');
            if (!a || !this.dirty) return;
            const href = a.getAttribute('href') || '';
            if (!href || href === '#' || href.startsWith('javascript:')) return;
            const url = new URL(href, window.location.origin);
            if (url.pathname === '/compose') return; // Stay on compose
            e.preventDefault();
            e.stopPropagation();
            this.pendingHref = href;
            this.showModal = true;
        },

        saveAndNavigate() {
            this.showModal = false;
            this.dirty = false;
            const editor = document.getElementById('compose-editor-body');
            const html = editor?.innerHTML ?? '';
            const href = this.pendingHref || '';
            const wire = this.$wire ?? (this.$el?.closest?.('[wire\\:id]') && window.Livewire?.find?.(this.$el.closest('[wire\\:id]').getAttribute('wire:id')));
            if (wire) wire.call('saveDraft', html, href);
            this.pendingHref = null;
        },

        discardAndNavigate() {
            this.showModal = false;
            this.dirty = false;
            const href = this.pendingHref;
            this.pendingHref = null;
            if (href) window.location.href = href;
        },

        cancelNavigation() {
            this.showModal = false;
            this.pendingHref = null;
        },
    }));
});

// Register richTextEditor on Livewire's Alpine (avoids "multiple instances" + "richTextEditor is not defined")
document.addEventListener('livewire:init', () => {
    window.Alpine.data('richTextEditor', (initialBody = '') => ({
        savedRange: null,
        selectionHandler: null,
        sending: false,

        init() {
            if (initialBody && this.$refs.editor && !this.$refs.editor.innerHTML.trim()) {
                this.$refs.editor.innerHTML = initialBody;
            }
            this.selectionHandler = () => {
                const editor = this.$refs?.editor;
                if (!editor) return;
                const sel = window.getSelection();
                if (sel.rangeCount > 0 && editor.contains(sel.anchorNode)) {
                    try {
                        this.savedRange = sel.getRangeAt(0).cloneRange();
                    } catch (e) {
                        this.savedRange = null;
                    }
                }
            };
            document.addEventListener('selectionchange', this.selectionHandler);
        },

        format(cmd) {
            const editor = this.$refs.editor;
            if (!editor) return;
            editor.focus();
            if (this.savedRange) {
                try {
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(this.savedRange);
                } catch (e) {
                    // Range may be invalid if DOM changed
                }
            }
            document.execCommand(cmd, false, null);
        },

        sendEmail() {
            this.sending = true;
            this.$dispatch('compose-saved');
            const editor = document.getElementById('compose-editor-body');
            const html = editor?.innerHTML ?? '';
            const wireEl = this.$el?.closest?.('[wire\\:id]');
            const id = wireEl?.getAttribute?.('wire:id');
            if (id && typeof window.Livewire !== 'undefined') {
                window.Livewire.find(id).call('sendWithBody', html);
            } else if (typeof this.$wire !== 'undefined') {
                this.$wire.call('sendWithBody', html);
            }
        },
    }));
});
