// modules
import './modules/genericDelete';
import './modules/batchActions';
import { init as moduleNotifications } from './modules/a_notifications';

// components
import componentSlug from './components/ws_slug';
import componentSelect from './components/ws_select';
import componentMarkdown from './components/ws_markdown';
import componentDatePicker from './components/ws_datePicker';
import componentWidgetListModal from './components/ws_widget_list_modal';
import componentAssetsImage from './components/ws_assets_image';
import componentColorPicker from './components/ws_colorPicker';
import componentRangeSlider from './components/ws_rangeSlider';
import componentTooltip from './components/ws_tooltip';
import componentDropdown from './components/ws_dropdown';
import componentTableCollapse from './components/ws_table_collapse';

// controllers
import settingsCntrl from './controllers/settings';
import translationCntrl from './controllers/translation';
import sidebarCntrl from './controllers/sidebar';

sidebarCntrl();
moduleNotifications();
componentMarkdown();
componentDatePicker();
componentSlug();
componentSelect();
componentWidgetListModal();
componentAssetsImage();
componentColorPicker();
componentRangeSlider();
componentTooltip();
componentDropdown();
componentTableCollapse();

if (document.querySelector('[data-page="settings"]')) {
  settingsCntrl();
}

if (document.querySelector('[data-page="translation"]')) {
  translationCntrl();
}
