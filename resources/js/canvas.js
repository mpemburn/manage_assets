import mx from 'mxgraph';

const mxgraph = mx({
    mxImageBasePath: './src/images',
    mxBasePath: './src'
});

window.mxGraph = mxgraph.mxGraph;
window.mxGraphModel = mxgraph.mxGraphModel;
window.mxEvent = mxgraph.mxEvent;
window.mxEditor = mxgraph.mxEditor;
window.mxGeometry = mxgraph.mxGeometry;
window.mxRubberband = mxgraph.mxRubberband;
window.mxDefaultKeyHandler = mxgraph.mxDefaultKeyHandler;
window.mxDefaultPopupMenu = mxgraph.mxDefaultPopupMenu;
window.mxStylesheet = mxgraph.mxStylesheet;
window.mxDefaultToolbar = mxgraph.mxDefaultToolbar;

const {
    mxGraph,
    mxClient,
    mxEditor,
    mxEvent,
    mxDefaultToolbar,
    mxCodec,
    mxUtils,
    mxConstants,
    mxPerimeter,
    mxRubberband,
    mxSvgCanvas2D
} = mxgraph;

export default class Canvas {
    constructor(options) {
        let config = options.config;
        let editor = new mxEditor(config);
        let container = document.getElementById('graphContainer');
        if (typeof(mxClient) !== 'undefined') {
            this.draw(container, editor);
        }
    }

    draw (container, editor) {
        if (! mxClient.isBrowserSupported())
        {
            // Displays an error message if the browser is not supported.
            mxUtils.error('Browser is not supported!', 200, false);
        }
        else
        {
            let toolbar = new mxDefaultToolbar(container, editor);
            toolbar.addItem('Copy ', null, 'copy');
            toolbar.addItem('Paste ', null, 'paste');
            toolbar.addItem('Zoom In ', null, 'zoomIn');
            toolbar.addItem('Zoom Out ', null, 'zoomOut');

            // let combo = toolbar.addActionCombo('More actions...');
            // toolbar.addActionOption(combo, 'Paste', 'paste');

            // Disables the built-in context menu
            mxEvent.disableContextMenu(container);

            // Creates the graph inside the given container
            let graph = editor.graph;
            let model = graph.getModel();
            editor.setGraphContainer(container);

            // Enables rubberband selection
            new mxRubberband(graph);

            // Gets the default parent for inserting new cells. This
            // is normally the first child of the root (ie. layer 0).
            var parent = graph.getDefaultParent();

            // Adds cells to the model in a single step
            graph.getModel().beginUpdate();
            try
            {
                var v1 = graph.insertVertex(parent, null, 'Hello,', 20, 20, 80, 30);
                var v2 = graph.insertVertex(parent, null, 'World!', 200, 150, 80, 30);
                var e1 = graph.insertEdge(parent, null, '', v1, v2);
            }
            finally
            {
                // Updates the display
                graph.getModel().endUpdate();
            }
        }
    }
}
