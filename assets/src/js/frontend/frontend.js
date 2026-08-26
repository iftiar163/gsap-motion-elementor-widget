import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { getFromVars } from "./animation-presets";
import { registerCounterWidget } from "./widgets/counter";

gsap.registerPlugin(ScrollTrigger);

function buildAnimationVars(settings) {
  return {
    ...getFromVars(settings.type),
    duration: parseFloat(settings.duration) || 1,
    delay: parseFloat(settings.delay) || 0,
    ease: settings.easing || "power2.out",
    repeat: parseInt(settings.repeat, 10) || 0,
    yoyo: "yes" === settings.yoyo,
  };
}

function applyScrollTrigger(vars, el, settings) {
  if ("scrub" === settings.scroll_behavior) {
    vars.scrollTrigger = {
      trigger: el,
      start: "top 85%",
      end: "bottom 15%",
      scrub: parseFloat(settings.scrub_amount) || true,
    };
  } else {
    vars.scrollTrigger = {
      trigger: el,
      start: "top 85%",
      toggleActions: "play none none none",
    };
  }
}

function animateElement(el) {
  if (!el || !el.classList || !el.classList.contains("gme-animate")) {
    return;
  }

  let settings;
  try {
    settings = JSON.parse(el.dataset.gmeAnimation);
  } catch (error) {
    return;
  }

  ScrollTrigger.getAll().forEach((st) => {
    if (st.trigger === el) {
      st.kill();
    }
  });
  gsap.killTweensOf(el);

  const vars = buildAnimationVars(settings);

  if ("on_scroll" === settings.trigger) {
    applyScrollTrigger(vars, el, settings);
  }

  if ("yes" === settings.stagger_children && el.children.length) {
    const children = Array.from(el.children);
    vars.stagger = parseFloat(settings.stagger_amount) || 0.15;
    gsap.killTweensOf(children);
    gsap.from(children, vars);
    return;
  }

  gsap.from(el, vars);
}

function registerWithElementor() {
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/global",
    ($scope) => {
      animateElement($scope[0]);
    },
  );

  registerCounterWidget();
}

if (window.elementorFrontend && window.elementorFrontend.hooks) {
  registerWithElementor();
} else if (window.elementorFrontend) {
  window.addEventListener("elementor/frontend/init", registerWithElementor);
} else {
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".gme-animate").forEach(animateElement);
  });
}
