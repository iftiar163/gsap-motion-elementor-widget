/**
 * Maps each animation "type" (saved from our Elementor control) to the
 * GSAP starting values used with gsap.from(). GSAP animates FROM these
 * values TO the element's natural/current state — so we only need to
 * describe where the animation starts, never where it ends.
 */
const PRESETS = {
	fade: { opacity: 0 },
	slide_up: { opacity: 0, y: 40 },
	slide_down: { opacity: 0, y: -40 },
	slide_left: { opacity: 0, x: 40 },
	slide_right: { opacity: 0, x: -40 },
	scale_in: { opacity: 0, scale: 0.8 },
	rotate_in: { opacity: 0, rotation: -15 },
};

/**
 * Get the starting GSAP values for a given animation type.
 * Falls back to 'fade' if the type is unrecognized — this keeps
 * things safe if we ever remove a type but old saved data still
 * references it.
 *
 * @param {string} type
 * @return {object}
 */
export function getFromVars( type ) {
	return PRESETS[ type ] || PRESETS.fade;
}