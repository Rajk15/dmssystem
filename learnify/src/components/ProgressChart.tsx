import { Cell, Pie, PieChart, ResponsiveContainer } from 'recharts'
import { progressBreakdown } from '../data'

export function ProgressChart() {
  return (
    <section className="card card-pad">
      <div className="section-head">
        <h3>Learning Progress</h3>
        <select
          aria-label="Progress period"
          defaultValue="weekly"
          style={{
            border: '1px solid var(--lf-border)',
            borderRadius: 999,
            padding: '6px 10px',
            background: '#fff',
            color: 'var(--lf-ink-soft)',
          }}
        >
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>

      <div className="chart-wrap">
        <div className="legend">
          {progressBreakdown.map((item) => (
            <div key={item.name} className="legend-item">
              <span className="swatch" style={{ background: item.color }} />
              <span>
                {item.name} · {item.value}%
              </span>
            </div>
          ))}
        </div>
        <div style={{ height: 150 }}>
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={progressBreakdown}
                dataKey="value"
                innerRadius={42}
                outerRadius={64}
                paddingAngle={3}
                stroke="none"
              >
                {progressBreakdown.map((entry) => (
                  <Cell key={entry.name} fill={entry.color} />
                ))}
              </Pie>
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>
    </section>
  )
}
