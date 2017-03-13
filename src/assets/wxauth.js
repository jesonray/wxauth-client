(function($){
    var form = $('#QRCodeLogin'),
        mod = form.find('.qrcode-mod'),
        msg = form.find('.qrcode-msg'),
        img = mod.find('img'),
        err = msg.find('.msg-err'),
        ok  = msg.find('.msg-ok'),
        loading = form.find('.qrcode-loading'),
        token = form.find('[name="token"]');

    var counter = {
            timer:'',
            interval:'',
            start: function(){
                this.stop();
                this.timer = setTimeout(function(){
                    counter.expired();
                }, 30000);
                this.interval = setInterval(function(){
                    form.trigger('query');
                }, 1000);
            },
            expired: function(){
                this.stop();
                form.trigger('error', ['expired']);
            },
            stop: function(){
                if(this.timer)clearTimeout(this.timer);
                if(this.interval)clearInterval(this.interval);
            }
        };

    form.find('.QRCodeRefresh').bind('click', function(){
        form.trigger('load');
    });

    form.bind('error', function(event, type, message){
        if( type=='expired' ) {
            err.find('.error-expired').show();
            err.find('.error-canceled').hide();
        } else {
            err.find('.error-expired').hide();
            err.find('.error-canceled').show();
        }
        mod.hide();
        msg.show();
        err.show();
        ok.hide();
    }).bind('scaned', function(){
        mod.hide();
        msg.show();
        ok.show();
        err.hide();
    }).bind('load', function(){
        img.hide();
        loading.show();
        mod.show();
        msg.hide();
        $.getJSON(img.attr('data-src')+'?_='+Date.now(), function(json){
            if( json.code!=200 ) {
                return form.trigger('error', ['error', json.message]);
            }
            console.log(json.data);
            token.val(json.data.task);
            loading.hide();
            img.attr('src', json.data.qrcode).show();
            counter.start();
        });
    }).bind('query', function(){
        $.getJSON(form.attr('data-src')+'?token='+token.val()+'&_='+Date.now(), function(json) {
            if( json.code!=200 ) {
                counter.stop();
                return form.trigger('error', ['error', json.message]);
            }
            if( json.data.status==1 ) {
                return form.trigger('scaned');
            } else if( json.data.status==2 ) {
                counter.stop();
                form.submit();
            } else if(json.data.status==3) {
                counter.stop();
                return form.trigger('error', ['error']);
            }
        });
    });

    form.trigger('load');
})(jQuery);