/**
 * Frontend animation engine entry point.
 *
 * This file is the single entry point bundled by esbuild into
 * assets/build/frontend.js. All GSAP core + plugin imports happen
 * here (or in files this one imports), so esbuild can tree-shake
 * and bundle only what we actually use.
 */
import { gsap } from 'gsap';

// Temporary sanity check — confirms GSAP is bundled and running.
// We'll remove this console.log once real animation logic replaces it.
console.log( 'GSAP loaded:', gsap.version );
