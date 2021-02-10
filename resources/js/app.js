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

let dtManager = new DatatablesManager();
new Permissions({
    modal: modal,
    dtManager: dtManager
});

new Inventory({
    dtManager: dtManager
});

new Reports({
    dtManager: dtManager
});


