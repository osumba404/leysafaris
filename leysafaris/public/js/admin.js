document.addEventListener('DOMContentLoaded', () => {
  initAdminImageFields();
  initAdminModals();
  initAdminSortable();
});

function initAdminImageFields() {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  document.querySelectorAll('[data-image-field]').forEach((field) => {
    const pathInput = field.querySelector('[data-image-path]');
    const fileInput = field.querySelector('[data-image-file]');
    const previewWrap = field.querySelector('[data-image-preview]');
    const previewImg = field.querySelector('[data-image-preview-img]');
    const pathLabel = field.querySelector('[data-image-path-label]');
    const status = field.querySelector('[data-image-status]');
    const uploadUrl = field.dataset.uploadUrl;
    const folder = field.dataset.folder || 'uploads';

    if (!pathInput || !fileInput || !uploadUrl) return;

    fileInput.addEventListener('change', async () => {
      const file = fileInput.files?.[0];
      if (!file) return;

      status.textContent = 'Uploading…';
      status.className = 'admin-image-field__status is-uploading';

      const body = new FormData();
      body.append('image', file);
      body.append('folder', folder);

      try {
        const response = await fetch(uploadUrl, {
          method: 'POST',
          headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {},
          body,
        });

        const data = await response.json();
        if (!response.ok) {
          const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
          throw new Error(firstError || data.message || 'Upload failed');
        }

        pathInput.value = data.path;
        if (previewImg) {
          previewImg.src = data.url;
          previewImg.hidden = false;
        }
        if (previewWrap) previewWrap.hidden = false;
        if (pathLabel) pathLabel.textContent = data.path;
        status.textContent = 'Upload complete';
        status.className = 'admin-image-field__status is-success';
        fileInput.value = '';
      } catch (error) {
        status.textContent = error.message || 'Upload failed. Try again.';
        status.className = 'admin-image-field__status is-error';
      }
    });
  });
}

function initAdminModals() {
  document.querySelectorAll('[data-admin-modal]').forEach((modal) => {
    const id = modal.id;
    if (!id) return;

    modal.querySelectorAll('[data-modal-close]').forEach((el) => {
      el.addEventListener('click', () => closeAdminModal(id));
    });
  });

  document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = trigger.dataset.modalOpen;
      if (target) openAdminModal(target);
    });
  });

  document.querySelectorAll('[data-modal-edit]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = trigger.dataset.modalEdit;
      const payload = trigger.dataset.item ? JSON.parse(trigger.dataset.item) : {};
      if (target) openAdminModal(target, payload);
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      document.querySelectorAll('.admin-modal.is-open').forEach((modal) => {
        closeAdminModal(modal.id);
      });
    }
  });
}

function openAdminModal(id, data = {}) {
  const modal = document.getElementById(id);
  if (!modal) return;

  const form = modal.querySelector('form[data-crud-form]');
  if (form) {
    form.reset();
    Object.entries(data).forEach(([key, value]) => {
      const input = form.querySelector(`[name="${key}"]`);
      if (!input) return;
      if (input.type === 'checkbox') {
        input.checked = Boolean(value);
      } else {
        input.value = value ?? '';
      }
    });

    const pathInput = form.querySelector('[data-image-path]');
    const previewWrap = form.querySelector('[data-image-preview]');
    const previewImg = form.querySelector('[data-image-preview-img]');
    const pathLabel = form.querySelector('[data-image-path-label]');
    if (pathInput && data.image) {
      pathInput.value = data.image;
      if (previewImg) {
        previewImg.src = data._imageUrl || '';
        previewImg.hidden = !previewImg.src;
      }
      if (previewWrap) previewWrap.hidden = false;
      if (pathLabel) pathLabel.textContent = data.image;
    } else if (pathInput) {
      pathInput.value = data.value || '';
      if (previewImg) {
        previewImg.src = data._imageUrl || '';
        previewImg.hidden = !previewImg.src;
      }
      if (previewWrap) previewWrap.hidden = !data._imageUrl;
      if (pathLabel) pathLabel.textContent = data.value || '';
    }

    if (data._type) {
      showSettingFieldType(modal, data._type);
      const activeField = modal.querySelector(`[data-setting-field="${data._type}"]`);
      const valueInput = activeField?.querySelector('[name="value"]:not([data-image-path])');
      if (valueInput && data.value !== undefined && data._type !== 'image') {
        valueInput.value = data.value ?? '';
      }
      if (data._hint) {
        const hint = activeField?.querySelector('[data-setting-hint]');
        if (hint) hint.textContent = data._hint;
      }
    } else if (pathInput && !data.image) {
      pathInput.value = '';
      if (previewWrap) previewWrap.hidden = true;
    }

    if (data._method) {
      let method = form.querySelector('input[name="_method"]');
      if (!method) {
        method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        form.appendChild(method);
      }
      method.value = data._method;
    } else {
      form.querySelector('input[name="_method"]')?.remove();
    }

    if (data._action) {
      form.action = data._action;
    }

    const title = modal.querySelector('[data-modal-title]');
    if (title && data._title) title.textContent = data._title;
  }

  modal.hidden = false;
  modal.classList.add('is-open');
  document.body.classList.add('admin-modal-open');

  if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeAdminModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove('is-open');
  modal.hidden = true;
  if (!document.querySelector('.admin-modal.is-open')) {
    document.body.classList.remove('admin-modal-open');
  }
}

