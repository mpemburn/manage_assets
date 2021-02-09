import FileUploader from './file-uploader';
import Modal from './modal';
import Permissions from './permissions';
import DatatablesManager from './datatables-manager';
import Inventory from "./inventory";
import Reports from "./reports";

let $ = require('jquery');
require('./bootstrap');
require('alpinejs');

let modal = new Modal();
new FileUploader({
    modal: modal
});

new Permissions({
    modal: modal
});

new Inventory({
    'DatatablesManager': new DatatablesManager()
});

new Reports({
    'DatatablesManager': new DatatablesManager()
});


