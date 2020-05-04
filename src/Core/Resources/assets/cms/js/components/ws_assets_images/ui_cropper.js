import { init as initACropper, getCropperInstance, crop } from '../../modules/a_cropper';

const cropperIgnoreClasses = ':not(.cropper-u-hidden):not(.cropper-hidden)';
let modal = null;

function getComponentConfig(elmId, ratio) {
  return {
    preview: document.querySelector(`[data-id="${elmId}"] .ws-cropper_preview`),
    aspectRatio: ratio,
    viewMode: 1,
    cropBoxResizable: true,
    rotatable: false,
    scalable: false,
    zoomable: false,
    background: false,
    zoomOnTouch: false,
    zoomOnWheel: false,
    wheelZoomRatio: false,
  };
}

function cancelCrop(event) {
  const dataId = event.currentTarget.dataset.id;
  document.getElementById(dataId).value = '';
  document.querySelector(`.ws-cropper_modal[data-id="${dataId}"] .ws-cropper_crop img`).src = '';
  document.querySelector(`.ws-cropper_modal[data-id="${dataId}"]`).dataset.croppIndex = 0;
  modal.close();
}

function saveCrop(id) {
  const fieldData = getCropperInstance(id);
  const hiddenFields = id.replace('asset', 'cropper_');
  fieldData.config.forEach((config) => {
    const idSelector = `${hiddenFields}${config.ratio.replace(':', 'x')}`;
    const value = `${config.data.width};${config.data.height};${config.data.x};${config.data.y}`;
    document.getElementById(idSelector).value = value;
  });

  document.querySelectorAll(`[data-id="${id.replace('_asset', '')}"]`).forEach((elm) => {
    elm.classList.remove('u-hidden');
  });

  if (document.querySelector('.js-open-modal.js-not-on-preview')) {
    document.querySelector('.js-open-modal.js-not-on-preview').classList.add('u-hidden');
  }

  document.querySelectorAll(`[data-id="${id.replace('_asset', '')}"]`).forEach((elm) => {
    if (elm.querySelector('img')) {
      elm.querySelector('img').src = fieldData.cropper.getCroppedCanvas().toDataURL();
    } else if (elm.querySelector('.c-img-upload__wrapper-img')) {
      elm.querySelector('.c-img-upload__wrapper-img').insertAdjacentHTML(
        'afterbegin',
        `<img class="c-img-upload__img" src="${fieldData.cropper.getCroppedCanvas().toDataURL()}">`,
      );
      elm.querySelector('.c-img-upload__wrapper-img').classList.remove('u-hidden');
    }
  });
  document.querySelector(`.ws-cropper_modal[data-id="${id}"] .ws-cropper_crop img`).src = '';
  modal.close();
}

function setPreview(elementId, src) {
  const selector = `.ws-cropper_modal[data-id="${elementId}"] .ws-cropper_crop img`;
  document.querySelector(selector).src = src;
}

function checkCropSize(event) {
  const parent = event.currentTarget.closest('.ws-cropper_modal');
  // get the current index
  const index = parseInt(parent.dataset.croppIndex, 10) - 1;
  const { minimums } = getCropperInstance(parent.dataset.id).config[index];
  const { width, height } = event.detail;

  if (width < minimums.width || height < minimums.height) {
    getCropperInstance(parent.dataset.id).cropper.setData({
      width: Math.max(minimums.width, width),
      height: Math.max(minimums.height, height),
    });
  }
}

function showCropper(elm, cropperIndex) {
  const fieldData = getCropperInstance(elm.id);
  const croppersConfig = fieldData.config;
  const cropperConfig = croppersConfig[cropperIndex];
  const imageSelector = `.ws-cropper_modal[data-id="${elm.id}"] img${cropperIgnoreClasses}`;
  const cropperSelector = `.ws-cropper_modal[data-id="${elm.id}"]`;
  const config = getComponentConfig(elm.id, cropperConfig.ratioValue);

  document.querySelector(`${cropperSelector} .ws-cropper_crop`).classList.add('u-hidden');
  if (fieldData.cropper !== null) {
    document.querySelector(imageSelector).removeEventListener('crop', checkCropSize);
    fieldData.cropper.destroy();
  }

  const ratio = cropperConfig.ratio.replace('_', ':');
  const image = document.querySelector(imageSelector);
  fieldData.cropper = crop(image, config);

  image.addEventListener('crop', checkCropSize);
  document.querySelector(`${cropperSelector} .ws-cropper_crop`).classList.remove('u-hidden');
  document.querySelector(`${cropperSelector} .ws-cropper_details_ratio`).innerText = ratio;
  document.querySelector(`${cropperSelector} .ws-cropper_details_min_w`).innerText = cropperConfig.minimums.width;
  document.querySelector(`${cropperSelector} .ws-cropper_details_min_h`).innerText = cropperConfig.minimums.height;

  if (croppersConfig.length > (cropperIndex + 1)) {
    document.querySelector(`${cropperSelector} .ws-cropper_save`).style.display = 'none';
    document.querySelector(`${cropperSelector} .ws-cropper_next`).style.display = 'inline-block';
  } else {
    document.querySelector(`${cropperSelector} .ws-cropper_save`).style.display = 'inline-block';
    document.querySelector(`${cropperSelector} .ws-cropper_next`).style.display = 'none';
  }
}

