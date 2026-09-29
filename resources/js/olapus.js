/*
 * Bootstrap toast messages rendered from Laravel session flash data.
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-olapus-toast]').forEach((element) => {
        window.bootstrap.Toast.getOrCreateInstance(element).show();
    });
});

/*
 * Prevent accidental double submit on explicitly marked primary save buttons.
 * The class is placed on the submit button, not on the whole form, so other
 * submit actions (search, filters, publish, delete, etc.) remain untouched.
 */
document.addEventListener('submit', (event) => {
    const submitter = event.submitter;

    if (!submitter || !submitter.classList.contains('js-disable-on-submit')) {
        return;
    }

    window.setTimeout(() => {
        submitter.disabled = true;
    }, 0);
});



/*
 * Light / dark theme switch used from the user menu.
 * The choice is browser-local and does not require a user setting in DB.
 */
const applyOlapusTheme = (theme) => {
    document.documentElement.setAttribute('data-bs-theme', theme);

    document.querySelectorAll('[data-olapus-theme-icon]').forEach((icon) => {
        icon.classList.toggle('bi-moon-stars', theme !== 'dark');
        icon.classList.toggle('bi-sun', theme === 'dark');
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const storedTheme = localStorage.getItem('olapus-theme');
    const currentTheme = storedTheme === 'dark' ? 'dark' : 'light';

    applyOlapusTheme(currentTheme);

    document.querySelectorAll('[data-olapus-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark'
                ? 'light'
                : 'dark';

            localStorage.setItem('olapus-theme', nextTheme);
            applyOlapusTheme(nextTheme);
        });
    });
});
