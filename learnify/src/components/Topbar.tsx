import { useApp } from '../context'
import type { Role } from '../data'
import { Icons } from './Icons'

const roles: Role[] = ['student', 'trainer', 'admin']

export function Topbar({ onMenu }: { onMenu: () => void }) {
  const { role, setRole, search, setSearch, profile, notify } = useApp()

  return (
    <header className="topbar">
      <button className="icon-btn mobile-toggle" type="button" aria-label="Open menu" onClick={onMenu}>
        <Icons.Menu size={20} color="#0f172a" />
      </button>

      <label className="search-box">
        <Icons.Search size={18} />
        <input
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Search courses, lessons."
          aria-label="Search courses and lessons"
        />
      </label>

      <div className="topbar-actions">
        <div className="role-switch" role="tablist" aria-label="Switch dashboard role">
          {roles.map((item) => (
            <button
              key={item}
              type="button"
              className={role === item ? 'active' : ''}
              onClick={() => setRole(item)}
            >
              {item[0].toUpperCase() + item.slice(1)}
            </button>
          ))}
        </div>

        <button
          className="icon-btn"
          type="button"
          aria-label="Language"
          onClick={() => notify('Language picker opened')}
          style={{ color: '#0f172a' }}
        >
          <Icons.Globe2 size={18} />
        </button>

        <button
          className="icon-btn"
          type="button"
          aria-label="Notifications"
          onClick={() => notify('You have 3 new notifications')}
          style={{ color: '#0f172a', position: 'relative' }}
        >
          <Icons.Bell size={18} />
          <span className="badge-dot" />
        </button>

        <div className="avatar" title={`${profile.name} · ${profile.title}`}>
          {profile.initials}
        </div>
      </div>
    </header>
  )
}
