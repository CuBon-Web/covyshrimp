
import DropDown from './components/Dropdown.vue';
import Card from './components/layouts/Cards/Card.vue';
import fgInput from './components/layouts/Inputs/formGroupInput.vue';
import CrmPageHeader from './components/_common/crm_page_header.vue';
import CrmFilterBar from './components/_common/crm_filter_bar.vue';
import CrmFormSection from './components/_common/crm_form_section.vue';
import CrmConfirmDialog from './components/_common/crm_confirm_dialog.vue';
/**
 * You can register global components here and use them as a plugin in your main Vue instance
 */

const GlobalComponents = {
  install(Vue) {
    Vue.component('drop-down', DropDown);
    Vue.component('card', Card);
    Vue.component('fg-input', fgInput);
    Vue.component('crm-page-header', CrmPageHeader);
    Vue.component('crm-filter-bar', CrmFilterBar);
    Vue.component('crm-form-section', CrmFormSection);
    Vue.component('crm-confirm-dialog', CrmConfirmDialog);
  }
};

export default GlobalComponents;
