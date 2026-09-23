document.addEventListener('DOMContentLoaded', () => {
  initAdminImageFields();
  initAdminVideoFields();
  initAdminImageGalleries();
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

function initAdminVideoFields() {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  document.querySelectorAll('[data-video-field]').forEach((field) => {
    const pathInput = field.querySelector('[data-video-path]');
    const fileInput = field.querySelector('[data-video-file]');
    const previewWrap = field.querySelector('[data-video-preview]');
    const previewPlayer = field.querySelector('[data-video-preview-player]');
    const pathLabel = field.querySelector('[data-video-path-label]');
    const status = field.querySelector('[data-video-status]');
    const uploadUrl = field.dataset.uploadUrl;
    const folder = field.dataset.folder || 'heroes';

    if (!pathInput || !fileInput || !uploadUrl || fileInput.dataset.bound === '1') return;
    fileInput.dataset.bound = '1';

    fileInput.addEventListener('change', async () => {
      const file = fileInput.files?.[0];
      if (!file) return;

      status.textContent = 'Uploading…';
      status.className = 'admin-video-field__status is-uploading';

      const body = new FormData();
      body.append('video', file);
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
        if (previewPlayer) {
          previewPlayer.src = data.url;
          previewPlayer.hidden = false;
        }
        if (previewWrap) previewWrap.hidden = false;
        if (pathLabel) pathLabel.textContent = data.path;
        status.textContent = 'Upload complete';
        status.className = 'admin-video-field__status is-success';
        fileInput.value = '';
      } catch (error) {
        status.textContent = error.message || 'Upload failed. Try again.';
        status.className = 'admin-video-field__status is-error';
      }
    });
  });
}

function initAdminImageGalleries() {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  document.querySelectorAll('[data-image-gallery]').forEach((gallery) => {
    const items = gallery.querySelector('[data-gallery-items]');
    const template = gallery.querySelector('[data-gallery-row-template]');
    const uploadUrl = gallery.dataset.uploadUrl;
    const folder = gallery.dataset.folder || 'uploads';
    let rowCounter = items?.querySelectorAll('[data-image-gallery-row]').length || 0;

    gallery.querySelector('[data-gallery-add]')?.addEventListener('click', () => {
      if (!template || !items) return;

      const html = template.innerHTML.replace(/__INDEX__/g, String(rowCounter++));
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html.trim();
      const row = wrapper.firstElementChild;
      items.appendChild(row);
      bindGalleryRow(gallery, row, uploadUrl, folder, csrf);
      if (typeof lucide !== 'undefined') lucide.createIcons();
      row.querySelector('[data-image-file]')?.click();
    });

    gallery.querySelectorAll('[data-image-gallery-row]').forEach((row) => {
      bindGalleryRow(gallery, row, uploadUrl, folder, csrf);
    });

    gallery.addEventListener('click', (event) => {
      const removeBtn = event.target.closest('[data-gallery-remove]');
      if (!removeBtn) return;

      const row = removeBtn.closest('[data-image-gallery-row]');
      const rows = gallery.querySelectorAll('[data-image-gallery-row]');
      if (rows.length <= 1) {
        clearGalleryRow(row);
        return;
      }
      row.remove();
    });
  });
}

function clearGalleryRow(row) {
  const pathInput = row.querySelector('[data-image-path]');
  const previewWrap = row.querySelector('[data-image-preview]');
  const previewImg = row.querySelector('[data-image-preview-img]');
  const pathLabel = row.querySelector('[data-image-path-label]');
  const status = row.querySelector('[data-image-status]');
  const fileInput = row.querySelector('[data-image-file]');

  if (pathInput) pathInput.value = '';
  if (previewImg) {
    previewImg.src = '';
    previewImg.hidden = true;
  }
  if (previewWrap) previewWrap.hidden = true;
  if (pathLabel) pathLabel.textContent = '';
  if (status) status.textContent = '';
  if (fileInput) fileInput.value = '';
}

function bindGalleryRow(gallery, row, uploadUrl, folder, csrf) {
  const pathInput = row.querySelector('[data-image-path]');
  const fileInput = row.querySelector('[data-image-file]');
  const previewWrap = row.querySelector('[data-image-preview]');
  const previewImg = row.querySelector('[data-image-preview-img]');
  const pathLabel = row.querySelector('[data-image-path-label]');
  const status = row.querySelector('[data-image-status]');

  if (!pathInput || !fileInput || !uploadUrl || fileInput.dataset.bound === '1') return;
  fileInput.dataset.bound = '1';

  fileInput.addEventListener('change', async () => {
    const file = fileInput.files?.[0];
    if (!file) return;

    status.textContent = 'Uploading…';
    status.className = 'admin-image-gallery__status is-uploading';

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
      status.className = 'admin-image-gallery__status is-success';
      fileInput.value = '';
    } catch (error) {
      status.textContent = error.message || 'Upload failed. Try again.';
      status.className = 'admin-image-gallery__status is-error';
    }
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
    const mediaTypeSelect = form.querySelector('[data-hero-media-type]');
    if (mediaTypeSelect && data.media_type) {
      mediaTypeSelect.value = data.media_type;
      mediaTypeSelect.dispatchEvent(new Event('change'));
    }

    const videoPathInput = form.querySelector('[data-video-path]');
    const videoPreviewWrap = form.querySelector('[data-video-preview]');
    const videoPreviewPlayer = form.querySelector('[data-video-preview-player]');
    const videoPathLabel = form.querySelector('[data-video-path-label]');
    if (videoPathInput && data.video_path) {
      videoPathInput.value = data.video_path;
      if (videoPreviewPlayer) {
        videoPreviewPlayer.src = data._videoUrl || '';
        videoPreviewPlayer.hidden = !videoPreviewPlayer.src;
      }
      if (videoPreviewWrap) videoPreviewWrap.hidden = false;
      if (videoPathLabel) videoPathLabel.textContent = data.video_path;
    } else if (videoPathInput) {
      videoPathInput.value = '';
      if (videoPreviewWrap) videoPreviewWrap.hidden = true;
      if (videoPreviewPlayer) {
        videoPreviewPlayer.src = '';
        videoPreviewPlayer.hidden = true;
      }
      if (videoPathLabel) videoPathLabel.textContent = '';
    }

    const videoUrlInput = form.querySelector('[data-hero-video-url]');
    if (videoUrlInput) {
      videoUrlInput.value = data.video_url || '';
    }

    form.querySelectorAll('[data-image-path]').forEach((input) => {
      input.value = data.image || data.value || '';
    });
    form.querySelectorAll('[data-image-preview]').forEach((wrap) => {
      wrap.hidden = !(data.image || data._imageUrl);
    });
    form.querySelectorAll('[data-image-preview-img]').forEach((img) => {
      img.src = data._imageUrl || '';
      img.hidden = !img.src;
    });
    form.querySelectorAll('[data-image-path-label]').forEach((label) => {
      label.textContent = data.image || data.value || '';
    });

    if (pathInput && !data.image && !data._imageUrl) {
      pathInput.value = data.value || '';
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
