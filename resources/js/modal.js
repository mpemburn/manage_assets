export default class Modal {
    constructor() {
        this.openmodal = document.querySelectorAll('.modal-open')
        this.body = document.querySelector('body')
        this.modal = document.querySelector('.modal')
        this.overlay = document.querySelector('.modal-overlay')
        this.closemodal = document.querySelectorAll('.modal-close')

        this.addEventListeners();
    }

    toggleModal() {
        this.modal.classList.toggle('opacity-0')
        this.modal.classList.toggle('fixed')
        this.modal.classList.toggle('pointer-events-none')
        this.body.classList.toggle('modal-active')
    }

    addEventListeners() {
        let self = this;
        for (let i = 0; i < this.openmodal.length; i++) {
            this.openmodal[i].addEventListener('click', function (event) {
                event.preventDefault()
                self.toggleModal()
            })
        }
        this.overlay.addEventListener('click', this.toggleModal)
        for (let i = 0; i < this.closemodal.length; i++) {
            this.closemodal[i].addEventListener('click', self.toggleModal)
        }

        document.onkeydown = function (evt) {
            evt = evt || window.event
            let isEscape = false
            if ("key" in evt) {
                isEscape = (evt.key === "Escape" || evt.key === "Esc")
            } else {
                isEscape = (evt.keyCode === 27)
            }
            if (isEscape && document.body.classList.contains('modal-active')) {
                toggleModal()
            }
        };
    }
}
