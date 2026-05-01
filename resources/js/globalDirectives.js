import clickOutside from './core/directives/clickOutsite';

/**
 * You can register global directives here and use them as a plugin in your main Vue instance
 */

const GlobalDirectives = {
  install(Vue) {
    Vue.directive('click-outside', clickOutside);

    const normalizeNumberString = (value) => {
      if (value === null || value === undefined) {
        return '';
      }

      const stringValue = String(value).trim();
      if (!stringValue) {
        return '';
      }

      const isNegative = stringValue.startsWith('-');
      const cleanedValue = stringValue
        .replace(/\./g, '')
        .replace(',', '.')
        .replace(/[^0-9.]/g, '');

      const parts = cleanedValue.split('.');
      const integerPart = parts.shift() || '';
      const decimalPart = parts.join('');

      if (!integerPart && !decimalPart) {
        return '';
      }

      const normalized = decimalPart ? `${integerPart}.${decimalPart}` : integerPart;
      return isNegative ? `-${normalized}` : normalized;
    };

    const formatNumber = (value) => {
      const normalized = normalizeNumberString(value);
      if (!normalized) {
        return '';
      }

      const numericValue = Number(normalized);
      if (Number.isNaN(numericValue)) {
        return '';
      }

      return new Intl.NumberFormat('vi-VN', {
        maximumFractionDigits: 20
      }).format(numericValue);
    };

    const bindAutoNumberFormat = (rootEl) => {
      if (!rootEl || typeof rootEl.querySelectorAll !== 'function') {
        return;
      }

      const inputs = rootEl.querySelectorAll('input[type="number"]:not([data-auto-number-bound])');
      inputs.forEach((input) => {
        input.setAttribute('data-auto-number-bound', '1');
        input.setAttribute('autocomplete', 'off');
        input.setAttribute('inputmode', 'decimal');
        input.setAttribute('type', 'text');

        const handleInputCapture = () => {
          const normalized = normalizeNumberString(input.value);
          // Keep value raw for v-model/.number parser so data saved to DB is correct.
          input.value = normalized;
          requestAnimationFrame(() => {
            if (document.activeElement === input) {
              input.value = formatNumber(input.value);
            }
          });
        };
        const handleBlur = () => {
          // Let component/v-model finish its own blur update first, then re-apply formatting.
          requestAnimationFrame(() => {
            input.value = formatNumber(input.value);
          });
        };

        input.addEventListener('input', handleInputCapture, true);
        input.addEventListener('blur', handleBlur);
        input.addEventListener('change', handleBlur);
        input.__autoNumberHandlers__ = {
          handleInputCapture,
          handleBlur
        };

        input.value = formatNumber(input.value);
      });
    };

    const formatBoundNumberInputs = (rootEl) => {
      if (!rootEl || typeof rootEl.querySelectorAll !== 'function') {
        return;
      }

      const boundInputs = rootEl.querySelectorAll('input[data-auto-number-bound="1"]');
      boundInputs.forEach((input) => {
        if (document.activeElement === input) {
          return;
        }
        input.value = formatNumber(input.value);
      });
    };

    Vue.mixin({
      mounted() {
        bindAutoNumberFormat(this.$el);
        formatBoundNumberInputs(this.$el);
      },
      updated() {
        bindAutoNumberFormat(this.$el);
        formatBoundNumberInputs(this.$el);
      },
      beforeDestroy() {
        if (!this.$el || typeof this.$el.querySelectorAll !== 'function') {
          return;
        }

        const inputs = this.$el.querySelectorAll('input[data-auto-number-bound="1"]');
        inputs.forEach((input) => {
          if (!input.__autoNumberHandlers__) {
            return;
          }

          input.removeEventListener('input', input.__autoNumberHandlers__.handleInputCapture, true);
          input.removeEventListener('blur', input.__autoNumberHandlers__.handleBlur);
          input.removeEventListener('change', input.__autoNumberHandlers__.handleBlur);
          delete input.__autoNumberHandlers__;
          input.removeAttribute('data-auto-number-bound');
        });
      }
    });
  }
};

export default GlobalDirectives;
