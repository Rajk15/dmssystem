import { calendarEvents } from '../data'
import { useApp } from '../context'
import { Icons } from './Icons'

const days = Array.from({ length: 30 }, (_, i) => i + 1)
const dow = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']

export function CalendarCard() {
  const { selectedDay, setSelectedDay, notify } = useApp()
  const event = calendarEvents[selectedDay]

  return (
    <section className="card card-pad">
      <div className="section-head">
        <h3>June 2026</h3>
        <div style={{ display: 'flex', gap: 4 }}>
          <button className="icon-btn" type="button" aria-label="Previous month" style={{ color: '#64748b' }}>
            <Icons.ChevronLeft size={18} />
          </button>
          <button className="icon-btn" type="button" aria-label="Next month" style={{ color: '#64748b' }}>
            <Icons.ChevronRight size={18} />
          </button>
        </div>
      </div>

      <div className="calendar-grid">
        {dow.map((d) => (
          <div key={d} className="dow">
            {d}
          </div>
        ))}
        {Array.from({ length: 1 }).map((_, i) => (
          <div key={`pad-${i}`} />
        ))}
        {days.map((day) => (
          <button
            key={day}
            type="button"
            className={`day ${day === selectedDay ? 'active' : ''} ${calendarEvents[day] ? 'has-event' : ''}`}
            onClick={() => {
              setSelectedDay(day)
              if (calendarEvents[day]) notify(calendarEvents[day])
            }}
          >
            {String(day).padStart(2, '0')}
          </button>
        ))}
      </div>

      {event ? (
        <p className="muted" style={{ margin: '14px 0 0', fontSize: '0.85rem' }}>
          {event}
        </p>
      ) : null}
    </section>
  )
}
