(function ($) {
    $(document).ready(function () {
       $('input[name="devvn_tragop_type"]').change(function () {
           var thisVal = $(this).val();
           $('#tragop_prod_wrap').removeClass('default yes no').addClass(thisVal);
       }); 
    });
})(jQuery);