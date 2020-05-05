const loader = document.querySelector('.js-loader');
let elementBehind = null;

function show(element) {
  elementBehind = element;
  loader.classList.add('is-active');
  elementBehind.classList.add('no-scroll');
}

function hide() {
  loader.classList.remove('is-active');
  elementBehind.classList.remove('no-scroll');
}

export {
  show,
  hide,
};
