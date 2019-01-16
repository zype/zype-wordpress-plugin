jQuery(document).ready(function ($) {

  $(document).on("change", "input[type='color']", function() {
    var selector = this.dataset.selector || this.id.replace('input', 'sandbox');
    changeSandboxColor(this.dataset.style, this.value, selector);
  });

  function changeSandboxColor(style, color, sandboxSelector) {
    if(sandboxSelector.includes(':')) {
      var style = ['<style>.', sandboxSelector, '{', style, ': ', color, ' !important; } </style>'].join('');
      $('head').append(style);
    }
    else {
      $('.' + sandboxSelector).css(style, color)
    }
  }

  $(document).on("click", "input.theme-selection[type='submit']", function(e) {
    $("#customize-ui input[name='theme']").val(e.currentTarget.id);
  });

});
