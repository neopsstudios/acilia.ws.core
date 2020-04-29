function toggleDropdown(event) {
  const elm = event.currentTarget;
  const panel = document.querySelector(`.${elm.dataset.toggle}`);
  if (elm && panel) {
    elm.classList.toggle('show');
    panel.classList.toggle('show');
  }
}

function init() {
  document.querySelectorAll('[data-component="ws_dropdown"]').forEach((elm) => {
    elm.addEventListener('click', toggleDropdown);
  });
}

export default init;
