(function ($) {
    Drupal.behaviors.pwTest = {
        attach: function(context, settings) {
          $('p').append('I see page wrappers.')
        }
    };
})(jQuery);
