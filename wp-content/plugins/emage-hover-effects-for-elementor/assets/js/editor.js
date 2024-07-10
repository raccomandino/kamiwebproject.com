(function($) {

    var ControlRepeatSelectView = elementor.modules.controls.BaseData.extend({
        onReady: function() {
    
            var self = this;
            var select = self.$el.find('select');
            
            select.selectize({
                items: self.getControlValue(),
                plugins: ['remove_button'],
                mode: 'multi',
                hideSelected: false,
                duplicates: true,
                inputClass: 'selectize-input elementor-input-style',
                dropdownClass: 'selectize-dropdown select2-dropdown'
            });
    
        }
    
    } );
    
    elementor.addControlView('repeatselect', ControlRepeatSelectView);
    
    var ControlHtml5SortableView = elementor.modules.controls.BaseData.extend({
        onReady: function() {
    
            var self = this;
            var list = self.$el.find('.elementor-control-html5sortable');
    
            self.setValues(list);
            
            sortable(list)[0].addEventListener('sortupdate', function(e) {
                var value = self.getValues(e.detail.destination.items);
                self.setValue(value);
            });
    
        },
        getValues: function(items) {
            var arr = [];
            for (var i = 0; i < items.length; i++) {
                arr.push($(items[i]).attr('data-value'));
            }
            return arr;
        },
        setValues: function(list) {
    
            var values = this.getControlValue();
            var items = [];
    
            for (var i = 0; i < values.length; i++) {
                items.push($(list).find('[data-value=' + values[i] + ']')[0]);
            }
    
            list.empty().append(items);
        }
    
    } );
    
    elementor.addControlView('html5sortable', ControlHtml5SortableView);
    
    elementor.channels.editor.on('emage:editor:activate emage:editor:deactivate', function(event, model) {
    
        var button = event.$el.find('.elementor-button');
        var codeInput = event.$el.closest('#elementor-controls').find('input[data-setting="license_purchase_code"]');
        var licenseInput = event.$el.closest('#elementor-controls').find('input[data-setting="license"]');
        var noticeDiv = event.$el.closest('#elementor-controls').find('.elementor-control-license_notice').find(".elementor-control-raw-html");
    
        var eventdata = button.attr('data-event');
        var action = (eventdata.indexOf('deactivate') !== -1) ? 'deactivate' : 'activate';
    
        var data = {
            action: 'emage_license',
            license_action: action,
            code: codeInput.val()
        };
    
        button.addClass('elementor-button-state');
        button.find('.publish-label').hide();
    
        jQuery.post(emage.ajax_url, data,
            function(response) {
                button.removeClass('elementor-button-state');
                button.find('.publish-label').show();
                noticeDiv.html('<div style="color:#0b5885;background-color:#d0eeff;border-color:#bee7ff;padding: .75rem 1.25rem;border-radius: .25rem;line-height: 1.3em;border: 1px solid #bbcff5;">' + response.message + '</div>');
                licenseInput.val(response.license).trigger('input');
                if (response.license !== 'false') {
                    var obLicense = data.code.substr(0,8) + '-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
                    codeInput.val(obLicense).trigger('input');
                } else {
                    codeInput.val('').trigger('input');
                }
            }
        );
        
    });

})(jQuery);