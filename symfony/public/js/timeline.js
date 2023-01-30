$(function(){
    $.widget("redmine.timeline", {
        options: {
            day_of_week: null
        },

        __create: function() {
            let me = this;
            let default_time = '0.0';

            if(me.__validate()){
                me.updateTime(default_time);
            }
        },

        updateTime: function(time) {
            this.time = time;
            this.__updateTime();
        },

        __updateTime: function() {
            let me = this;
            let time = this.time;
            
            me.time_field.html(time);

            me.__trigger("change", null, time);
        },

        __validate: function() {
            let me = this, o = this.options;
            if(o.day_of_week < 0 || o.day_of_week > 6){
                me.__Error('Initialized with an invalid day of week ' + o.day_of_week + ', only 0-6 are valid');
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
        },
    });
});