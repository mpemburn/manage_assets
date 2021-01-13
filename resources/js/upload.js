export default class FileUploader {
    constructor() {
        this.dropper = document.getElementById("dropper");
        this.gallery = document.getElementById("gallery");
        this.overlay = document.getElementById("overlay");
        this.fileTempl = document.getElementById("file-template");
        this.imageTempl = document.getElementById("image-template");
        this.empty = document.getElementById("empty");
        this.hidden = document.getElementById("hidden-input");
        this.button = document.getElementById("button");
        this.submit = document.getElementById("submit");
        this.cancel = document.getElementById("cancel");
        this.xhr = new XMLHttpRequest();
        this.counter = 0;
        this.addEventListeners();

        // use to store selected files
        this.FILES = {};
    }

    addFile(target, file) {
        const isImage = file.type.match("image.*");
        const objectURL = URL.createObjectURL(file);

        const clone = isImage
            ? this.imageTempl.content.cloneNode(true)
            : this.fileTempl.content.cloneNode(true);

        clone.querySelector("h1").textContent = file.name;
        clone.querySelector("li").id = objectURL;
        clone.querySelector(".delete").dataset.target = objectURL;
        clone.querySelector(".size").textContent =
            file.size > 1024
                ? file.size > 1048576
                ? Math.round(file.size / 1048576) + " MB"
                : Math.round(file.size / 1024) + " KB"
                : file.size + " bytes";

        isImage &&
        Object.assign(clone.querySelector("img"), {
            src: objectURL,
            alt: file.name
        });

        this.empty.classList.add("hidden");
        target.prepend(clone);

        this.FILES[objectURL] = file;
    }

    hasFiles () {
        return ({dataTransfer: {types = []}}) => types.indexOf("Files") > -1;
    }

    addEventListeners() {
        let self = this;
        // Drag and drop events
        this.dropper.addEventListener('drop', function (evt) {
            evt.preventDefault();
            for (const file of evt.dataTransfer.files) {
                self.addFile(self.gallery, file);
                self.overlay.classList.remove("draggedover");
                self.counter = 0;
            }
        });
        this.dropper.addEventListener('dragover', function (evt) {
            if (self.hasFiles(evt)) {
                evt.preventDefault();
            }
        });
        this.dropper.addEventListener('dragenter', function (evt) {
            evt.preventDefault();
            if (!self.hasFiles(evt)) {
                return;
            }
            ++self.counter && self.overlay.classList.add("draggedover");
        });
        this.dropper.addEventListener('dragleave', function (e) {
            1 > --self.counter && self.overlay.classList.remove("draggedover");
        });

        // event delegation to caputre delete events
        // fron the waste buckets in the file preview cards
        this.gallery.onclick = ({target}) => {
            if (target.classList.contains("delete")) {
                const ou = target.dataset.target;
                document.getElementById(ou).remove(ou);

                self.gallery.children.length === 1 && self.empty.classList.remove("hidden");

                delete self.FILES[ou];
            }
        };

        this.hidden.onchange = (evt) => {
            for (const file of evt.target.files) {
                self.addFile(self.gallery, file);
            }
        };

        this.button.onclick = () => this.hidden.click();

        // Submit all selected files
        this.submit.onclick = () => {
            let formData = new FormData();
            for (let key in self.FILES) {
                if (self.FILES.hasOwnProperty(key)) {
                    formData.append('uploads[]', self.FILES[key]);
                }
            }
            self.xhr.open("POST", "/api/receive_files", true);
            self.xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
            self.xhr.send(formData);
        };

        // Clear entire selection
        this.cancel.onclick = () => {
            while (self.gallery.children.length > 0) {
                self.gallery.lastChild.remove();
            }
            self.FILES = {};
            self.empty.classList.remove("hidden");
            self.gallery.append(self.empty);
        };

        this.xhr.addEventListener('loadend', function (evt) {
            //alert('Nothing succeeds like success!');
        });

        this.xhr.addEventListener('error', function (evt) {

        });
    }
}

