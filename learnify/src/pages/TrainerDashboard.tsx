import { liveClasses, trainerCourses, trainerStats } from '../data'
import { useApp } from '../context'
import { CalendarCard } from '../components/CalendarCard'
import { Icons } from '../components/Icons'

function StatIcon({ name }: { name: (typeof trainerStats)[number]['icon'] }) {
  if (name === 'live') return <Icons.Radio size={16} />
  if (name === 'users') return <Icons.Users size={16} />
  if (name === 'file') return <Icons.FileText size={16} />
  return <Icons.BarChart3 size={16} />
}

export function TrainerDashboard() {
  const { notify, setActive, search, profile } = useApp()
  const classes = trainerCourses.filter((c) =>
    c.title.toLowerCase().includes(search.toLowerCase()),
  )

  return (
    <div className="dashboard-grid">
      <div className="stack">
        <section className="hero">
          <div>
            <h1>Welcome back, {profile.name.split(' ')[0]} 👋</h1>
            <p>
              You have 2 live sessions today and 5 submissions waiting for review. Keep mentoring momentum
              high.
            </p>
            <button className="btn btn-primary" type="button" onClick={() => setActive('live')}>
              Start Next Session
            </button>
          </div>
          <div className="rings">
            <div className="ring">
              <strong>08</strong>
              <span>Classes live</span>
            </div>
            <div className="ring">
              <strong>17</strong>
              <span>To review</span>
            </div>
            <div className="ring">
              <strong>4.9</strong>
              <span>Trainer rating</span>
            </div>
          </div>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>My Classes</h3>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('courses')}>
              Manage
            </button>
          </div>
          <div className="course-list">
            {classes.map((course) => (
              <article key={course.id} className="course-item" style={{ gridTemplateColumns: '1fr auto' }}>
                <div className="course-meta">
                  <div className="tag">{course.learners} learners</div>
                  <h4>{course.title}</h4>
                  <div className="muted" style={{ fontSize: '0.8rem' }}>
                    Next · {course.nextSession}
                  </div>
                  <div className="progress" aria-hidden>
                    <span style={{ width: `${course.completion}%` }} />
                  </div>
                </div>
                <button
                  className="btn btn-outline btn-sm"
                  type="button"
                  onClick={() => notify(`Opened “${course.title}” classroom`)}
                >
                  Open Class
                </button>
              </article>
            ))}
          </div>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Upcoming Live Sessions</h3>
          </div>
          <div className="class-list">
            {liveClasses.map((item) => (
              <div key={item.id} className="class-row">
                <div>
                  <h4>
                    {item.name} {item.live ? <span className="live-pill">LIVE</span> : null}
                  </h4>
                </div>
                <div className="muted">{item.instructor}</div>
                <div className="muted">{item.time}</div>
                <div>
                  <button
                    className="btn btn-primary btn-sm"
                    type="button"
                    onClick={() => notify(item.live ? `Going live: ${item.name}` : `Prepared ${item.name}`)}
                  >
                    {item.live ? 'Go Live' : 'Prepare'}
                  </button>
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>

      <div className="stack">
        <div className="stat-grid">
          {trainerStats.map((stat) => (
            <div key={stat.label} className="stat-card">
              <div className="stat-icon">
                <StatIcon name={stat.icon} />
              </div>
              <strong>{stat.value}</strong>
              <span>{stat.label}</span>
            </div>
          ))}
        </div>
        <CalendarCard />
        <section className="card card-pad">
          <div className="section-head">
            <h3>Review Queue</h3>
          </div>
          <div className="stack" style={{ gap: 10 }}>
            {['Hooks Challenge · Alex', 'Case Study · Priya', 'Pitch Deck · Nora'].map((item) => (
              <div
                key={item}
                style={{
                  display: 'flex',
                  justifyContent: 'space-between',
                  gap: 12,
                  alignItems: 'center',
                  padding: '10px 0',
                  borderBottom: '1px solid var(--lf-border)',
                }}
              >
                <span>{item}</span>
                <button className="btn btn-outline btn-sm" type="button" onClick={() => notify(`Reviewing ${item}`)}>
                  Review
                </button>
              </div>
            ))}
          </div>
        </section>
      </div>
    </div>
  )
}
