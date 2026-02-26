import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('richTextEditor', () => ({
    savedRange: null,
    selectionHandler: null,

    init() {
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
        const editor = this.$refs?.editor;
        const html = editor?.innerHTML ?? '';
        document.getElementById('sendingModal').style.cssText = 'display:flex !important; position:fixed;';
        if (typeof this.$wire !== 'undefined') {
            this.$wire.call('sendWithBody', html);
        } else if (typeof window.Livewire !== 'undefined') {
            const wireEl = this.$el?.closest?.('[wire\\:id]');
            const id = wireEl?.getAttribute?.('wire:id');
            if (id) {
                window.Livewire.find(id).call('sendWithBody', html);
            }
        }
    },
}));

Alpine.start();
