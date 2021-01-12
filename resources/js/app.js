import FileUploader from './upload';

require('./bootstrap');

require('alpinejs');
document.addEventListener('DOMContentLoaded', function () {
    const fileUploader = new FileUploader();
});