window.openAdminModal = openAdminModal;
window.closeAdminModal = closeAdminModal;

function showSettingFieldType(modal, type) {
  modal.querySelectorAll('[data-setting-field]').forEach((el) => {
    const isActive = el.dataset.settingField === type;
    el.hidden = !isActive;
    el.querySelectorAll('input, textarea, select').forEach((input) => {
      input.disabled = !isActive;
    });
  });
}

function initAdminSortable() {
  document.querySelectorAll('[data-sortable]').forEach((tbody) => {
    const url = tbody.dataset.sortable;
    const groupScope = tbody.dataset.sortGroup || null;
    let draggedRow = null;

    tbody.querySelectorAll('tr[data-sort-id]').forEach((row) => {
      const handle = row.querySelector('.admin-sort-handle');
      if (!handle) return;

      handle.setAttribute('draggable', 'true');

      handle.addEventListener('dragstart', (event) => {
        draggedRow = row;
        row.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
      });

      row.addEventListener('dragend', () => {
        row.classList.remove('is-dragging');
        draggedRow = null;
        saveSortOrder(tbody, url, groupScope);
      });

      row.addEventListener('dragover', (event) => {
        event.preventDefault();
        if (!draggedRow || draggedRow === row) return;

        const rowGroup = row.dataset.sortGroup || null;
        const draggedGroup = draggedRow.dataset.sortGroup || null;
        if (groupScope && rowGroup !== groupScope) return;
        if (!groupScope && rowGroup && draggedGroup && rowGroup !== draggedGroup) return;

        const rect = row.getBoundingClientRect();
        const after = event.clientY > rect.top + rect.height / 2;
        tbody.insertBefore(draggedRow, after ? row.nextSibling : row);
      });
    });
  });
}

async function saveSortOrder(tbody, url, groupScope) {
  const rows = [...tbody.querySelectorAll('tr[data-sort-id]')];
  const ids = rows.map((row) => parseInt(row.dataset.sortId, 10)).filter(Boolean);
  if (!ids.length) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  const payload = { order: ids };
  if (groupScope) payload.group = groupScope;

  const card = tbody.closest('.admin-card');
  let status = card?.querySelector('[data-sort-status]');
  if (!status && card) {
    status = document.createElement('p');
    status.className = 'admin-sort-status';
    status.dataset.sortStatus = '';
    card.querySelector('.admin-card__header')?.appendChild(status);
  }

  try {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf || '',
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) throw new Error('Failed');

    if (status) {
      status.textContent = 'Order saved';
      status.classList.remove('is-error');
      status.classList.add('is-success');
      setTimeout(() => { status.textContent = ''; status.classList.remove('is-success'); }, 2000);
    }
  } catch {
    if (status) {
      status.textContent = 'Could not save order';
      status.classList.add('is-error');
    }
  }
}
