/*
 * Bootstrap 5 JS
 */
import * as bootstrap from 'bootstrap';

/*
 * AdminLTE 4
 */
import * as AdminLTE from 'admin-lte';

/*
 * AdminLTE / dashboard libraries
 */
import { OverlayScrollbars } from 'overlayscrollbars';
import ApexCharts from 'apexcharts';
import jsVectorMap from 'jsvectormap';
import Sortable from 'sortablejs';

/*
 * Forms
 */
import flatpickr from 'flatpickr';
import TomSelect from 'tom-select';
import intlTelInput from 'intl-tel-input';
import { cs as intlTelInputCs } from 'intl-tel-input/locale';

/*
 * Tables
 */
import { TabulatorFull as Tabulator } from 'tabulator-tables';

/*
 * Rich text
 */
import Quill from 'quill';

/*
 * Calendar
 */
import { Calendar } from 'fullcalendar';

/*
 * Expose libraries globally.
 *
 * This is intentional during Olapus migration:
 * existing Blade files can continue using inline JavaScript.
 */
window.bootstrap = bootstrap;
window.AdminLTE = AdminLTE;

window.OverlayScrollbars = OverlayScrollbars;
window.ApexCharts = ApexCharts;
window.jsVectorMap = jsVectorMap;
window.Sortable = Sortable;

window.flatpickr = flatpickr;
window.TomSelect = TomSelect;
window.intlTelInput = intlTelInput;
window.intlTelInputLoadUtils = () => import('intl-tel-input/utils');
window.intlTelInputUiTranslations = {
    cs: intlTelInputCs,
};

window.Tabulator = Tabulator;
window.Quill = Quill;

window.FullCalendar = {
    Calendar,
};

/*
 * Olapus custom JavaScript.
 */
import './olapus.js';