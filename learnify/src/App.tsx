import { useState } from 'react'
import { AppProvider, useApp } from './context'
import { Sidebar } from './components/Sidebar'
import { Topbar } from './components/Topbar'
import { StudentDashboard } from './pages/StudentDashboard'
import { TrainerDashboard } from './pages/TrainerDashboard'
import { AdminDashboard } from './pages/AdminDashboard'
import { SecondaryPage } from './pages/SecondaryPages'

function Shell() {
  const { role, active, toast } = useApp()
  const [sidebarOpen, setSidebarOpen] = useState(false)

  return (
    <div className="app-shell">
      {sidebarOpen ? <div className="backdrop" onClick={() => setSidebarOpen(false)} /> : null}
      <Sidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
      <div className="main-panel">
        <Topbar onMenu={() => setSidebarOpen(true)} />
        <main className="content">
          {active === 'dashboard' ? (
            role === 'student' ? (
              <StudentDashboard />
            ) : role === 'trainer' ? (
              <TrainerDashboard />
            ) : (
              <AdminDashboard />
            )
          ) : (
            <SecondaryPage page={active} />
          )}
        </main>
      </div>
      {toast ? <div className="toast">{toast}</div> : null}
    </div>
  )
}

export default function App() {
  return (
    <AppProvider>
      <Shell />
    </AppProvider>
  )
}
