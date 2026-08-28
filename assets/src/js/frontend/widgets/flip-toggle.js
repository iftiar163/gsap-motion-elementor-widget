import { gsap } from "gsap";
import { Flip } from "gsap/Flip";

gsap.registerPlugin(Flip);

function initFlipToggle(el) {
  const header = el.querySelector(".gme-flip-toggle-header");
  const wrap = el.querySelector(".gme-flip-toggle-content-wrap");

  if (!header || !wrap) {
    return;
  }

  let config;
  try {
    config = JSON.parse(el.dataset.gmeFlip);
  } catch (error) {
    config = {};
  }

  const duration = parseFloat(config.duration) || 0.4;
  const ease = config.easing || "power2.inOut";

  header.addEventListener("click", () => {
    // 1. Record current state BEFORE the change.
    const state = Flip.getState(wrap);

    // 2. Make the abrupt change — toggling this class flips
    // display between none/block via CSS (see frontend.scss).
    el.classList.toggle("is-open");

    // 3. Animate smoothly across the recorded difference.
    Flip.from(state, {
      duration,
      ease,
      height: true,
    });
  });
}

export function registerFlipToggleWidget() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/gme-flip-toggle.default",
    ($scope) => {
      initFlipToggle($scope[0].querySelector(".gme-flip-toggle") || $scope[0]);
    },
  );
}
