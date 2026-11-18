
(function(){
  if (window.__fcHeaderFixLoaded) return;
  window.__fcHeaderFixLoaded = true;
  var css = "\n#lang-change,\n#google_translate_element{\n  display:inline-flex !important;\n  align-items:center !important;\n  max-width:180px !important;\n  vertical-align:middle !important;\n}\n#google_translate_element .goog-te-gadget,\n#google_translate_element .goog-te-gadget-simple{\n  background:transparent !important;\n  background-color:transparent !important;\n  border:1px solid rgba(255,255,255,.4) !important;\n  border-radius:4px !important;\n  padding:3px 10px !important;\n  font-size:12px !important;\n  line-height:18px !important;\n  color:#fff !important;\n  display:inline-flex !important;\n  align-items:center !important;\n  box-shadow:none !important;\n  height:auto !important;\n  width:auto !important;\n}\n#google_translate_element .goog-te-gadget-simple .goog-te-menu-value,\n#google_translate_element .goog-te-gadget-simple .goog-te-menu-value span,\n#google_translate_element .goog-te-gadget-simple a,\n#google_translate_element .goog-te-gadget-simple a:link,\n#google_translate_element .goog-te-gadget-simple a:visited,\n#google_translate_element .goog-te-gadget span{\n  color:#fff !important;\n  background:transparent !important;\n  border:none !important;\n  text-decoration:none !important;\n  font-size:12px !important;\n}\n#google_translate_element img,\n#google_translate_element .goog-te-gadget-icon{\n  display:none !important;\n}\n#google_translate_element .goog-te-combo{\n  background:#111 !important;\n  color:#fff !important;\n  border:1px solid rgba(255,255,255,.4) !important;\n  border-radius:4px !important;\n  padding:2px 8px !important;\n  font-size:12px !important;\n  max-width:170px !important;\n  margin:0 !important;\n}\n.top-bar .admin-area.dropdown{display:flex !important;align-items:center !important}\n.goog-te-banner-frame.skiptranslate,\n.goog-te-balloon-frame{display:none !important}\nbody{top:0 !important}\n.top-bar #google_translate_element,\n.top-bar #google_translate_element *{box-sizing:border-box !important}\n";
  var s = document.createElement('style');
  s.id = 'fc-header-fix-style';
  s.appendChild(document.createTextNode(css));
  (document.head || document.documentElement).appendChild(s);

  function scopeLuxuryStyles(){
    var root = document.querySelector('.luxury-tour');
    if (!root) return;
    var styles = root.querySelectorAll('style');
    if (!styles.length) {
      // nested broken HTML may still expose style nodes as descendants
      styles = document.querySelectorAll('.page-content-area style, .luxury-tour style, style');
    }
    Array.prototype.forEach.call(styles, function(styleEl){
      var txt = styleEl.textContent || '';
      if (!txt || txt.indexOf('fc-scoped') !== -1) return;
      if (txt.indexOf('box-sizing') === -1 || (txt.indexOf('Cinzel') === -1 && txt.indexOf('hero-grid') === -1 && txt.indexOf('*{') === -1 && txt.indexOf('* {') === -1)) {
        if (!(txt.indexOf('*{') !== -1 || txt.indexOf('* {') !== -1)) return;
      }
      var out = txt
        .replace(/\*\s*\{[^}]*\}/g, '.luxury-tour,.luxury-tour *{box-sizing:border-box;}')
        .replace(/\bhtml\s*\{[^}]*\}/g, '')
        .replace(/\bbody\s*\{/g, '.luxury-tour{')
        .replace(/(^|\})(\s*)a\s*\{/g, '$1$2.luxury-tour a{')
        .replace(/(^|\})(\s*)img\s*\{/g, '$1$2.luxury-tour img{')
        .replace(/(^|\})(\s*)section\s*\{/g, '$1$2.luxury-tour section{')
        .replace(/(^|\})(\s*)header\s*\{/g, '$1$2.luxury-tour header{')
        .replace(/(^|\})(\s*)nav\s*\{/g, '$1$2.luxury-tour nav{');
      // prefix common class selectors only when not already scoped
      out = out.replace(/(^|\})(\s*)(\.[a-zA-Z0-9_-]+(?:\s+\.[a-zA-Z0-9_-]+)*(?:\s+[a-z]+)?)\s*\{/g, function(_, a, sp, sel){
        if (sel.indexOf('.luxury-tour') === 0) return a + sp + sel + '{';
        if (sel.indexOf('@') === 0) return a + sp + sel + '{';
        return a + sp + '.luxury-tour ' + sel + '{';
      });
      styleEl.textContent = '/*fc-scoped*/' + out;
    });
  }
  function run(){ try { scopeLuxuryStyles(); } catch(e) {} }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
  else run();
  setTimeout(run, 300);
  setTimeout(run, 1200);
})();
