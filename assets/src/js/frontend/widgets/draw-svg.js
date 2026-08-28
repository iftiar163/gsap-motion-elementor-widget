import { gsap } from "gsap";
import { DrawSVGPlugin } from "gsap/DrawSVGPlugin";

gsap.registerPlugin(DrawSVGPlugin);

function initDrawSvg(el) {
  let config;

  try {
    config = JSON.parse(el.dataset.gmeDraw);
  } catch (error) {
    return;
  }

  const shapes = el.querySelectorAll(
    "path, circle, ellipse, line, polyline, polygon, rect",
  );

  if (!shapes.length) {
    return;
  }

  const vars = {
    drawSVG: "100%",
    duration: parseFloat(config.duration) || 2,
    ease: config.easing || "power1.inOut",
  };

  if ("on_scroll" === config.trigger) {
    vars.scrollTrigger = {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none none",
    };
  }

  gsap.set(shapes, { drawSVG: "0%" });
  gsap.to(shapes, vars);
}

export function registerDrawSvgWidget() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/gme-draw-svg.default",
    ($scope) => {
      initDrawSvg($scope[0].querySelector(".gme-draw-svg") || $scope[0]);
    },
  );
}
