import { adminStats, adminUsers, studentCourses } from '../data'
import { useApp } from '../context'
import { Icons } from '../components/Icons'

function StatIcon({ name }: { name: (typeof adminStats)[number]['icon'] }) {
  if (name === 'users') return <Icons.Users size={16} />
  if (name === 'book') return <Icons.BookOpen size={16} />
  if (name === 'trainer') return <Icons.GraduationCap size={16} />
  return <Icons.CircleDollarSign size={16} />
}

export function AdminDashboard() {
  const { notify, setActive, search, profile } = useApp()
  const users = adminUsers.filter((u) =>
    `${u.name} ${u.role}`.toLowerCase().includes(search.toLowerCase()),
  )

  return (
    <div className="dashboard-grid">
      <div className="stack">
        <section className="hero">
          <div>
            <h1>Welcome back, {profile.name.split(' ')[0]} 👋</h1>
            <p>
              Platform health looks strong: 92% weekly active learners and 6 new courses awaiting publish
              approval.
            </p>
            <button className="btn btn-primary" type="button" onClick={() => setActive('courses')}>
              Review Courses
            </button>
          </div>
          <div className="rings">
            <div className="ring">
              <strong>92%</strong>
              <span>WAU</span>
            </div>
            <div className="ring">
              <strong>6</strong>
              <span>Pending</span>
            </div>
            <div className="ring">
              <strong>$48K</strong>
              <span>Revenue</span>
            </div>
          </div>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>User Directory</h3>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('students')}>
              Manage users
            </button>
          </div>
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
              {users.map((user) => (
                <tr key={user.name}>
                  <td>{user.name}</td>
                  <td>{user.role}</td>
                  <td>{user.status}</td>
                  <td>{user.joined}</td>
                  <td>
                    <button
                      className="btn btn-outline btn-sm"
                      type="button"
                      onClick={() => notify(`Opened profile for ${user.name}`)}
                    >
                      View
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Course Catalog</h3>
            <button
              className="btn btn-primary btn-sm"
              type="button"
              onClick={() => notify('New course draft created')}
            >
              Add Course
            </button>
          </div>
          <div className="course-list">
            {studentCourses.map((course) => (
              <article key={course.id} className="course-item">
                <img className="thumb" src={course.thumb} alt="" />
                <div className="course-meta">
                  <div className="tag">{course.category}</div>
                  <h4>{course.title}</h4>
                  <div className="muted" style={{ fontSize: '0.8rem' }}>
                    {course.lessonsTotal} lessons · Published
                  </div>
                </div>
                <button
                  className="btn btn-outline btn-sm"
                  type="button"
                  onClick={() => notify(`Editing “${course.title}”`)}
                >
                  Edit
                </button>
              </article>
            ))}
          </div>
        </section>
      </div>

      <div className="stack">
        <div className="stat-grid">
          {adminStats.map((stat) => (
            <div key={stat.label} className="stat-card">
              <div className="stat-icon">
                <StatIcon name={stat.icon} />
              </div>
              <strong>{stat.value}</strong>
              <span>{stat.label}</span>
            </div>
          ))}
        </div>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Ops Alerts</h3>
          </div>
          {[
            '2 trainers awaiting KYC verification',
            'Certificate queue has 14 exports',
            'Live class capacity at 88% tonight',
          ].map((alert) => (
            <div
              key={alert}
              style={{
                padding: '12px 0',
                borderBottom: '1px solid var(--lf-border)',
                display: 'flex',
                justifyContent: 'space-between',
                gap: 12,
                alignItems: 'center',
              }}
            >
              <span>{alert}</span>
              <button className="btn btn-outline btn-sm" type="button" onClick={() => notify(alert)}>
                Open
              </button>
            </div>
          ))}
        </section>

        <section className="card card-pad">
          <div className="section-head">
            <h3>Quick Actions</h3>
          </div>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 10 }}>
            <button className="btn btn-primary btn-sm" type="button" onClick={() => setActive('trainers')}>
              Invite Trainer
            </button>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('reports')}>
              View Reports
            </button>
            <button className="btn btn-outline btn-sm" type="button" onClick={() => setActive('settings')}>
              Platform Settings
            </button>
          </div>
        </section>
      </div>
    </div>
  )
}
