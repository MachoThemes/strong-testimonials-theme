(function($){

    $(document).ready(function(){
        $('.compare-plans-button').on('click', function(e) {
            e.preventDefault();
            $('.pricing-table--comparison').show();
            $("body,html").animate(
                { scrollTop: $('.pricing-table--comparison').offset().top }, 800 );
        });
    });


})(jQuery);