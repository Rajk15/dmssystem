import {
  liveClasses,
  studentCourses,
  studentStats,
} from '../data'
import { useApp } from '../context'
import { CalendarCard } from '../components/CalendarCard'
import { ProgressChart } from '../components/ProgressChart'
import { Icons } from '../components/Icons'

function StatIcon({ name }: { name: (typeof studentStats)[number]['icon'] }) {
  if (name === 'book') return <Icons.BookOpen size={16} />
  if (name === 'check') return <Icons.CheckCircle2 size={16} />
  if (name === 'clock') return <Icons.Clock3 size={16} />
  return <Icons.Flame size={16} />
}

function Ring({ value, label, max = 100 }: { value: string; label: string; max?: number }) {
  const numeric = Number(String(value).replace(/[^\d.]/g, '')) || 0
  const pct = Math.min(100, (numeric / max) * 100)
  const r = 28
  const c = 2 * Math.PI * r
  const offset = c - (pct / 100) * c

  return (
    <div className="ring">
      <svg width="72" height="72" viewBox="0 0 72 72">
        <circle cx="36" cy="36" r={r} fill="none" stroke="rgba(255,255,255,0.12)" strokeWidth="6" />
        <circle
          cx="36"
          cy="36"
          r={r}
          fill="none"
          stroke="#60a5fa"
          strokeWidth="6"
          strokeLinecap="round"
          strokeDasharray={c}
          strokeDashoffset={offset}
          transform="rotate(-90 36 36)"
        />
        <text x="36" y="40" textAnchor="middle" fill="#fff" fontSize="14" fontWeight="800">
          {value}
        </text>
      </svg>
      <span>{label}</span>
    </div>
  )
}

export function StudentDashboard() {
  const { notify, setActive, search } = useApp()
  const courses = studentCourses.filter((c) =>
    `${c.title} ${c.category}`.toLowerCase().includes(search.toLowerCase()),
  )

  return (
    <div className="dashboard-grid">
      <div className="stack">
        <section className="hero">
          <div>
            <h1>Welcome back, Alex 👋</h1>
            <p>
              You&apos;re 72% toward this week&apos;s learning goal. Keep the streak going with one more
              focused lesson today.
            </p>
            <button className="btn btn-primary" type="button" onClick={() => setActive('courses')}>
              Continue Learning
            </button>
          </div>
          <div className="rings">
            <Ring value="32" label="Current streak" max={40} />
            <Ring value="126" label="Learning hours" max={160} />
            <Ring value="3.6K" label="XP points" max={5000} />
          </div>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Continue Learning</h3>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('courses')}>
              View all
            </button>
          </div>
          <div className="course-list">
            {courses.length === 0 ? (
              <div className="empty">No courses match your search.</div>
            ) : (
              courses.map((course) => (
                <article key={course.id} className="course-item">
                  <img className="thumb" src={course.thumb} alt="" />
                  <div className="course-meta">
                    <div className="tag">{course.category}</div>
                    <h4>{course.title}</h4>
                    <div className="muted" style={{ fontSize: '0.8rem' }}>
                      {course.lessonsDone}/{course.lessonsTotal} Lessons
                    </div>
                    <div className="progress" aria-hidden>
                      <span style={{ width: `${course.progress}%` }} />
                    </div>
                  </div>
                  <button
                    className="btn btn-outline btn-sm"
                    type="button"
                    onClick={() => notify(`Resumed “${course.title}”`)}
                  >
                    Resume Lesson
                  </button>
                </article>
              ))
            )}
          </div>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Upcoming Live Classes</h3>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('live')}>
              See schedule
            </button>
          </div>
          <div className="class-list">
            <div className="class-row muted" style={{ fontSize: '0.8rem', fontWeight: 700 }}>
              <div>Class Name</div>
              <div>Instructor</div>
              <div>Time</div>
              <div>Action</div>
            </div>
            {liveClasses.map((item) => (
              <div key={item.id} className="class-row">
                <div>
                  <h4>
                    {item.name}{' '}
                    {item.live ? <span className="live-pill">LIVE</span> : null}
                  </h4>
                </div>
                <div className="muted">{item.instructor}</div>
                <div className="muted">{item.time}</div>
                <div>
                  {item.live ? (
                    <button
                      className="btn btn-primary btn-sm"
                      type="button"
                      onClick={() => notify(`Joining “${item.name}”`)}
                    >
                      Join Now
                    </button>
                  ) : (
                    <button
                      className="btn btn-outline btn-sm"
                      type="button"
                      onClick={() => notify(`Reminder set for “${item.name}”`)}
                    >
                      Set Reminder
                    </button>
                  )}
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>

      <div className="stack">
        <div className="stat-grid">
          {studentStats.map((stat) => (
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
        <ProgressChart />
      </div>
    </div>
  )
}
