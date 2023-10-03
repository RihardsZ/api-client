(function ($) {
    const csscls = PhpDebugBar.utils.makecsscls('phpdebugbar-widgets-');

    const ApiWidget = PhpDebugBar.Widgets.ApiWidget = PhpDebugBar.Widget.extend({
        tagName: 'table',

        className: csscls('api'),

        initialize: function(options) {
            if (!options['itemRenderer']) {
                options['itemRenderer'] = this.itemRenderer;
            }

            this.set(options);

            const tHead = $('<thead />').appendTo(this.$el);
            const headerRow = $('<tr />').addClass(csscls('api-table-header')).appendTo(tHead);

            $('<th />').attr('title', 'Is Cached?').text('Cached').appendTo(headerRow);
            $('<th />').attr('title', 'Method').text('Method').appendTo(headerRow);
            $('<th />').attr('title', 'Service').text('Service').appendTo(headerRow);
            $('<th />').attr('title', 'Execution Time').text('Execution Time').appendTo(headerRow);
            $('<th />').attr('title', 'Start Time').text('Start Time').appendTo(headerRow);

            this.$tBody = $('<tbody />').appendTo(this.$el);
        },

        render: function() {
            this.bindAttr(['itemRenderer', 'data'], function() {
                this.$tBody.empty();
                if (!this.has('data')) {
                    return;
                }

                for (const item of this.get('data')) {
                    const tr = $('<tr />').addClass(csscls('api-table-row')).appendTo(this.$tBody);
                    this.get('itemRenderer')(tr, item);
                }
            });
        },

        /**
         * @param {jQuery} tr The <tr> element as a jQuery Object
         * @param {Object} entry An item from the data array
         */
        itemRenderer: function(tr, entry) {
            const cachedMark = entry.isCached ? '&check;' : '';
            $('<td />').attr('title', entry.isCached ? 'yes' : 'no').html(cachedMark).appendTo(tr);

            $('<td />').attr('title', entry.method).text(entry.method).appendTo(tr);
            $('<td />').attr('title', entry.service).text(entry.service).appendTo(tr);

            const executionTime = entry.isCached ? '' : `${entry.executionTime.toFixed(4)} s`;
            $('<td />').attr('title', executionTime).text(executionTime).appendTo(tr);

            $('<td />').attr('title', entry.startTime).text(entry.startTime).appendTo(tr);
        }
    });
})(PhpDebugBar.$);
