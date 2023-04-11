$(function(){
    $.widget("redmine.timecard", {

        options: {
            api_url: null,
            url: null,
            user_id: null,
            date: null
        },

        /**
         * Update the date that the widget is displaying and trigger a rerender of the widget data
         * 
         * @param {String} date The Date in YYYY-MM-DD format
         */
        updateDate: function (date) {
            this.options.date = date;
            if(this.__validate()){
                this.data_response = null;
                this.__updateData();
            }
        },

        /**
         * Update the information in the widget
         */
        updateData: function() {
            this.data_response = null;
            this.__updateData();
        },

        /**
         * constructor for the widget
         */
        _create: function() {
            let me = this,
                e = this.element;
            
            if(me.__validate()) {
                me.widgetFullName = this.eventNamespace.replace('.', '');
                me.data_response = null;
                $(e).addClass('col');
                me.__updateData();
            }
        },

        __updateData: function() {
            let me = this;
            $.ajax({
                url: me.options.api_url + '/time_entries.json',
                data: {
                    user_id: me.options.user_id,
                    from: me.options.date,
                    to: me.options.date,
                    limit: 100
                },
                method: 'GET',
                async: true,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    me.data_response = data;
                    me.__render();
                },
                error: function(xhr, status) {
                    if(status === 'timeout'){
                        me.__Error('Ajax Request Timeout');
                        return false;
                    }
                    if(status === 'error'){
                        me.__Error('Internal Server Error');
                        return false;
                    }
                    let error = JSON.parse(xhr.responseText);
                    let error_text = 'Invalid Response';
                    $.each(error.error, function(index, value){
                        if(typeof(value) !== 'object'){
                            error_text += value + '<br>';
                        } else {
                            if (typeof value.args[1] !== 'undefined'){
                                error_text += value.args[1] + '<br>';
                            }
                        }
                    });
                    me.__Error(error_text);
                }
            });
        },

        __render: function() {
            let me =  this,
                e = this.element,
                data = this.data_response;
            
            let total_count = data['total_count'];
            let total_time = 0.0;

            let table = '';

            e.empty(); // clear the element's children, we recreate instead of updating

            let dateparts = me.options.date.split('-');
            let theDate = new Date(dateparts[0], dateparts[1] - 1, dateparts[2]);
            let dow = theDate.toLocaleDateString(Intl.DateTimeFormat().resolvedOptions().locale, {weekday: 'long'});

            // Create the time table
            $.each(data['time_entries'], function(i, time_entry) {
                table = table + '<tr><td><a href="' + me.options.url +'issues/' +  time_entry['issue']['id'] + '" target="_blank">' + time_entry['project']['name'] + '</a></td><td>' + time_entry['activity']['name'] + '</td><td>' + time_entry['hours'] + '</td></tr>' + '\r\n';
                total_time += time_entry['hours'];
            });
            let total_time_display = (total_time.toFixed(1) === '0.0') ? 'N/A' : total_time.toFixed(1);
            table = table + '<tr><td colspan=2 class="fw-bold">TOTAL</td><td class="fw-bold">' + total_time_display + '</td></tr>' + '\r\n';

            table = '<table class="table table-sm table-striped"><thead><th>Project</th><th>Activity</th><th>Time</th></thead><tbody>' + table + '</tbody></table>';
            
            let headerclass = me.__isToday(theDate) ?  ' bg-warning' : ' border-info';

            let widget = $([
                    '<div class="card h-100">',
                        '<div class="card-header' + headerclass + '" title="' + theDate.toISOString().slice(0,10) + '">',
                            dow,
                            '<span class="badge bg-secondary">' + total_time_display + '</span>',
                        '</div>',
                        '<div class="card-body">',
                            table,
                        '</div>',
                        '<div class="card-footer">',
                        'Entries: ' + total_count,
                        '</div>',
                    '</div>',
            ].join('\r\n'));
            e.append(widget);

            me._trigger("change", null, total_time);
        },

        /**
         * Determine if the passed date is the same calendar date as today
         * 
         * @param {Date} date 
         * @returns 
         */
        __isToday: function(date) {
            const today = new Date();
            return date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear();
        },

        /**
         * validate the Widget Options
         * @returns {boolean}
         */
        __validate: function() {
            let me = this,
                o = this.options;

            if(o.user_id === null){
                me.__Error('A user_id must be set in the options');
                return false;
            }

            if(o.date === null){
                me.__Error('Date must be set for widget');
                return false;
            }

            if(o.date.match(/^\d{4}-\d{2}-\d{2}$/) === null) {
                me.__Error('Date is invalid: ' + o.date);
                return false;
            }

            return true;
        },

        /**
         * Error handling method for the widget
         *@private
         */
         __Error: function (message) {
            let me = this;
            let error_message = me.widgetFullName + ':' + message;
            let e = this.element;
            $(e).replaceWith('<div class="alert alert-danger"><strong>ALERT: </strong>' + error_message + '</div>');
            //clearInterval(me.update_interval);
        },
    });
});