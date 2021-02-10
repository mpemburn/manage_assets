export default class Inventory {
    constructor(options) {
        if (options.dtManager) {
            options.dtManager.run('inventory-table', {
                pageLength: 250,
                lengthMenu: [ 10, 25, 50, 75, 100, 250 ]
            });
        }
    }
}
