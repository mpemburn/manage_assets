import FileUploader from './upload';
import Modal from './modal';
import Canvas from './canvas';

require('./bootstrap');
require('alpinejs');
require('mxgraph');

const modal = new Modal();
const fileUploader = new FileUploader({modal: modal});
const canvas = new Canvas();
