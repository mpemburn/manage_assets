export default class Reports {
    constructor(options) {
        if (options.DatatablesManager) {
            options.DatatablesManager.run('reports-table', {
                pageLength: 25,
                lengthMenu: [ 10, 25, 50, 75, 100, 250 ],
                order: [[ 1, "desc" ]]
            });
        }
    }
}
