import './bootstrap';

// Register richTextEditor on Livewire's Alpine (avoids "multiple instances" + "richTextEditor is not defined")
document.addEventListener('livewire:init', () => {
    window.Alpine.data('richTextEditor', () => ({
        savedRange: null,
        selectionHandler: null,
        sending: false,

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
            this.sending = true;
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
