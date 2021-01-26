import FileUploader from './upload';
import Modal from './modal';
import Canvas from './canvas';

require('./bootstrap');
require('alpinejs');

const config = require('xml-loader!./keyhandler-commons.xml');

new FileUploader({
    modal: new Modal()
});

new Canvas({
    'config': config
});
