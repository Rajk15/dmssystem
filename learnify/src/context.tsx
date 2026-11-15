import { createContext, useContext, useMemo, useState, type ReactNode } from 'react'
import { profiles, type NavKey, type Role } from './data'

interface AppState {
  role: Role
  setRole: (role: Role) => void
  active: NavKey
  setActive: (key: NavKey) => void
  search: string
  setSearch: (value: string) => void
  toast: string | null
  notify: (message: string) => void
  selectedDay: number
  setSelectedDay: (day: number) => void
  profile: (typeof profiles)[Role]
}

const AppContext = createContext<AppState | null>(null)

export function AppProvider({ children }: { children: ReactNode }) {
  const [role, setRoleState] = useState<Role>('student')
  const [active, setActive] = useState<NavKey>('dashboard')
  const [search, setSearch] = useState('')
  const [toast, setToast] = useState<string | null>(null)
  const [selectedDay, setSelectedDay] = useState(9)

  const notify = (message: string) => {
    setToast(message)
    window.setTimeout(() => setToast(null), 2200)
  }

  const setRole = (next: Role) => {
    setRoleState(next)
    setActive('dashboard')
    notify(`Switched to ${profiles[next].title} dashboard`)
  }

  const value = useMemo(
    () => ({
      role,
      setRole,
      active,
      setActive,
      search,
      setSearch,
      toast,
      notify,
      selectedDay,
      setSelectedDay,
      profile: profiles[role],
    }),
    [role, active, search, toast, selectedDay],
  )

  return <AppContext.Provider value={value}>{children}</AppContext.Provider>
}

export function useApp() {
  const ctx = useContext(AppContext)
  if (!ctx) throw new Error('useApp must be used within AppProvider')
  return ctx
}
