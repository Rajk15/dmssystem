
(function(){
  if (window.__fcHeaderFixLoaded) return;
  window.__fcHeaderFixLoaded = true;
  var css = "\n#lang-change,\n#google_translate_element{\n  display:inline-flex !important;\n  align-items:center !important;\n  max-width:180px !important;\n  vertical-align:middle !important;\n}\n#google_translate_element .goog-te-gadget,\n#google_translate_element .goog-te-gadget-simple{\n  background:transparent !important;\n  background-color:transparent !important;\n  border:1px solid rgba(255,255,255,.4) !important;\n  border-radius:4px !important;\n  padding:3px 10px !important;\n  font-size:12px !important;\n  line-height:18px !important;\n  color:#fff !important;\n  display:inline-flex !important;\n  align-items:center !important;\n  box-shadow:none !important;\n  height:auto !important;\n  width:auto !important;\n}\n#google_translate_element .goog-te-gadget-simple .goog-te-menu-value,\n#google_translate_element .goog-te-gadget-simple .goog-te-menu-value span,\n#google_translate_element .goog-te-gadget-simple a,\n#google_translate_element .goog-te-gadget-simple a:link,\n#google_translate_element .goog-te-gadget-simple a:visited,\n#google_translate_element .goog-te-gadget span{\n  color:#fff !important;\n  background:transparent !important;\n  border:none !important;\n  text-decoration:none !important;\n  font-size:12px !important;\n}\n#google_translate_element img,\n#google_translate_element .goog-te-gadget-icon{\n  display:none !important;\n}\n#google_translate_element .goog-te-combo{\n  background:#111 !important;\n  color:#fff !important;\n  border:1px solid rgba(255,255,255,.4) !important;\n  border-radius:4px !important;\n  padding:2px 8px !important;\n  font-size:12px !important;\n  max-width:170px !important;\n  margin:0 !important;\n}\n.top-bar .admin-area.dropdown{display:flex !important;align-items:center !important}\n.goog-te-banner-frame.skiptranslate,\n.goog-te-balloon-frame{display:none !important}\nbody{top:0 !important}\n.top-bar #google_translate_element,\n.top-bar #google_translate_element *{box-sizing:border-box !important}\n";
  var s = document.createElement('style');
  s.id = 'fc-header-fix-style';
  s.appendChild(document.createTextNode(css));
  document.head.appendChild(s);

  function scopeLuxuryStyles(){
    var root = document.querySelector('.luxury-tour');
    if (!root) return;
    var styles = root.querySelectorAll('style');
    styles.forEach(function(styleEl){
      var txt = styleEl.textContent || '';
      if (!txt || txt.indexOf('fc-scoped') !== -1) return;
      if (txt.indexOf('*{') === -1 && txt.indexOf('* {') === -1 && txt.indexOf('body{') === -1 && txt.indexOf('body {') === -1) return;
      var out = txt
        .replace(/\*\s*\{[^}]*\}/g, '.luxury-tour,.luxury-tour *{box-sizing:border-box;}')
        .replace(/\bhtml\s*\{[^}]*\}/g, '')
        .replace(/\bbody\s*\{/g, '.luxury-tour{')
        .replace(/(^|})\s*a\s*\{/g, '$1.luxury-tour a{')
        .replace(/(^|})\s*img\s*\{/g, '$1.luxury-tour img{')
        .replace(/(^|})\s*section\s*\{/g, '$1.luxury-tour section{')
        .replace(/(^|})\s*header\s*\{/g, '$1.luxury-tour header{')
        .replace(/(^|})\s*nav\s*\{/g, '$1.luxury-tour nav{')
        .replace(/(^|})\s*\.container\s*\{/g, '$1.luxury-tour .container{')
        .replace(/(^|})\s*\.btn\s*\{/g, '$1.luxury-tour .btn{')
        .replace(/(^|})\s*\.btn:hover\s*\{/g, '$1.luxury-tour .btn:hover{')
        .replace(/(^|})\s*\.gold\s*\{/g, '$1.luxury-tour .gold{')
        .replace(/(^|})\s*\.section-title\s*\{/g, '$1.luxury-tour .section-title{')
        .replace(/(^|})\s*\.section-subtitle\s*\{/g, '$1.luxury-tour .section-subtitle{')
        .replace(/(^|})\s*\.menu\s*\{/g, '$1.luxury-tour .menu{')
        .replace(/(^|})\s*\.menu a\s*\{/g, '$1.luxury-tour .menu a{')
        .replace(/(^|})\s*\.menu a:hover\s*\{/g, '$1.luxury-tour .menu a:hover{')
        .replace(/(^|})\s*\.logo\s*\{/g, '$1.luxury-tour .logo{')
        .replace(/(^|})\s*\.logo span\s*\{/g, '$1.luxury-tour .logo span{')
        .replace(/(^|})\s*\.hero\s*\{/g, '$1.luxury-tour .hero{')
        .replace(/(^|})\s*\.hero-grid\s*\{/g, '$1.luxury-tour .hero-grid{')
        .replace(/(^|})\s*\.hero h4\s*\{/g, '$1.luxury-tour .hero h4{')
        .replace(/(^|})\s*\.hero h1\s*\{/g, '$1.luxury-tour .hero h1{')
        .replace(/(^|})\s*\.hero p\s*\{/g, '$1.luxury-tour .hero p{')
        .replace(/(^|})\s*\.hero-card\s*\{/g, '$1.luxury-tour .hero-card{');
      styleEl.textContent = '/*fc-scoped*/' + out;
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', scopeLuxuryStyles);
  } else {
    scopeLuxuryStyles();
  }
  setTimeout(scopeLuxuryStyles, 500);
})();
