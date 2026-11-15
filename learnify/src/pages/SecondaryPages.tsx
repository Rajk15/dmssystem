import type { CSSProperties, ReactNode } from 'react'
import {
  adminUsers,
  assignments,
  certificates,
  liveClasses,
  messages,
  studentCourses,
  trainerCourses,
  type NavKey,
} from '../data'
import { useApp } from '../context'
import { CalendarCard } from '../components/CalendarCard'
import { ProgressChart } from '../components/ProgressChart'

export function SecondaryPage({ page }: { page: NavKey }) {
  const { role, notify, search, profile } = useApp()

  if (page === 'courses') {
    const list =
      role === 'trainer'
        ? trainerCourses.map((c) => ({
            id: c.id,
            title: c.title,
            meta: `${c.learners} learners · ${c.completion}% complete`,
          }))
        : studentCourses
            .filter((c) => `${c.title} ${c.category}`.toLowerCase().includes(search.toLowerCase()))
            .map((c) => ({
              id: c.id,
              title: c.title,
              meta: `${c.category} · ${c.progress}% complete`,
            }))

    return (
      <PageShell title={role === 'trainer' ? 'My Classes' : role === 'admin' ? 'Courses' : 'My Courses'}>
        <div className="page-grid">
          {list.map((item) => (
            <div
              key={item.id}
              className="card card-pad"
              style={{ display: 'flex', justifyContent: 'space-between', gap: 12 }}
            >
              <div>
                <h4 style={{ margin: '0 0 6px' }}>{item.title}</h4>
                <div className="muted">{item.meta}</div>
              </div>
              <button className="btn btn-outline btn-sm" type="button" onClick={() => notify(`Opened ${item.title}`)}>
                Open
              </button>
            </div>
          ))}
        </div>
      </PageShell>
    )
  }

  if (page === 'live') {
    return (
      <PageShell title="Live Classes">
        <div className="card card-pad">
          {liveClasses.map((item) => (
            <div key={item.id} className="class-row">
              <div>
                <h4>
                  {item.name} {item.live ? <span className="live-pill">LIVE</span> : null}
                </h4>
              </div>
              <div className="muted">{item.instructor}</div>
              <div className="muted">{item.time}</div>
              <button
                className={`btn ${item.live ? 'btn-primary' : 'btn-outline'} btn-sm`}
                type="button"
                onClick={() => notify(item.live ? `Joined ${item.name}` : `Reminder set for ${item.name}`)}
              >
                {item.live ? 'Join Now' : 'Set Reminder'}
              </button>
            </div>
          ))}
        </div>
      </PageShell>
    )
  }

  if (page === 'assignments') {
    return (
      <PageShell title="Assignments">
        <div className="card card-pad">
          <table className="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Course</th>
                <th>Due</th>
                <th>Status</th>
                <th />
              </tr>
            </thead>
            <tbody>
              {assignments.map((a) => (
                <tr key={a.id}>
                  <td>{a.title}</td>
                  <td>{a.course}</td>
                  <td>{a.due}</td>
                  <td>{a.status}</td>
                  <td>
                    <button
                      className="btn btn-outline btn-sm"
                      type="button"
                      onClick={() => notify(`${role === 'trainer' ? 'Reviewing' : 'Opening'} ${a.title}`)}
                    >
                      {role === 'trainer' ? 'Review' : 'Open'}
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </PageShell>
    )
  }

  if (page === 'certificates') {
    return (
      <PageShell title="Certificates">
        <div className="page-grid">
          {certificates.map((cert) => (
            <div key={cert.id} className="card card-pad">
              <h4 style={{ marginTop: 0 }}>{cert.title}</h4>
              <p className="muted">
                Issued {cert.issued} · {cert.idCode}
              </p>
              <button
                className="btn btn-primary btn-sm"
                type="button"
                onClick={() => notify(`Downloading ${cert.title}`)}
              >
                Download PDF
              </button>
            </div>
          ))}
        </div>
      </PageShell>
    )
  }

  if (page === 'progress') {
    return (
      <PageShell title="Progress">
        <div className="dashboard-grid">
          <ProgressChart />
          <CalendarCard />
        </div>
      </PageShell>
    )
  }

  if (page === 'messages') {
    return (
      <PageShell title="Messages">
        <div className="card card-pad page-grid">
          {messages.map((m) => (
            <button
              key={m.id}
              type="button"
              className="card card-pad"
              style={{
                textAlign: 'left',
                border: '1px solid var(--lf-border)',
                background: m.unread ? '#eff6ff' : '#fff',
              }}
              onClick={() => notify(`Opened message from ${m.from}`)}
            >
              <div style={{ display: 'flex', justifyContent: 'space-between', gap: 12 }}>
                <strong>{m.from}</strong>
                <span className="muted">{m.time}</span>
              </div>
              <p className="muted" style={{ marginBottom: 0 }}>
                {m.preview}
              </p>
            </button>
          ))}
        </div>
      </PageShell>
    )
  }

  if (page === 'calendar') {
    return (
      <PageShell title="Calendar">
        <div style={{ maxWidth: 420 }}>
          <CalendarCard />
        </div>
      </PageShell>
    )
  }

  if (page === 'students' || page === 'trainers') {
    const rows = adminUsers.filter((u) =>
      page === 'students' ? u.role === 'Student' || role !== 'admin' : u.role === 'Trainer',
    )
    return (
      <PageShell title={page === 'students' ? 'Students' : 'Trainers'}>
        <div className="card card-pad">
          <table className="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th />
              </tr>
            </thead>
            <tbody>
              {(rows.length ? rows : adminUsers).map((u) => (
                <tr key={u.name}>
                  <td>{u.name}</td>
                  <td>{u.role}</td>
                  <td>{u.status}</td>
                  <td>{u.joined}</td>
                  <td>
                    <button className="btn btn-outline btn-sm" type="button" onClick={() => notify(`Opened ${u.name}`)}>
                      View
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </PageShell>
    )
  }

  if (page === 'reports') {
    return (
      <PageShell title="Reports">
        <div className="stat-grid" style={{ gridTemplateColumns: 'repeat(auto-fit,minmax(180px,1fr))' }}>
          {[
            ['Completion Rate', '78%'],
            ['Live Attendance', '91%'],
            ['Assignment Turn-in', '84%'],
            ['Churn Risk', '6%'],
          ].map(([label, value]) => (
            <div key={label} className="stat-card">
              <strong>{value}</strong>
              <span>{label}</span>
            </div>
          ))}
        </div>
      </PageShell>
    )
  }

  if (page === 'settings') {
    return (
      <PageShell title="Settings">
        <div className="card card-pad stack">
          <label>
            Display name
            <input defaultValue={profile.name} style={inputStyle} />
          </label>
          <label>
            Email
            <input defaultValue={profile.email} style={inputStyle} />
          </label>
          <button
            className="btn btn-primary"
            type="button"
            style={{ alignSelf: 'start' }}
            onClick={() => notify('Settings saved')}
          >
            Save changes
          </button>
        </div>
      </PageShell>
    )
  }

  return (
    <PageShell title="Help Center">
      <div className="card card-pad">
        <p className="muted">Browse guides for courses, live sessions, certificates, and billing.</p>
        <button className="btn btn-primary" type="button" onClick={() => notify('Help article opened')}>
          Open getting started guide
        </button>
      </div>
    </PageShell>
  )
}

function PageShell({ title, children }: { title: string; children: ReactNode }) {
  return (
    <div className="stack">
      <div>
        <h2 className="page-title">{title}</h2>
        <p className="muted" style={{ margin: 0 }}>
          Interactive Learnify workspace with shared design system across roles.
        </p>
      </div>
      {children}
    </div>
  )
}

const inputStyle: CSSProperties = {
  display: 'block',
  width: '100%',
  marginTop: 6,
  marginBottom: 8,
  padding: '12px 14px',
  borderRadius: 12,
  border: '1px solid var(--lf-border)',
}
