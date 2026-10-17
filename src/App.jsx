import { useEffect, useState } from 'react'
import './index.css'

const STORE = 'https://lenses.eyzakart.com'

const categories = [
  {
    name: 'Power Lenses',
    desc: 'Prescription lenses for clear, comfortable everyday vision.',
    href: `${STORE}/product-category/powered-lens/`,
  },
  {
    name: 'Colored Lenses',
    desc: 'Natural and bold shades to enhance or transform your look.',
    href: `${STORE}/product-category/aquacolor/`,
  },
  {
    name: 'Zero Power Lenses',
    desc: 'Style lenses with no correction — beauty without compromise.',
    href: `${STORE}/product-category/zero-power/`,
  },
  {
    name: 'Monthly Lenses',
    desc: 'Long-wear comfort and value for your daily routine.',
    href: `${STORE}/product-category/contact-lenses/`,
  },
  {
    name: 'Lens Care',
    desc: 'Solutions and cleaners to keep every pair fresh and safe.',
    href: `${STORE}/product-category/lens-solutions/`,
  },
]

const products = [
  {
    name: 'Bausch + Lomb ULTRA® Spherical',
    price: 3999,
    was: 4300,
    image: '/images/ultra.webp',
    href: `${STORE}/product/bausch-lomb-ultra-spherical-contact-lenses/`,
  },
  {
    name: 'Aquacolor Premium Spicy Gray',
    price: 349,
    was: 779,
    image: '/images/spicy-grey.jpg',
    href: `${STORE}/product/aquacolor-premium-spicy-gray-zero-power-monthly-starter-kit/`,
  },
  {
    name: 'Aqualens Comfort Solution 360 ml × 2',
    price: 699,
    was: 1098,
    image: '/images/comfort.jpg',
    href: `${STORE}/product/aqualens-comfort-contact-lens-solution-360-ml-x-2/`,
  },
  {
    name: 'Aqualens Digital Lens Cleaner',
    price: 149,
    was: 220,
    image: '/images/cleaner.jpg',
    href: `${STORE}/product/aqualens-digital-lens-cleaner-spray-100-ml/`,
  },
  {
    name: 'Opti-Free Pure Moist 300 ml',
    price: 649,
    was: 695,
    image: '/images/optifree.jpg',
    href: `${STORE}/product/opti-free-pure-moist-contact-lens-solution-300-ml/`,
  },
  {
    name: 'Fog-Resist Lens Cleaner 30 ml',
    price: 149,
    was: 499,
    image: '/images/fog.jpg',
    href: `${STORE}/product/fog-resist-lens-cleaner-30-ml/`,
  },
]

const colours = [
  {
    name: 'Hazel',
    image: '/images/color-hazel.jpg',
    href: `${STORE}/product-category/aquacolor/`,
  },
  {
    name: 'Grey',
    image: '/images/color-grey.jpg',
    href: `${STORE}/product-category/aquacolor/`,
  },
  {
    name: 'Aqua',
    image: '/images/color-aqua.jpg',
    href: `${STORE}/product-category/aquacolor/`,
  },
  {
    name: 'Envy Green',
    image: '/images/color-green.jpg',
    href: `${STORE}/product-category/aquacolor/`,
  },
]

const quotes = [
  {
    text: 'Ordered my contact lenses from Eyzakart and I’m really happy with the quality. Comfortable all day, excellent packaging.',
    who: 'Aman K.',
    where: 'Delhi',
  },
  {
    text: 'Very smooth shopping experience. I found my preferred lenses easily, the price was good, and delivery was quick.',
    who: 'Kavya R.',
    where: 'Jaipur',
  },
  {
    text: 'I ordered coloured lenses and loved the look. Comfortable, and the colour looked natural.',
    who: 'Verified buyer',
    where: 'India',
  },
]

function formatInr(n) {
  return `₹${n.toLocaleString('en-IN')}`
}

