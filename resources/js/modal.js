export default class Modal {
    constructor() {
        this.openmodal = $('.modal-open');
        this.body = $('body')
        this.modal = $('.modal')
        this.overlay = $('.modal-overlay')
        this.closemodal = $('.modal-close')

        if (typeof (this.modal) !== 'undefined' && this.modal !== null) {
            this.body.removeClass('modal-active');
            this.addEventListeners();
        }
    }

    toggleModal() {
        this.modal.toggleClass('opacity-0');
        this.modal.toggleClass('fixed');
        this.modal.toggleClass('pointer-events-none');
        this.body.toggleClass('modal-active');
    }

    addEventListeners() {
        let self = this;
        this.openmodal.on('click', function (evt) {
            evt.preventDefault();
            self.toggleModal();
        });

        this.overlay.on('click', function() {
            self.toggleModal();
        });

        this.closemodal.on('click', function() {
            self.toggleModal();
        });

        this.body.keydown(function (evt) {
            let isEscape = false
            if ('key' in evt) {
                isEscape = (evt.key === 'Escape' || evt.key === 'Esc')
            }
            if (isEscape && self.body.hasClass('modal-active')) {
                self.toggleModal();
            }
        });
    }
}
