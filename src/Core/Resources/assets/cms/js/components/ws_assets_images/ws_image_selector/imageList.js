import getNewElements from './getNewElements';
import { init as lazyLoadInit, update as lazyLoadUpdate } from '../../../modules/a_lazyload';
import { initCropper as showCropper } from '../ui_cropper';
import { show as showLoader, hide as hideLoader } from '../../ws_loader';

let imageListContainer = null;
let nextPage = 0;
let stillData = true;
let working = false;
let dataId = '';
let endpointUrl = window.cmsSettings.ws_cms_components.assets_images.endpoint;

function removeListElements(list) {
  list.querySelectorAll('.js-image-item').forEach((element) => {
    list.removeChild(element);
  });
}

function setNextPage(page) {
  nextPage = page;
}

function openCropper(event) {
  showCropper(event);
}

function useImage(event) {
  const { id } = event.currentTarget.closest('.js-img-selector-images-list').dataset;

  if (document.querySelector(`[data-id="${id.replace('_asset', '')}"] img`) !== null) {
    document.querySelector(`[data-id="${id.replace('_asset', '')}"] img`).src = event.currentTarget.dataset.imageUrl;
  }

  document.getElementById(`${id}_data`).value = event.currentTarget.dataset.imageId;
  document.querySelector('[data-id="image-selector"]').querySelector('#a-close').click();
}

function showElements(imageList) {
  const { id } = document.querySelector(`.js-image-selector-modal[data-id="${dataId}"]`).dataset;
  const imgTemplate = document.querySelector(`[data-id="${dataId}"].js-image-item`).outerHTML;

  if (imageList.length > 0) {
    imageList.forEach((element) => {
      imageListContainer.insertAdjacentHTML(
        'beforeend',
        imgTemplate
          .replace(/#image-alt/g, element.alt)
          .replace(/#image-thumb/g, element.thumb)
          .replace(/#id/g, id)
          .replace(/#image-id/g, element.id)
          .replace(/#image-original/g, element.image)
          .replace(/#extra-class/g, 'is-visible'),
      );

      imageListContainer.lastChild.querySelector('.js-list-image-crop').addEventListener('click', openCropper);
      imageListContainer.lastChild.querySelector('.js-list-image-use').addEventListener('click', useImage);
    });

    lazyLoadUpdate();
  } else {
    stillData = false;
    imageListContainer.insertAdjacentHTML(
      'beforeend',
      `<p class="js-no-more-images">${window.cmsTranslations.ws_cms_components.assets_images.no_results}</p>`,
    );
  }

  hideLoader();
  working = false;
}

function getElementsOnScroll() {
  const {
    y: yLastElement,
    height: heightLastElement,
  } = imageListContainer.lastElementChild.getBoundingClientRect();

  const {
    y: yContainer,
    height: heightContainer,
  } = imageListContainer.getBoundingClientRect();

  if (
    stillData
    && !working
    && !imageListContainer.lastElementChild.classList.contains('js-loader')
    && yLastElement + heightLastElement <= yContainer + heightContainer
  ) {
    working = true;
    showLoader(imageListContainer);

    if (document.querySelector('.js-search-form').dataset.queryString) {
      endpointUrl = `${endpointUrl}&${document.querySelector('.js-search-form').dataset.queryString}`;
    }

    getNewElements(endpointUrl.replace(/\?page=[^/]*$/, `?page=${nextPage}`)).then(showElements);
    setNextPage(nextPage += 1);
  }
}

function init(containerId = null) {
  if (containerId) {
    dataId = containerId;
    imageListContainer = document.querySelector(`.js-img-selector-images-list[data-id="${containerId}"]`);
    if (imageListContainer.querySelector('.js-no-more-images')) {
      imageListContainer.removeChild(imageListContainer.querySelector('.js-no-more-images'));
    }
    if (!stillData) {
      stillData = true;
    }
    nextPage = parseInt(imageListContainer.dataset.nextPage, 10);
    endpointUrl = `${endpointUrl}?page=1`;
    imageListContainer.addEventListener('scroll', getElementsOnScroll);
    removeListElements(imageListContainer);
    showLoader(imageListContainer);
    getNewElements(endpointUrl).then(showElements);
    lazyLoadInit();
  }
}

export {
  init,
  setNextPage,
  showElements,
  removeListElements,
};
