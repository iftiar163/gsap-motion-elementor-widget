import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

function formatNumber(value, useSeparator) {
  const rounded = Math.floor(value);
  return useSeparator ? rounded.toLocaleString() : String(rounded);
}

function initCounter(el) {
  const numberEl = el.querySelector(".gme-counter-number");
  if (!numberEl) {
    return;
  }

  const start = parseFloat(numberEl.dataset.start) || 0;
  const end = parseFloat(numberEl.dataset.end) || 0;
  const duration = parseFloat(numberEl.dataset.duration) || 2;
  const useSeparator = "yes" === numberEl.dataset.separator;

  ScrollTrigger.getAll().forEach((st) => {
    if (st.trigger === el) {
      st.kill();
    }
  });

  const counter = { value: start };

  gsap.to(counter, {
    value: end,
    duration,
    ease: "power1.out",
    scrollTrigger: {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none none",
    },
    onUpdate: () => {
      numberEl.textContent = formatNumber(counter.value, useSeparator);
    },
  });
}

export function registerCounterWidget() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/gme-counter.default",
    ($scope) => {
      initCounter($scope[0]);
    },
  );
}
