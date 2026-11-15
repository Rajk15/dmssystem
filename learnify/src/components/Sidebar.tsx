import { navForRole } from '../data'
import { useApp } from '../context'
import { Icons, NavIcon } from './Icons'

export function Sidebar({
  open,
  onClose,
}: {
  open: boolean
  onClose: () => void
}) {
  const { role, active, setActive, notify } = useApp()
  const items = navForRole(role)

  return (
    <aside className={`sidebar ${open ? 'open' : ''}`} aria-label="Primary">
      <div className="brand">
        <div className="brand-left">
          <span className="brand-mark" aria-hidden>
            <Icons.BookOpen size={18} />
          </span>
          Learnify
        </div>
        <button className="collapse-btn" type="button" aria-label="Collapse sidebar" onClick={onClose}>
          <Icons.PanelLeftClose size={18} />
        </button>
      </div>

      <nav className="nav">
        {items.map((item) => (
          <button
            key={item.key}
            type="button"
            className={`nav-item ${active === item.key ? 'active' : ''}`}
            onClick={() => {
              setActive(item.key)
              onClose()
            }}
          >
            <NavIcon name={item.key} />
            {item.label}
          </button>
        ))}
      </nav>

      <div className="sidebar-bottom">
        <div className="premium-card">
          <h4>Unlock Premium Access</h4>
          <p>Get mentorship, certificates, and unlimited live classes.</p>
          <button
            className="btn btn-primary btn-sm"
            type="button"
            onClick={() => notify('Premium upgrade flow opened')}
          >
            Upgrade Pro
          </button>
        </div>

        <button
          className="nav-item"
          type="button"
          onClick={() => {
            setActive('settings')
            onClose()
          }}
        >
          <Icons.Settings size={18} />
          Settings
        </button>
        <button
          className="nav-item"
          type="button"
          onClick={() => {
            setActive('help')
            onClose()
          }}
        >
          <Icons.HelpCircle size={18} />
          Help Center
        </button>
        <button
          className="nav-item"
          type="button"
          onClick={() => notify('Signed out of Learnify demo')}
        >
          <Icons.LogOut size={18} />
          Logout
        </button>
      </div>
    </aside>
  )
}
