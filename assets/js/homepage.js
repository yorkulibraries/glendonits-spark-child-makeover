document.addEventListener('DOMContentLoaded', function () {
  const menu = document.querySelector('.spark-primary-menu');
  let activeSubmenu = null;

  document.querySelectorAll('.spark-primary-menu > .menu-item-has-children').forEach(function (item, index) {
    const heading = item.querySelector(':scope > a');
    const submenu = item.querySelector(':scope > .sub-menu');

    if (!heading || !submenu) {
      return;
    }

    const panelId = 'spark-homepage-menu-' + (index + 1);
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'spark-primary-menu-toggle';
    toggle.setAttribute('data-bs-toggle', 'collapse');
    toggle.setAttribute('data-bs-target', '#' + panelId);
    toggle.setAttribute('aria-controls', panelId);
    toggle.setAttribute('aria-expanded', 'false');
    const indicator = document.createElement('span');
    indicator.className = 'spark-primary-menu-indicator';
    indicator.setAttribute('aria-hidden', 'true');
    indicator.textContent = '+';
    toggle.append(document.createTextNode(heading.textContent.trim()), indicator);

    submenu.id = panelId;
    submenu.classList.add('collapse');
    heading.replaceWith(toggle);

    submenu.addEventListener('show.bs.collapse', function () {
      activeSubmenu = submenu;
      menu.querySelectorAll(':scope > .menu-item-has-children > .sub-menu.show').forEach(function (openSubmenu) {
        if (openSubmenu !== submenu) {
          bootstrap.Collapse.getOrCreateInstance(openSubmenu, { toggle: false }).hide();
        }
      });

      toggle.setAttribute('aria-expanded', 'true');
      indicator.textContent = '−';
    });
    submenu.addEventListener('shown.bs.collapse', function () {
      if (activeSubmenu !== submenu) {
        bootstrap.Collapse.getOrCreateInstance(submenu, { toggle: false }).hide();
      }
    });
    submenu.addEventListener('hide.bs.collapse', function () {
      if (activeSubmenu === submenu) {
        activeSubmenu = null;
      }

      toggle.setAttribute('aria-expanded', 'false');
      indicator.textContent = '+';
    });
  });
});
