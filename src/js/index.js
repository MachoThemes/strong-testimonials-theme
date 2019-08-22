import Header from './modules/Header';
import TopBar from './modules/TopBar';
import Sidebar from './modules/Sidebar';
import Footer from './modules/Footer';
import Accordion from './modules/Accordion';
import DocSearch from './modules/DocSearch';
import Modal from './modules/Modal';

class ST {

	constructor(){
		this.initHeader();
		this.initTopBar();
		this.initScrollAnimation();
		this.initAccordions();
		this.initDocSearch();
		this.initModals();
		this.initSidebar();
		this.initFooter();
		this.initAccountPage();
		this.initPricingPage();
	}

	initHeader() {
		new Header( jQuery('.header') );
	}

	initTopBar() {
		new TopBar( jQuery('.topbar-section') );
	}

	initSidebar() {
		new Sidebar( jQuery('.post-sidebar') );
	}

	initFooter() {
		new Footer( jQuery('.footer-section') );
	}

	initAccountPage() {
		if( ! jQuery( 'body' ).hasClass( 'page-template-account' ) ) {
			return;
		}

		jQuery( '.edd-manage-license-back' ).attr('href', '/account');
	}

	initPricingPage() {
		if( ! jQuery( 'body' ).hasClass( 'page-template-pricing-ltd' ) ) {
			return;
		}

		let expires = new Date('Sep 5, 2019 18:00:00');

		let $daysCountdown = jQuery( '.hero-section__illustration' ).find('#number-1');
		let $hoursCountdown = jQuery( '.hero-section__illustration' ).find('#number-2');
		let $minutesCountdown = jQuery( '.hero-section__illustration' ).find('#number-3');
		let $secondsCountdown = jQuery( '.hero-section__illustration' ).find('#number-4');

		// Update the count down every 1 second
		let interval = setInterval(function() {

			let now = new Date().getTime();
			let distance = (6*60*60*1000) - (now - expires);

			// Time calculations for days, hours, minutes and seconds
			let days = Math.floor(distance / (1000 * 60 * 60 * 24));
			let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			let seconds = Math.floor((distance % (1000 * 60)) / 1000);

			$daysCountdown.html( ('0' + days ).slice(-2) );
			$hoursCountdown.html( ('0' + hours ).slice(-2) );
			$minutesCountdown.html( ('0' + minutes ).slice(-2) );
			$secondsCountdown.html( ('0' + seconds ).slice(-2) );

			// If the count down is finished, write some text
		 	if (distance < 0) {
				clearInterval( interval );
			}

		}, 1000);


	}

	initScrollAnimation() {

		jQuery( 'a[href*="#"]:not([href="#"])' ).on( 'click', function(e) {
			let target;
			if ( location.pathname.replace( /^\//, '' ) === this.pathname.replace( /^\//, '' ) && location.hostname === this.hostname ) {
				target = jQuery( this.hash );
				target = target.length ? target : jQuery( '[name=' + this.hash.slice( 1 ) + ']' );
				if ( target.length ) {
					e.preventDefault();
					jQuery( 'html, body' ).animate( { scrollTop: target.offset().top }, 1000, 'swing' );
				}
			}
		});
	}

	initAccordions( $elements = jQuery(".accordion") ){
		$elements.each(function(index) {
			new Accordion( jQuery(this) );
		});
	}

	initDocSearch( $elements = jQuery(".doc-search") ){
		$elements.each( function() {
			new DocSearch( jQuery(this) );
		});
	}

	initModals() {
		new Modal( jQuery('.modal--login'), jQuery('.login-link') );
	}


}

new ST();