jQuery(document).ready(function(e) {
	jQuery('.testimonial-slider').slick({
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		pauseOnHover: false,
		autoplay: false,
		autoplaySpeed: 3000,
		speed: 1000,
		arrows: true,
		dots: true,
		adaptiveHeight: true,
		responsive: [
				{
				  breakpoint: 768,
				  settings: {
					dots: true,
					arrows:false
				  }
				}
				
			  ]
	})
});