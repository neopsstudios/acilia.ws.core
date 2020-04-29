import SimpleMDE from 'simplemde';
import { init as initMarkdownImage, handleImage } from './ws_markdown/ws_markdown_image';

const config = {
  status: false,
  autosave: {
    enabled: false,
  },
  spellChecker: false,
  previewRender: false,
  autoDownloadFontAwesome: false,
  hideIcons: ['image', 'side-by-side'],
  toolbar: [
    'bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', 'link', 'preview', 'fullscreen',
    {
      name: 'custom',
      action: function addImage(editor) {
        document.querySelector('[data-component="ws_markdown_image"]').click();
        handleImage().then((image) => {
          editor.codemirror.replaceSelection(`![${image.name}](${image.path})`);
        });
      },
      className: 'fa fa-image',
      title: 'custom',
    },
  ],
};

function createMarkdown(elm) {
  config.element = elm;
  return new SimpleMDE(config);
}

function init() {
  const { cmsTranslations } = window;

  if (cmsTranslations === undefined || cmsTranslations === null) {
    throw Error('No CMS Translations defined.');
  }

  document.querySelectorAll('[data-component="ws_markdown"]').forEach((elm) => {
    createMarkdown(elm);
  });

  initMarkdownImage();
}

export default init;
