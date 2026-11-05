document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.spark-module-menu').forEach(function (menu, menuIndex) {
    const items = Array.from(menu.querySelectorAll('.menu-item-has-children'));
    const activeItems = menu.querySelectorAll('.current-menu-item, .current_page_item');
    const isFrench = menu.closest('[lang="fr"]') !== null;
    const expandLabel = isFrench ? 'Afficher ' : 'Expand ';
    const collapseLabel = isFrench ? 'Masquer ' : 'Collapse ';

    menu.classList.add('spark-module-menu-enhanced');

    function closeItem(item) {
      const panel = item.querySelector(':scope > .sub-menu');
      const toggle = item.querySelector(':scope > .spark-module-menu-toggle');
      if (panel && toggle) {
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', expandLabel + item.querySelector(':scope > a').textContent.trim());
      }

      item.querySelectorAll('.menu-item-has-children').forEach(function (nestedItem) {
        const nestedPanel = nestedItem.querySelector(':scope > .sub-menu');
        const nestedToggle = nestedItem.querySelector(':scope > .spark-module-menu-toggle');
        if (nestedPanel && nestedToggle) {
          nestedPanel.hidden = true;
          nestedToggle.setAttribute('aria-expanded', 'false');
          nestedToggle.setAttribute('aria-label', expandLabel + nestedItem.querySelector(':scope > a').textContent.trim());
        }
      });
    }

    items.forEach(function (item, itemIndex) {
      const link = item.querySelector(':scope > a');
      const panel = item.querySelector(':scope > .sub-menu');

      if (!link || !panel) {
        return;
      }

      const panelId = panel.id || item.id + '-submenu-' + menuIndex + '-' + itemIndex;
      const isActivePath = item.matches('.current-menu-parent, .current-menu-ancestor, .current_page_parent, .current_page_ancestor') ||
        item.matches('.current-menu-item, .current_page_item') ||
        Array.from(activeItems).some(function (activeItem) {
          return item.contains(activeItem);
        });
      const toggle = document.createElement('button');
      const label = link.textContent.trim();

      panel.id = panelId;
      panel.hidden = !isActivePath;
      item.classList.add('spark-module-menu-item');
      toggle.type = 'button';
      toggle.className = 'spark-module-menu-toggle';
      toggle.setAttribute('aria-controls', panelId);
      toggle.setAttribute('aria-expanded', isActivePath ? 'true' : 'false');
      toggle.setAttribute('aria-label', (isActivePath ? collapseLabel : expandLabel) + label);
      link.insertAdjacentElement('afterend', toggle);

      toggle.addEventListener('click', function () {
        const shouldOpen = panel.hidden;
        const siblings = Array.from(item.parentElement.children);

        siblings.forEach(function (sibling) {
          if (sibling === item) {
            return;
          }

          if (sibling.classList.contains('menu-item-has-children')) {
            closeItem(sibling);
          }
        });

        if (!shouldOpen) {
          closeItem(item);
        }
        panel.hidden = !shouldOpen;
        toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        toggle.setAttribute('aria-label', (shouldOpen ? collapseLabel : expandLabel) + label);
      });

      item.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !panel.hidden) {
          closeItem(item);
          toggle.focus();
        }
      });
    });
  });
});
