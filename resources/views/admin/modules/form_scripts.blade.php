@once
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pageLocale = document.documentElement.lang || 'cs';
    const pageLanguage = pageLocale.toLowerCase().split('-')[0];
    const invalidPhoneMessage = @json(trans('admin_module_user.invalid_phone'));

    // Populate ISO country selectors from the same country dataset used by intl-tel-input.
    document.querySelectorAll('select[data-country-select]').forEach(function (select) {
        if (!window.intlTelInput) return;

        const displayNames = typeof Intl.DisplayNames === 'function'
            ? new Intl.DisplayNames([pageLocale], { type: 'region' })
            : null;
        const selected = (select.dataset.selected || '').toUpperCase();
        const countries = window.intlTelInput.getAllCountries()
            .map(function (country) {
                const code = country.iso2.toUpperCase();
                return {
                    code: code,
                    name: displayNames?.of(code) || country.name || code,
                };
            })
            .sort(function (a, b) {
                return a.name.localeCompare(b.name, pageLocale, { sensitivity: 'base' });
            });

        countries.forEach(function (country) {
            const option = document.createElement('option');
            option.value = country.code;
            option.textContent = country.name;
            option.selected = country.code === selected;
            select.append(option);
        });
    });

    // The shared Vite bundle is loaded before DOMContentLoaded.
    document.querySelectorAll('select[data-enhance="select"]').forEach(function (select) {
        if (!window.TomSelect || select.tomselect) return;

        new window.TomSelect(select, {
            create: false,
            dropdownParent: select.closest('.table-responsive') ? 'body' : null,
            plugins: select.multiple ? ['remove_button'] : [],
        });
    });

    // International telephone inputs. Values are normalised to E.164 before submit.
    const phoneInputs = [];
    document.querySelectorAll('input[data-phone-input]').forEach(function (input) {
        if (!window.intlTelInput) return;

        let initialCountry = 'cz';
        if (input.name === 'billing_phone') {
            initialCountry = (document.querySelector('#billing_country')?.value || 'CZ').toLowerCase();
        } else if (input.name === 'shipping_phone') {
            initialCountry = (document.querySelector('#shipping_country')?.value || 'CZ').toLowerCase();
        }

        const iti = window.intlTelInput(input, {
            initialCountry: initialCountry,
            countryOrder: ['cz', 'sk', 'de', 'at', 'pl'],
            countryNameLocale: pageLocale,
            uiTranslations: window.intlTelInputUiTranslations?.[pageLanguage],
            separateDialCode: true,
            loadUtils: window.intlTelInputLoadUtils,
        });

        phoneInputs.push({ input: input, iti: iti });

        input.addEventListener('input', function () {
            input.setCustomValidity('');
        });
    });

    const validatePhone = async function (entry) {
        const input = entry.input;
        input.setCustomValidity('');

        if (input.value.trim() === '') {
            return true;
        }

        try {
            await entry.iti.promise;
        } catch (error) {
            input.setCustomValidity(input.dataset.invalidMessage || invalidPhoneMessage);
            return false;
        }

        if (!entry.iti.isValidNumber()) {
            input.setCustomValidity(input.dataset.invalidMessage || invalidPhoneMessage);
            return false;
        }

        input.value = entry.iti.getNumber();
        return true;
    };

    new Set(phoneInputs.map(function (entry) { return entry.input.form; }).filter(Boolean))
        .forEach(function (form) {
            form.addEventListener('submit', async function (event) {
                if (form.dataset.phoneValidationComplete === '1') {
                    delete form.dataset.phoneValidationComplete;
                    return;
                }

                event.preventDefault();
                const formPhones = phoneInputs.filter(function (entry) { return entry.input.form === form; });
                let firstInvalid = null;

                for (const entry of formPhones) {
                    if (!await validatePhone(entry) && firstInvalid === null) {
                        firstInvalid = entry.input;
                    }
                }

                if (firstInvalid) {
                    firstInvalid.reportValidity();
                    firstInvalid.focus();
                    return;
                }

                form.dataset.phoneValidationComplete = '1';
                if (event.submitter) {
                    form.requestSubmit(event.submitter);
                } else {
                    form.requestSubmit();
                }
            });
        });

    // Copy billing address to shipping address without coupling the stored values.
    document.querySelectorAll('[data-copy-billing-address]').forEach(function (button) {
        button.addEventListener('click', async function () {
            const fields = [
                'name',
                'contact_name',
                'street',
                'house_number',
                'city',
                'zip',
                'state',
                'country',
                'email',
            ];

            fields.forEach(function (field) {
                const source = document.querySelector('[name="billing_' + field + '"]');
                const target = document.querySelector('[name="shipping_' + field + '"]');
                if (!source || !target) return;

                if (target.tomselect) {
                    target.tomselect.setValue(source.value, true);
                } else {
                    target.value = source.value;
                    target.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            const sourcePhone = document.querySelector('[name="billing_phone"]');
            const targetPhone = document.querySelector('[name="shipping_phone"]');
            if (!sourcePhone || !targetPhone || !window.intlTelInput) return;

            const sourceIti = window.intlTelInput.getInstance(sourcePhone);
            const targetIti = window.intlTelInput.getInstance(targetPhone);
            if (!sourceIti || !targetIti) return;

            try {
                await sourceIti.promise;
                targetIti.setNumber(sourcePhone.value.trim() === '' ? '' : sourceIti.getNumber());
            } catch (error) {
                targetPhone.value = sourcePhone.value;
            }
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
