export default class FileUploader {
    constructor(options) {
        this.dropper = $('#dropper');
        this.gallery = $('#gallery');
        this.overlay = $('#overlay');
        this.fileTemplate = document.getElementById('file-template');
        this.imageTemplate = document.getElementById('image-template');
        this.empty = $('#empty');
        this.hidden = $('#hidden-input');
        this.button = $('#button');
        this.submit = $('#submit');
        this.cancel = $('#cancel');
        this.apiAction = $('#modal_form').attr('action');
        this.csrf = $('[name="_token');
        this.bToken = $('[name="b_token"]');
        this.xhr = new XMLHttpRequest();
        this.counter = 0;
        this.errorMessage = $('#file_uploader_error');
        // use to store selected files
        this.FILES = {};

        // Modal is added in app.js
        if (options.modal) {
            this.modal = options.modal;
        }

        if (typeof (this.dropper) !== 'undefined' && this.dropper !== null) {
            this.addEventListeners();
        }

    }

    addFile(target, file) {
        let isImage = file.type.match("image.*");
        let objectURL = URL.createObjectURL(file);
        let objectId = objectURL.substring(objectURL.length - 12, objectURL.length);
        let clone = isImage
            ? this.imageTemplate.content.cloneNode(true)
            : this.fileTemplate.content.cloneNode(true);

        clone.querySelector("h1").textContent = file.name;
        clone.querySelector("li").id = objectId;
        clone.querySelector("li").dataset.url = objectURL;
        clone.querySelector(".delete").dataset.target = objectId;
        clone.querySelector(".size").textContent = this.calculateFileSize(file.size);

        isImage &&
        Object.assign(clone.querySelector("img"), {
            src: objectURL,
            alt: file.name
        });

        this.empty.addClass('hidden');
        target.prepend(clone);

        this.FILES[objectURL] = file;
    }

    calculateFileSize(fileSize) {
        return fileSize > 1024
            ? fileSize > 1048576
                ? Math.round(fileSize / 1048576) + " MB"
                : Math.round(fileSize / 1024) + " KB"
            : fileSize + " bytes";
    }

    hasFiles() {
        return ({dataTransfer: {types = []}}) => types.indexOf("Files") > -1;
    }

    callAjax(data) {
        let self = this;

        $.ajax({
            url: this.apiAction,
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With':'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrf.val(),
                'Authorization': 'Bearer ' + this.bToken.val()
            },
            success: function (response) {
                self.modal.toggleModal();

                document.location.reload();
            },
            error: function (data) {
                self.errorMessage.html(data.responseJSON.error)
                    .removeClass('opacity-0')
                    .fadeOut(5000, function () {
                        $(this).addClass('opacity-0').show();
                    });
            }
        });
    }

    addEventListeners() {
        let self = this;
        // Drag and drop events
        this.dropper.on('drop', function (evt) {
            evt.preventDefault();
            evt.stopPropagation();
            for (const file of evt.originalEvent.dataTransfer.files) {
                self.addFile(self.gallery, file);
                self.overlay.removeClass('draggedover');
                self.counter = 0;
                0
            }
        });

        this.dropper.on('dragover', function (evt) {
            if (self.hasFiles(evt)) {
                evt.preventDefault();
            }
        });

        this.dropper.on('dragenter', function (evt) {
            evt.preventDefault();
            if (!self.hasFiles(evt)) {
                return;
            }
            ++self.counter && self.overlay.addClass('draggedover');
        });

        this.dropper.on('dragleave', function () {
            1 > --self.counter && self.overlay.removeClass('draggedover');
        });

        this.gallery.on('click', function ({target}) {
            if (target.classList.contains('delete')) {
                // Get the object representing the file by its data-target attribute
                let targetId = $(target).attr('data-target');
                let fileObject = $('#' + targetId);
                // Retrieve the URL
                let fileUrl = fileObject.attr('data-url');
                // Delete the object
                fileObject.remove();
                // Do something mysterious
                self.gallery.children().length === 1 && self.empty.removeClass('hidden');
                // Remove the file URL from the FILES collection
                delete self.FILES[fileUrl];
            }
        });

        this.hidden.on('change' , function (evt) {
            for (const file of evt.target.files) {
                self.addFile(self.gallery, file);
            }
        });

        this.button.on('click', function () {
            this.hidden.click();
        });

        // Submit all selected files
        this.submit.on('click', function () {
            let formData = new FormData();

            for (let key in self.FILES) {
                if (self.FILES.hasOwnProperty(key)) {
                    formData.append('uploads[]', self.FILES[key]);
                }
            }
            self.callAjax(formData);
        });

        // Clear entire selection
        this.cancel.onclick = () => {
            while (self.gallery.children.length > 0) {
                self.gallery.lastChild.remove();
            }
            self.FILES = {};
            self.empty.removeClass('hidden');
            self.gallery.append(self.empty);
        };
    }
}

