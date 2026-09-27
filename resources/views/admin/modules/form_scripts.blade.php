@once
<script>
document.addEventListener('DOMContentLoaded', function () {
    // The shared Vite bundle is loaded before DOMContentLoaded.
    document.querySelectorAll('select[data-enhance="select"]').forEach(function (select) {
        if (!window.TomSelect || select.tomselect) return;

        new window.TomSelect(select, {
            create: false,
            dropdownParent: select.closest('.table-responsive') ? 'body' : null,
            plugins: select.multiple ? ['remove_button'] : [],
        });
    });

    // Keep server-side filtering; do not add a second table/pagination library.
    document.querySelectorAll('[data-submit-on-change]').forEach(function (select) {
        select.addEventListener('change', function () {
            select.form?.requestSubmit();
        });
    });

    // Only new records get automatic slugs. A manual URL is never overwritten.
    document.querySelectorAll('[data-slug-source]').forEach(function (target) {
        const source = document.querySelector(target.dataset.slugSource);
        if (!source) return;

        let manual = target.value !== '';
        target.addEventListener('input', function () { manual = true; });
        const update = function () {
            if (manual) return;
            target.value = source.value.normalize('NFKD').replace(/\p{M}/gu, '')
                .toLowerCase().replace(/\u00df/g, 'ss').replace(/\u0142/g, 'l')
                .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        };
        source.addEventListener('input', update);
        update();
    });

    // The original textarea remains the submitted HTML field.
    document.querySelectorAll('textarea[data-editor="html"]').forEach(function (textarea) {
        if (!window.Quill) return; // Plain HTML editing still works without JS.

        const host = document.createElement('div');
        textarea.before(host);
        const editor = new window.Quill(host, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }, { color: [] }, { background: [] }],
                    ['link', 'blockquote', 'code-block', 'clean'],
                ],
            },
        });
        editor.root.style.minHeight = '240px';
        editor.root.setAttribute('aria-label', textarea.labels?.[0]?.textContent.trim() || 'HTML');
        editor.root.setAttribute('aria-multiline', 'true');
        editor.setContents(editor.clipboard.convert({ html: textarea.value }), 'silent');
        textarea.classList.add('d-none');

        // Do not normalize stored HTML merely by opening and saving the form.
        editor.on('text-change', function (delta, previous, source) {
            if (source === 'user') {
                textarea.value = editor.getLength() > 1 ? editor.getSemanticHTML() : '';
            }
        });

        // Source mode keeps unsupported legacy markup editable without loss.
        const toolbar = editor.getModule('toolbar').container;
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'btn btn-sm btn-outline-secondary mt-2';
        toggle.textContent = 'HTML / Editor';
        toggle.setAttribute('aria-pressed', 'false');
        textarea.after(toggle);
        let sourceMode = false;
        toggle.addEventListener('click', function () {
            sourceMode = !sourceMode;
            if (!sourceMode) {
                editor.setContents(editor.clipboard.convert({ html: textarea.value }), 'silent');
            }
            host.classList.toggle('d-none', sourceMode);
            toolbar.classList.toggle('d-none', sourceMode);
            textarea.classList.toggle('d-none', !sourceMode);
            toggle.setAttribute('aria-pressed', String(sourceMode));
            if (sourceMode) textarea.focus();
            else editor.focus();
        });
    });
});
</script>
@endonce
