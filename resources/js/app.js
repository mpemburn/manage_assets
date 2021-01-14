import FileUploader from './upload';
import Modal from './modal';

require('./bootstrap');

require('alpinejs');
document.addEventListener('DOMContentLoaded', function () {
    const modal = new Modal();
    const fileUploader = new FileUploader({modal: modal});
});
