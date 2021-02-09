import FileUploader from './file-uploader';
import Modal from './modal';
import DatatablesManager from './datatables-manager';
import Inventory from "./inventory";
import Reports from "./reports";

let $ = require('jquery');
require('./bootstrap');
require('alpinejs');

new FileUploader({
    modal: new Modal()
});

new Inventory({
    'DatatablesManager': new DatatablesManager()
});

new Reports({
    'DatatablesManager': new DatatablesManager()
});


