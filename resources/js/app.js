import FileUploader from './upload';
import Modal from './modal';
import DatatablesManager from './datatables-manager';

require('./bootstrap');
require('alpinejs');

new FileUploader({
    modal: new Modal()
});

new DatatablesManager();


