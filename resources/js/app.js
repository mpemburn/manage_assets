import FileUploader from './upload';
import Modal from './modal';
import DiagramEditor from './diagram-editor';

require('./bootstrap');
require('alpinejs');
require('mxgraph-editor');

new FileUploader({
    modal: new Modal()
});

window.DiagramEditor = new DiagramEditor();
