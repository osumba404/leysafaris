class AdminRichEditorUploadAdapter {
  constructor(loader, uploadUrl, folder, csrf) {
    this.loader = loader;
    this.uploadUrl = uploadUrl;
    this.folder = folder;
    this.csrf = csrf;
  }

  upload() {
    return this.loader.file.then((file) => {
      const body = new FormData();
      body.append('image', file);
      body.append('folder', this.folder);

      return fetch(this.uploadUrl, {
        method: 'POST',
        headers: this.csrf ? { 'X-CSRF-TOKEN': this.csrf } : {},
        body,
      })
        .then((response) => response.json().then((data) => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) {
            const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
            throw new Error(firstError || data.message || 'Image upload failed');
          }

          return { default: data.url };
        });
    });
  }

  abort() {}
}

function adminRichEditorUploadPlugin(uploadUrl, folder, csrf) {
  return function (editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) =>
      new AdminRichEditorUploadAdapter(loader, uploadUrl, folder, csrf);
  };
}

function initAdminRichEditors() {
  if (typeof ClassicEditor === 'undefined') return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  document.querySelectorAll('[data-rich-editor-wrap]').forEach((wrap) => {
    const mount = wrap.querySelector('[data-rich-editor]');
    const textarea = mount?.querySelector('textarea');
    const statsEl = wrap.querySelector('[data-rich-editor-stats]');
    const uploadUrl = wrap.dataset.uploadUrl;
    const folder = wrap.dataset.uploadFolder || 'blog';

    if (!mount || !textarea || textarea.dataset.editorReady === '1') return;

    ClassicEditor.create(textarea, {
      toolbar: {
        items: [
          'undo', 'redo',
          '|',
          'heading',
          '|',
          'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript',
          '|',
          'fontSize', 'fontColor', 'fontBackgroundColor',
          '|',
          'alignment',
          '|',
          'bulletedList', 'numberedList', 'outdent', 'indent',
          '|',
          'link', 'uploadImage', 'insertTable', 'blockQuote', 'horizontalLine', 'mediaEmbed',
          '|',
          'code', 'codeBlock',
          '|',
          'removeFormat',
          '|',
          'sourceEditing',
        ],
        shouldNotGroupWhenFull: true,
      },
      heading: {
        options: [
          { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
          { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
          { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
          { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
        ],
      },
      fontSize: {
        options: [10, 12, 14, 'default', 18, 22, 26, 32],
      },
      image: {
        toolbar: [
          'imageTextAlternative',
          '|',
          'imageStyle:inline',
          'imageStyle:block',
          'imageStyle:side',
          '|',
          'toggleImageCaption',
          'linkImage',
        ],
      },
      table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'],
      },
      link: {
        decorators: {
          openInNewTab: {
            mode: 'manual',
            label: 'Open in new tab',
            attributes: {
              target: '_blank',
              rel: 'noopener noreferrer',
            },
          },
        },
      },
      placeholder: 'Write your journal article…',
      extraPlugins: [adminRichEditorUploadPlugin(uploadUrl, folder, csrf)],
    })
      .then((editor) => {
        textarea.dataset.editorReady = '1';
        mount.ckeditorInstance = editor;

        const updateStats = () => {
          if (!statsEl) return;
          const text = editor.getData().replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
          const words = text ? text.split(' ').filter(Boolean).length : 0;
          const characters = text.length;
          statsEl.textContent = `${words} word${words === 1 ? '' : 's'} · ${characters} character${characters === 1 ? '' : 's'}`;
        };

        editor.model.document.on('change:data', updateStats);
        updateStats();

        const form = wrap.closest('form');
        form?.addEventListener('submit', () => {
          textarea.value = editor.getData();
        });
      })
      .catch((error) => {
        console.error('Rich editor failed to initialize:', error);
      });
  });
}

document.addEventListener('DOMContentLoaded', initAdminRichEditors);
