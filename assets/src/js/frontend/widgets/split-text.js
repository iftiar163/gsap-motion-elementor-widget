import { gsap } from "gsap";
import { SplitText } from "gsap/SplitText";
import { getSplitFromVars } from "./split-text-presets";

gsap.registerPlugin(SplitText);

function initSplitText(el) {
  let config;

  try {
    config = JSON.parse(el.dataset.gmeSplit);
  } catch (error) {
    return;
  }

  const splitTypeMap = {
    chars: "chars",
    words: "words",
    lines: "lines",
  };

  const split = new SplitText(el, {
    type: splitTypeMap[config.split_by] || "chars",
  });

  const targets = split[config.split_by] || split.chars;

  const vars = {
    ...getSplitFromVars(config.type),
    duration: parseFloat(config.duration) || 0.6,
    stagger: parseFloat(config.stagger) || 0.03,
    ease: config.easing || "power2.out",
  };

  if ("on_scroll" === config.trigger) {
    vars.scrollTrigger = {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none none",
    };
  }

  gsap.from(targets, vars);
}

export function registerSplitTextWidget() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/gme-split-text.default",
    ($scope) => {
      initSplitText($scope[0].querySelector(".gme-split-text") || $scope[0]);
    },
  );
}
