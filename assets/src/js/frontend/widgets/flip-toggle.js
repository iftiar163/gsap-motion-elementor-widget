import { gsap } from "gsap";

function initFlipToggle(el) {
  const header = el.querySelector(".gme-flip-toggle-header");
  const wrap = el.querySelector(".gme-flip-toggle-content-wrap");
  const inner = el.querySelector(".gme-flip-toggle-content");

  if (!header || !wrap || !inner) {
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

  // Set the correct starting height immediately (no animation),
  // based on whether "Start Expanded" was enabled.
  const startsOpen = el.classList.contains("is-open");
  gsap.set(wrap, { height: startsOpen ? "auto" : 0, overflow: "hidden" });

  header.addEventListener("click", () => {
    const isOpening = !el.classList.contains("is-open");
    el.classList.toggle("is-open");

    if (isOpening) {
      const targetHeight = inner.scrollHeight;
      gsap.fromTo(
        wrap,
        { height: 0 },
        {
          height: targetHeight,
          duration,
          ease,
          onComplete: () => {
            // Release to 'auto' so dynamic content (e.g. a
            // window resize reflowing text) still displays
            // correctly, rather than staying locked to a
            // stale pixel value.
            gsap.set(wrap, { height: "auto" });
          },
        },
      );
    } else {
      gsap.fromTo(
        wrap,
        { height: inner.scrollHeight },
        { height: 0, duration, ease },
      );
    }
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