function checkImagesSizes(elm, cropperIndex) {
  const { cmsTranslations } = window;
  const errorMessage = cmsTranslations.ws_cms_components.cropper.error;
  const cropper = getCropperInstance(elm.id);

  const image = document.querySelector(`.ws-cropper_modal[data-id="${elm.id}"] img${cropperIgnoreClasses}`);
  if (image.width === 0 && image.height === 0) {
    return setTimeout(() => checkImagesSizes(elm, cropperIndex), 100);
  }

  const minimums = JSON.parse(elm.dataset.minimums);

  Object.entries(minimums).forEach((min) => {
    if (min[1] !== undefined && min[1].height !== undefined && min[1].width !== undefined) {
      if (image.naturalHeight < min[1].height || image.naturalWidth < min[1].width) {
        getCropperInstance(elm.id).cropper.disabled = true;

        const msg = errorMessage.concat(`${min[1].width} x ${min[1].height}`);
        document.querySelector(`.ws-cropper_modal[data-id="${elm.id}"] .ws-cropper_details_obs`).innerText = msg;
        document.querySelectorAll(`.ws-cropper_confirm[data-id="${elm.id}"]`).forEach((input) => {
          input.classList.add('u-hidden');
        });
        cropper.cropper.clear();
      }
    }
  });

  return true;
}

function nextCrop(event) {
  const { id } = event.currentTarget.dataset;
  const index = parseInt(document.querySelector(`.ws-cropper_modal[data-id="${id}"]`).dataset.croppIndex, 10);

  const fieldData = getCropperInstance(id);
  const cropperConfig = fieldData.config[index - 1];
  cropperConfig.data = fieldData.cropper.getData();

  document.querySelector(`.ws-cropper_modal[data-id="${id}"]`).dataset.croppIndex = index + 1;

  if (event.currentTarget.classList.contains('ws-cropper_next')) {
    showCropper({ id }, index);
  } else if (event.currentTarget.classList.contains('ws-cropper_save')) {
    saveCrop(id);
  }
}

function initCropper(event) {
  const id = event.currentTarget.id || event.currentTarget.dataset.id;
  const elm = document.querySelector(`#${id}[data-component="ws_cropper"]`);
  const modalCroppper = document.querySelector(`.ws-cropper_modal[data-id="${elm.id}"]`);

  if (elm.files && elm.files.length) {
    setPreview(elm.id, window.URL.createObjectURL(elm.files[0]));
  } else if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length) {
    elm.files = event.dataTransfer.files;
    setPreview(elm.id, window.URL.createObjectURL(event.dataTransfer.files[0]));
  } else if (event.currentTarget.dataset.imageUrl) {
    setPreview(elm.id, event.currentTarget.dataset.imageUrl);
    document.getElementById(`${id}_data`).value = event.currentTarget.dataset.imageId;
  }

  initACropper(elm);

  modalCroppper.querySelector('.ws-cropper_details_obs').innerText = '';
  modalCroppper.dataset.croppIndex = 1;

  document.querySelectorAll(`.ws-cropper_confirm[data-id="${elm.id}"]`).forEach(
    (input) => input.classList.remove('u-hidden'),
  );

  showCropper(elm, 0);

  if (elm.dataset.displayMode === 'list') {
    modal.refresh(`.ws-cropper_modal[data-id="${elm.id}"]`);
  } else {
    modal.open(`.ws-cropper_modal[data-id="${elm.id}"]`);
  }

  checkImagesSizes(elm, 0);
}

function init(assetElement, modalElement) {
  const { cmsTranslations } = window;
  if (cmsTranslations === undefined || cmsTranslations === null) {
    throw Error('No CMS Translations defined.');
  }

  modal = modalElement;
  assetElement.addEventListener('change', initCropper);

  document.querySelectorAll(`[data-id="${assetElement.id}"] .ws-cropper_container .ws-cropper_cancel`).forEach((elm) => {
    elm.addEventListener('click', cancelCrop);
  });

  document.querySelectorAll(`[data-id="${assetElement.id}"] .ws-cropper_container .ws-cropper_confirm`).forEach(
    (elm) => {
      elm.addEventListener('click', nextCrop);
    },
  );
}

export {
  init,
  initCropper,
};
