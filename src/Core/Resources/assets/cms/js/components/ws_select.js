import aSelect from '../modules/a_select';

function init() {
  const { cmsTranslations } = window;
  if (cmsTranslations === undefined || cmsTranslations === null) {
    throw Error('No CMS Translations defined.');
  }

  const selectTranslations = cmsTranslations.ws_cms_components.select;
  const config = {
    loadingText: selectTranslations.loading,
    noResultsText: selectTranslations.no_results,
    noChoicesText: selectTranslations.no_choices,
    itemSelectText: selectTranslations.item_select,
    removeItems: true,
    removeItemButton: true,
  };

  document.querySelectorAll('[data-component="ws_select"]').forEach((elm) => {
    config.searchEnabled = elm.dataset.search ? elm.dataset.search : false;
    aSelect(elm, config);
  });
}

export default init;
