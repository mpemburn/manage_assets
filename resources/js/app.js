import RequestAjax from "./request-ajax";
import FileUploader from './file-uploader';
import Modal from './modal';
import PermissionsManager from './permissions-manager';
import UserRolesManager from './user-roles-manager';
import DatatablesManager from './datatables-manager';
import Inventory from "./inventory";
import Reports from "./reports";

let $ = require('jquery');
require('./bootstrap');
require('alpinejs');

let ajax = new RequestAjax();
let modal = new Modal();
let dtManager = new DatatablesManager();

new FileUploader({
    modal: modal
});

new PermissionsManager({
    modal: modal,
    dtManager: dtManager,
    ajax: ajax
});

new UserRolesManager({
    modal: modal,
    dtManager: dtManager,
    ajax: ajax
});

new Inventory({
    dtManager: dtManager
});

new Reports({
    dtManager: dtManager
});


