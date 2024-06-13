@props(['label', 'height' => 500])
    <script src="https://cdn.tiny.cloud/1/lj3niasi9s3d111t7rdmcli3m6lwbc6k3gs85xciou7c0oyn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <div
    {{ $attributes->whereDoesntStartWith('wire:model')->merge(['class'=>'form-group mb-10']) }}
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-init="
        tinymce.init({
            target: $refs.tinymce,
            themes: 'modern',
            height: {{ $height }},
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount',
                'image'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help' +
                'link image',
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: (cb, value, meta) => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
            
                input.addEventListener('change', (e) => {
                  const file = e.target.files[0];
            
                  const reader = new FileReader();
                  reader.addEventListener('load', () => {
                    /*
                      Note: Now we need to register the blob in TinyMCEs image blob
                      registry. In the next release this part hopefully won't be
                      necessary, as we are looking to handle it internally.
                    */
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
            
                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), { title: file.name });
                  });
                  reader.readAsDataURL(file);
                });
            
                input.click();
              },
            
            setup: function(editor) {
                editor.on('blur', function(e) {
                    value = editor.getContent()
                })

                editor.on('init', function (e) {
                    if (value != null) {
                        editor.setContent(value)
                    }
                })

                function putCursorToEnd() {
                    editor.selection.select(editor.getBody(), true);
                    editor.selection.collapse(false);
                }

                $watch('value', function (newValue) {
                    if (newValue !== editor.getContent()) {
                        editor.resetContent(newValue || '');
                        putCursorToEnd();
                    }
                });
            }
        })
    "
    wire:ignore
>
    <div>
        <label class="w-100" for="{{ $label }}">{{ $label }}</label>
        <input
            x-ref="tinymce"
            type="textarea"
        >
    </div>
</div>
