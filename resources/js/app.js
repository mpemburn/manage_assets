import Comparator from "./comparator";
import RequestAjax from "./request-ajax";
import Modal from './modal';
import Confirmation from './confirmation';
import FileUploader from './file-uploader';
import PermissionsManager from './permissions-manager';
import UserRolesManager from './user-roles-manager';
import DatatablesManager from './datatables-manager';
import Inventory from "./inventory";
import Reports from "./reports";

let $ = require('jquery');
require('./bootstrap');
require('alpinejs');

let ajax = new RequestAjax();
let comparator = new Comparator();
let modal = new Modal();
let confirmation = new Confirmation();
let dtManager = new DatatablesManager();

new FileUploader({
    modal: modal
});

new PermissionsManager({
    comparator: comparator,
    ajax: ajax,
    modal: modal,
    confirmation: confirmation,
    dtManager: dtManager
});

new UserRolesManager({
    comparator: comparator,
    ajax: ajax,
    modal: modal,
    confirmation: confirmation,
    dtManager: dtManager
});

new Inventory({
    dtManager: dtManager
});

new Reports({
    dtManager: dtManager
});


