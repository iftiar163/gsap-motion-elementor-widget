const esbuild = require("esbuild");
const sass = require("sass");
const fs = require("fs");
const path = require("path");

const isWatch = process.argv.includes("--watch");
const isProduction = process.argv.includes("--production");

/**
 * Entry points: each key becomes an output file in assets/build/
 * Add new entries here whenever we introduce a new bundle
 * (e.g. a widget-specific script later).
 */
const jsEntries = {
  admin: "assets/src/js/admin/admin.js",
  frontend: "assets/src/js/frontend/frontend.js",
};

const scssEntries = {
  admin: "assets/src/scss/admin/admin.scss",
  frontend: "assets/src/scss/frontend/frontend.scss",
};

/**
 * Compile all SCSS entries to assets/build/*.css
 */
function buildStyles() {
  for (const [name, srcPath] of Object.entries(scssEntries)) {
    const result = sass.compile(srcPath, {
      style: isProduction ? "compressed" : "expanded",
      sourceMap: !isProduction,
    });

    const outPath = `assets/build/${name}.css`;
    fs.mkdirSync(path.dirname(outPath), { recursive: true });
    fs.writeFileSync(outPath, result.css);
    console.log(`✓ Compiled ${outPath}`);
  }
}

/**
 * Bundle all JS entries to assets/build/*.js
 */
async function buildScripts() {
  const ctx = await esbuild.context({
    entryPoints: jsEntries,
    bundle: true,
    outdir: "assets/build",
    minify: isProduction,
    sourcemap: !isProduction,
    target: ["es2018"],
    logLevel: "info",
  });

  if (isWatch) {
    await ctx.watch();
    console.log("Watching JS for changes...");
  } else {
    await ctx.rebuild();
    await ctx.dispose();
  }
}

/**
 * Main run
 */
(async () => {
  buildStyles();
  await buildScripts();

  if (isWatch) {
    // Basic SCSS watch (esbuild only natively watches JS).
    fs.watch("assets/src/scss", { recursive: true }, () => {
      buildStyles();
    });
  }
})();
