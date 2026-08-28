const PRESETS = {
  fade: { opacity: 0 },
  slide_up: { opacity: 0, y: 30 },
  scale_in: { opacity: 0, scale: 0.5 },
  rotate_in: { opacity: 0, rotationX: -90 },
};

export function getSplitFromVars(type) {
  return PRESETS[type] || PRESETS.fade;
}
