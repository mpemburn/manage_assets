import FileUploader from './upload';
import Modal from './modal';

require('./bootstrap');
require('alpinejs');

new FileUploader({
    modal: new Modal()
});
