function menubAction(event) {
  if (event.target.closest('.js-submenu-children')) {
    return true;
  }

  const { currentTarget } = event;
  const submenu = currentTarget.querySelector('ul.c-sidebar__submenu');
  if (submenu) {
    submenu.classList.toggle('collapse');
    currentTarget.classList.toggle('is-open');
  }

  return true;
}

function init() {
  document.querySelectorAll('.c-sidebar__list li.has-submenu').forEach((li) => {
    li.addEventListener('click', menubAction);
  });

  // if a subnav is active, we can't set the subnav class in the html using twig
  // then we have to open the subnav using js
  const linkActive = document.querySelector('ul.c-sidebar__submenu.collapse a.c-sidebar__link.is-active');
  if (linkActive) {
    linkActive.closest('ul.collapse').classList.remove('collapse');
  }
}

export default init;
