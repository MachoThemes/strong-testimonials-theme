<section class="promotion-section text-center" style="display:none">
    <span>Enjoy our <strong>Black Friday</strong> offer for <strong>Strong Testimonials</strong>, get <strong>30% OFF</strong>. The promotion ends in: </span><span class="timer"><span class="days"></span> : <span class="hours"></span> : <span class="minutes"></span> : <span class="seconds"></span></span>
</section>

<script>

    jQuery(function () {

        // don't need the cookie anymore
        /*function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }*/

        var $promotionSection = jQuery('.promotion-section');
        var $timerSpan = jQuery('.promotion-section').find('.timer');

        // don't need the cookie anymore
        //var $modulaDiscountCode = getCookie('modulaDiscountCodeExpires');

        /*if( ! $modulaDiscountCode )  {
           //set cookie
           var now = new Date().getTime();
           var exdate = new Date();
           exdate.setDate( exdate.getDate() + 1 );
           document.cookie = "modulaDiscountCodeExpires="+ exdate.getTime() + ';expires=' + exdate.toUTCString();
           $modulaDiscountCode = exdate.getTime();
       }*/

        $promotionSection.delay(1000).slideDown("medium");

        // Update the count down every 1 second
        var x = setInterval(function () {

            var now = new Date().getTime();
            var endDate = new Date(2019, 11, 2, 10, 00, 00, 0).getTime();

            //var distance = (6*60*60*1000) - (now - $modulaDiscountCode);

            var distance = endDate - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            /*$timerSpan.html( "<span>" + ('0' + days).slice(-2) + "</span>:" + ('0' + hours).slice(-2) + ":" + ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2) );*/
            $timerSpan.find('span.days').html(('0' + days).slice(-2));
            $timerSpan.find('span.hours').html(('0' + hours).slice(-2));
            $timerSpan.find('span.minutes').html(('0' + minutes).slice(-2));
            $timerSpan.find('span.seconds').html(('0' + seconds).slice(-2));

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $promotionSection.hide();
            }
        }, 1000);


    });

</script>