export default function App() {
  const [scrolled, setScrolled] = useState(false)
  const [menuOpen, setMenuOpen] = useState(false)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 24)
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  useEffect(() => {
    document.body.style.overflow = menuOpen ? 'hidden' : ''
    return () => {
      document.body.style.overflow = ''
    }
  }, [menuOpen])

  const closeMenu = () => setMenuOpen(false)

  return (
    <div className="site">
      <header className={`nav${scrolled ? ' is-scrolled' : ''}`}>
        <a className="nav-brand" href="#top" onClick={closeMenu}>
          <img src="/images/logo.png" alt="" width={38} height={38} />
          <span>EYZAKART</span>
        </a>
        <nav className="nav-links" aria-label="Primary">
          <a href="#shop">Shop</a>
          <a href="#colours">Colours</a>
          <a href="#guide">Guide</a>
          <a href="#stories">Stories</a>
        </nav>
        <a className="nav-cta" href={`${STORE}/product-category/contact-lenses/`}>
          Shop lenses
        </a>
        <button
          className="nav-toggle"
          type="button"
          aria-expanded={menuOpen}
          aria-controls="mobile-nav"
          onClick={() => setMenuOpen((v) => !v)}
        >
          <span />
          <span className="sr-only">Menu</span>
        </button>
      </header>

      <div
        id="mobile-nav"
        className={`nav-drawer${menuOpen ? ' is-open' : ''}`}
        hidden={!menuOpen}
      >
        <a href="#shop" onClick={closeMenu}>
          Shop
        </a>
        <a href="#colours" onClick={closeMenu}>
          Colours
        </a>
        <a href="#guide" onClick={closeMenu}>
          Guide
        </a>
        <a href="#stories" onClick={closeMenu}>
          Stories
        </a>
        <a href="tel:9041994555" onClick={closeMenu}>
          Call 9041994555
        </a>
      </div>

      <main id="top">
        <section className="hero" aria-label="Hero">
          <div className="hero-media">
            <img
              src="/images/hero.png"
              alt="Contact lenses on stone with water droplets"
              width={1600}
              height={667}
              fetchPriority="high"
            />
          </div>
          <div className="hero-shade" aria-hidden="true" />
          <div className="hero-inner">
            <p className="hero-brand">EYZAKART</p>
            <h1 className="hero-title">Clear vision. True colour.</h1>
            <p className="hero-copy">
              Premium power and coloured contact lenses for sight that feels effortless and eyes that look luminous.
            </p>
            <div className="hero-actions">
              <a className="btn btn-primary" href={`${STORE}/product-category/contact-lenses/`}>
                Shop lenses
              </a>
              <a className="btn btn-ghost" href="tel:9041994555">
                Call 9041994555
              </a>
            </div>
          </div>
        </section>

        <section className="categories" id="shop">
          <div className="section">
            <header className="section-head">
              <p className="section-kicker">Shop by need</p>
              <h2 className="section-title">Lenses tuned to how you see — and how you want to look.</h2>
              <p className="section-copy">
                From prescription clarity to colour play and daily care, find the pair that fits your routine.
              </p>
            </header>
            <div className="category-list">
              {categories.map((cat, i) => (
                <a key={cat.name} className="category-row" href={cat.href}>
                  <span className="category-index">{String(i + 1).padStart(2, '0')}</span>
                  <span>
                    <p className="category-name">{cat.name}</p>
                    <p className="category-desc">{cat.desc}</p>
                  </span>
                  <span className="category-arrow" aria-hidden="true">
                    →
                  </span>
                </a>
              ))}
            </div>
          </div>
        </section>

        <section aria-labelledby="picks-title">
          <div className="section">
            <header className="section-head">
              <p className="section-kicker">Treasured finds</p>
              <h2 className="section-title" id="picks-title">
                Trendsetters’ choices
              </h2>
              <p className="section-copy">
                Best-selling lenses and care essentials from brands you already trust.
              </p>
            </header>
            <div className="product-grid">
              {products.map((p) => (
                <a key={p.name} className="product" href={p.href}>
                  <div className="product-media">
                    <img src={p.image} alt="" width={600} height={600} loading="lazy" />
                  </div>
                  <div>
                    <h3 className="product-name">{p.name}</h3>
                    <div className="product-price">
                      <strong>{formatInr(p.price)}</strong>
                      <s>{formatInr(p.was)}</s>
                    </div>
                  </div>
                </a>
              ))}
            </div>
            <div className="section-foot">
              <a className="btn btn-teal" href={`${STORE}/product-category/contact-lenses/`}>
                Browse all products
              </a>
            </div>
          </div>
        </section>

        <section className="colours" id="colours">
          <div className="section">
            <header className="section-head">
              <p className="section-kicker">Coloured lenses</p>
              <h2 className="section-title">Find your perfect eye colour</h2>
              <p className="section-copy">
                Soft naturals to statement shades — tried-and-loved Aquacolor looks for every mood.
              </p>
            </header>
            <div className="colour-grid">
              {colours.map((c) => (
                <a key={c.name} className="colour" href={c.href}>
                  <figure style={{ margin: 0, height: '100%' }}>
                    <img src={c.image} alt={`${c.name} coloured contact lens`} loading="lazy" />
                    <figcaption>{c.name}</figcaption>
                  </figure>
                </a>
              ))}
            </div>
          </div>
        </section>

        <section id="guide">
          <div className="section guide-layout">
            <header className="section-head" style={{ marginBottom: 0 }}>
              <p className="section-kicker">Clarity starts here</p>
              <h2 className="section-title">Unlock a world of clear vision &amp; beautiful eyes</h2>
              <p className="section-copy">
                Whether you need everyday prescription comfort or a colour change, we help you choose the right lens
                type, schedule, and fit — from home.
              </p>
            </header>
            <div className="guide-panel">
              <h3>Know before you order</h3>
              <p>
                Learn how to read a prescription and measure pupillary distance so your next pair lands right the
                first time.
              </p>
              <div className="guide-links">
                <a className="link-plain" href={`${STORE}/`}>
                  How to read a prescription
                </a>
                <a className="link-plain" href={`${STORE}/`}>
                  About pupillary distance
                </a>
              </div>
            </div>
          </div>
        </section>

        <section id="stories">
          <div className="section">
            <header className="section-head">
              <p className="section-kicker">From our customers</p>
              <h2 className="section-title">Comfort, colour, and delivery that keep pace.</h2>
            </header>
            <div className="quotes">
              {quotes.map((q) => (
                <blockquote key={q.who + q.where} className="quote">
                  <p>“{q.text}”</p>
                  <footer>
                    <cite>{q.who}</cite> · {q.where}
                  </footer>
                </blockquote>
              ))}
            </div>
          </div>
        </section>

        <section className="cta-band">
          <div className="section">
            <div>
              <h2>Ready for clearer days?</h2>
              <p>Shop online or call us — we’re in Chandigarh, Mon–Sat 9:00–18:00.</p>
            </div>
            <div className="hero-actions">
              <a className="btn btn-teal" href={`${STORE}/product-category/contact-lenses/`}>
                Shop now
              </a>
              <a className="btn" style={{ border: '1px solid var(--line)', background: '#fff' }} href="mailto:info@eyzakart.com">
                info@eyzakart.com
              </a>
            </div>
          </div>
        </section>
      </main>

      <footer className="footer">
        <div className="footer-inner">
          <div>
            <p className="footer-brand">EYZAKART</p>
            <p>
              Premium contact lenses and care from Chandigarh — power, colour, and zero-power options for every look.
            </p>
          </div>
          <div>
            <h4>Visit</h4>
            <ul>
              <li>SCO 54–55, 4th Floor</li>
              <li>Sector 34 A, Chandigarh</li>
              <li>Mon–Sat · 9:00–18:00</li>
            </ul>
          </div>
          <div>
            <h4>Contact</h4>
            <ul>
              <li>
                <a href="tel:9041994555">9041994555</a>
              </li>
              <li>
                <a href="mailto:info@eyzakart.com">info@eyzakart.com</a>
              </li>
              <li>
                <a href={STORE}>lenses.eyzakart.com</a>
              </li>
            </ul>
          </div>
        </div>
        <div className="footer-meta">
          <span>© {new Date().getFullYear()} Eyzakart</span>
          <span>Power &amp; coloured lenses · Lens care</span>
        </div>
      </footer>
    </div>
  )
}
