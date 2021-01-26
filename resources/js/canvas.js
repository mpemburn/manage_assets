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
    mxRubberband
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
                //graph.insertVertex(parent, null, '', 20, 20, 80, 80, this.getSvg());
            }
            finally
            {
                // Updates the display
                graph.getModel().endUpdate();
            }
        }
    }
    getSvg () {
        return 'shape=image;image=data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNTEgNTAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxLjUgMSkiPjxwYXRoIGQ9Im00MS4zNiAyMi41NTJoLTIzLjk4NmMtLjMyMy0yLjk3OS0yLjc0LTUuMzAyLTUuNjY2LTUuMzAyLTMuMTQ2IDAtNS43MDUgMi42ODQtNS43MDUgNS45ODIgMCAzLjI5OSAyLjU1OSA1Ljk4MiA1LjcwNSA1Ljk4MiAyLjk1MSAwIDUuMzg2LTIuMzYxIDUuNjc2LTUuMzc2aDE3LjU0N3YzLjg1N2MwIC4zNTUuMjg3LjY0My42NDMuNjQzLjM1NSAwIC42NDMtLjI4Ny42NDMtLjY0M3YtMy44NTdoNC40OTlsLS4wMDkgNS43MDNjLS4wMDEuMzU1LjI4Ny43MjYuNjQyLjcyNi4zNTUgMCAuNjQ0LS4zNjguNjQ0LS43MjNsLjAxLTYuMzA2YzAtLjE3LS4wNjctLjM1NS0uMTg4LS40NzUtLjEyLS4xMjItLjI4NC0uMjEtLjQ1NS0uMjFtLTI5LjY1MiA1LjM3NmMtMi40MzYgMC00LjQxOS0yLjEwNy00LjQxOS00LjY5NiAwLTIuNTkgMS45ODMtNC42OTcgNC40MTktNC42OTcgMi40MzcgMCA0LjQyIDIuMTA3IDQuNDIgNC42OTcgMCAyLjU4OS0xLjk4MyA0LjY5Ni00LjQyIDQuNjk2IiBmaWxsPSIjN2E4OTk2Ii8+PGNpcmNsZSBzdHJva2U9IiM3YTg5OTYiIHN0cm9rZS13aWR0aD0iMiIgY3g9IjI0IiBjeT0iMjQiIHI9IjI0Ii8+PC9nPjwvc3ZnPg==';
    }
}
