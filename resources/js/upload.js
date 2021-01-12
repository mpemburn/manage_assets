export default class FileUploader {
    constructor() {
        this.dropper = document.getElementById("dropper");
        this.gallery = document.getElementById("gallery");
        this.overlay = document.getElementById("overlay");
        this.fileTempl = document.getElementById("file-template");
        this.imageTempl = document.getElementById("image-template");
        this.empty = document.getElementById("empty");
        this.hidden = document.getElementById("hidden-input");
        this.counter = 0;
        this.hasFiles = ({dataTransfer: {types = []}}) =>
            types.indexOf("Files") > -1;
        this.addEventListeners();

        // use to store selected files
        this.FILES = {};
    }

    addFile(target, file) {
        const isImage = file.type.match("image.*"),
            objectURL = URL.createObjectURL(file);

        const clone = isImage
            ? this.imageTempl.content.cloneNode(true)
            : this.fileTempl.content.cloneNode(true);

        clone.querySelector("h1").textContent = file.name;
        clone.querySelector("li").id = objectURL;
        clone.querySelector(".delete").dataset.target = objectURL;
        clone.querySelector(".size").textContent =
            file.size > 1024
                ? file.size > 1048576
                ? Math.round(file.size / 1048576) + "mb"
                : Math.round(file.size / 1024) + "kb"
                : file.size + "b";

        isImage &&
        Object.assign(clone.querySelector("img"), {
            src: objectURL,
            alt: file.name
        });

        empty.classList.add("hidden");
        target.prepend(clone);

        this.FILES[objectURL] = file;
    }

    addEventListeners() {
        let self = this;
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
        document.getElementById("button").onclick = () => this.hidden.click();
        this.hidden.onchange = (evt) => {
            for (const file of evt.target.files) {
                self.addFile(self.gallery, file);
            }
        };
        // print all selected files
        document.getElementById("submit").onclick = () => {
            alert(`Submitted Files:\n${JSON.stringify(self.FILES)}`);
            console.log(self.FILES);
        };
        // clear entire selection
        document.getElementById("cancel").onclick = () => {
            while (self.gallery.children.length > 0) {
                self.gallery.lastChild.remove();
            }
            self.FILES = {};
            self.empty.classList.remove("hidden");
            self.gallery.append(self.empty);
        };
    }
}

