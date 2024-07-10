(function($) {

    var emageHover = function($scope, $) {

        $scope.find('.imghvr-anim-slide-content').each(function() {
            var self = $(this);
            var height = self.prop('scrollHeight') + 'px';
            self.closest('.imghvr').hover(function() { self.css('height', height); }, function() { self.css('height', 0); });
        });

    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/emage_hover_effects.default', emageHover);
    });

})(jQuery);