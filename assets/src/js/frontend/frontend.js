/**
 * Frontend animation engine entry point.
 * This is where GSAP + ScrollTrigger + our custom animation
 * runner will live. Built out in a dedicated future step.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { getFromVars } from './animation-presets';

gsap.registerPlugin( ScrollTrigger );

/**
 * Build the full GSAP vars object (starting values + timing + behavior)
 * from our saved settings for a single element.
 *
 * @param {object} settings Parsed data-gme-animation JSON.
 * @return {object}
 */
function buildAnimationVars( settings ) {
	const vars = {
		...getFromVars( settings.type ),
		duration: parseFloat( settings.duration ) || 1,
		delay: parseFloat( settings.delay ) || 0,
		ease: settings.easing || 'power2.out',
		repeat: parseInt( settings.repeat, 10 ) || 0,
		yoyo: 'yes' === settings.yoyo,
	};

	return vars;
}

/**
 * Attach ScrollTrigger config onto an existing vars object, based on
 * whether this should "play once" or "scrub" with scroll position.
 *
 * @param {object} vars
 * @param {Element} el
 * @param {object} settings
 */
function applyScrollTrigger( vars, el, settings ) {
	if ( 'scrub' === settings.scroll_behavior ) {
		vars.scrollTrigger = {
			trigger: el,
			start: 'top 85%',
			end: 'bottom 15%',
			scrub: parseFloat( settings.scrub_amount ) || true,
		};
	} else {
		vars.scrollTrigger = {
			trigger: el,
			start: 'top 85%',
			toggleActions: 'play none none none',
		};
	}
}

/**
 * Find every animated element on the page and run its GSAP animation.
 */
function initAnimations() {
	const elements = document.querySelectorAll( '.gme-animate' );

	elements.forEach( ( el ) => {
		let settings;

		try {
			settings = JSON.parse( el.dataset.gmeAnimation );
		} catch ( error ) {
			return;
		}

		const vars = buildAnimationVars( settings );

		if ( 'on_scroll' === settings.trigger ) {
			applyScrollTrigger( vars, el, settings );
		}

		// Stagger children: animate the element's direct children
		// together, one after another, instead of animating the
		// element itself.
		if ( 'yes' === settings.stagger_children && el.children.length ) {
			vars.stagger = parseFloat( settings.stagger_amount ) || 0.15;
			gsap.from( Array.from( el.children ), vars );
			return;
		}

		gsap.from( el, vars );
	} );
}

document.addEventListener( 'DOMContentLoaded', initAnimations );