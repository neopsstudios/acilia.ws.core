function loadImage(imageSrc) {
  return new Promise((resolve, reject) => {
    const img = document.createElement('img');
    img.src = imageSrc;
    img.onload = () => resolve(img);
    img.onerror = (e) => reject(e);
  });
}

export default async function checkImagesSizes(imageSrc, minimums) {
  try {
    const imageTag = await loadImage(imageSrc);
    const validatorData = { isValid: true };

    Object.entries(minimums).forEach((min) => {
    // The variable min is an array containing:
    // in position 0 the ratio of the image and in position 1 the height and width of the image
    // ex: ["16x9", {width: 1280, height: 720}]
      if (min[1] !== undefined && min[1].height !== undefined && min[1].width !== undefined) {
        if (imageTag.naturalHeight < min[1].height || imageTag.naturalWidth < min[1].width) {
          validatorData.isValid = false;
          validatorData.minHeight = min[1].height;
          validatorData.minWidth = min[1].width;
        }
      }
    });

    return validatorData;
  } catch (err) {
    const { currentTarget } = err;
    throw new Error(`Failed to load image for src: ${currentTarget.src}`);
  }
}
