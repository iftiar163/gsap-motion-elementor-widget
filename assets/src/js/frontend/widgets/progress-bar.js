import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

function initProgressBar(el) {
  const fill = el.querySelector(".gme-progress-fill");
  const label = el.querySelector(".gme-progress-percentage");

  if (!fill) {
    return;
  }

  let config;
  try {
    config = JSON.parse(el.dataset.gmeProgress);
  } catch (error) {
    return;
  }

  const target = parseFloat(config.percentage) || 0;
  const duration = parseFloat(config.duration) || 1.5;
  const ease = config.easing || "power2.out";

  const proxy = { value: 0 };

  gsap.to(proxy, {
    value: target,
    duration,
    ease,
    scrollTrigger: {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none none",
    },
    onUpdate: () => {
      fill.style.width = proxy.value + "%";
      if (label) {
        label.textContent = Math.round(proxy.value) + "%";
      }
    },
  });
}

export function registerProgressBarWidget() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/gme-progress-bar.default",
    ($scope) => {
      initProgressBar(
        $scope[0].querySelector(".gme-progress-bar") || $scope[0],
      );
    },
  );
}
