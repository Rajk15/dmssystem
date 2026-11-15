export type Role = 'student' | 'trainer' | 'admin'

export type NavKey =
  | 'dashboard'
  | 'courses'
  | 'live'
  | 'assignments'
  | 'certificates'
  | 'progress'
  | 'messages'
  | 'calendar'
  | 'students'
  | 'trainers'
  | 'reports'
  | 'settings'
  | 'help'

export interface UserProfile {
  name: string
  initials: string
  email: string
  title: string
}

export const profiles: Record<Role, UserProfile> = {
  student: {
    name: 'Alex Harper',
    initials: 'AH',
    email: 'alex@learnify.app',
    title: 'Student',
  },
  trainer: {
    name: 'Jordan Lee',
    initials: 'JL',
    email: 'jordan@learnify.app',
    title: 'Trainer',
  },
  admin: {
    name: 'Sam Rivera',
    initials: 'SR',
    email: 'sam@learnify.app',
    title: 'Admin',
  },
}

export const studentCourses = [
  {
    id: 'c1',
    title: 'UI/UX Design Masterclass',
    category: 'Design',
    lessonsDone: 16,
    lessonsTotal: 20,
    progress: 80,
    thumb:
      'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=200&q=80',
  },
  {
    id: 'c2',
    title: 'React Development',
    category: 'Development',
    lessonsDone: 10,
    lessonsTotal: 24,
    progress: 42,
    thumb:
      'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&w=200&q=80',
  },
  {
    id: 'c3',
    title: 'Product Communication',
    category: 'Soft Skills',
    lessonsDone: 7,
    lessonsTotal: 12,
    progress: 58,
    thumb:
      'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=200&q=80',
  },
]

export const liveClasses = [
  {
    id: 'l1',
    name: 'Advanced Figma Systems',
    instructor: 'Mia Chen',
    time: 'Today · 4:30 PM',
    live: true,
  },
  {
    id: 'l2',
    name: 'React Hooks Deep Dive',
    instructor: 'Omar Diaz',
    time: 'Tomorrow · 10:00 AM',
    live: false,
  },
  {
    id: 'l3',
    name: 'Portfolio Critique Lab',
    instructor: 'Jordan Lee',
    time: 'Fri · 2:00 PM',
    live: false,
  },
]

export const progressBreakdown = [
  { name: 'Communication', value: 60, color: '#2563EB' },
  { name: 'UI Design', value: 24, color: '#8B5CF6' },
  { name: 'Coding', value: 16, color: '#14B8A6' },
]

export const studentStats = [
  { label: 'Enrolled', value: '24', icon: 'book' as const },
  { label: 'Completed', value: '06', icon: 'check' as const },
  { label: 'Learning Hours', value: '45 hrs', icon: 'clock' as const },
  { label: 'Streak', value: '7 Days', icon: 'flame' as const },
]

export const trainerCourses = [
  {
    id: 't1',
    title: 'UI Systems Studio',
    learners: 48,
    nextSession: 'Today 4:30 PM',
    completion: 72,
  },
  {
    id: 't2',
    title: 'Frontend Mentorship',
    learners: 31,
    nextSession: 'Wed 11:00 AM',
    completion: 54,
  },
  {
    id: 't3',
    title: 'Critique Lab',
    learners: 22,
    nextSession: 'Fri 2:00 PM',
    completion: 88,
  },
]

export const trainerStats = [
  { label: 'Active Classes', value: '08', icon: 'live' as const },
  { label: 'Students', value: '126', icon: 'users' as const },
  { label: 'Assignments', value: '17', icon: 'file' as const },
  { label: 'Avg. Score', value: '86%', icon: 'chart' as const },
]

export const adminStats = [
  { label: 'Total Users', value: '2,480', icon: 'users' as const },
  { label: 'Active Courses', value: '64', icon: 'book' as const },
  { label: 'Trainers', value: '38', icon: 'trainer' as const },
  { label: 'Monthly Revenue', value: '$48K', icon: 'dollar' as const },
]

export const adminUsers = [
  { name: 'Alex Harper', role: 'Student', status: 'Active', joined: 'Mar 12' },
  { name: 'Jordan Lee', role: 'Trainer', status: 'Active', joined: 'Jan 04' },
  { name: 'Priya Shah', role: 'Student', status: 'Invited', joined: 'Jun 01' },
  { name: 'Chris Park', role: 'Trainer', status: 'Active', joined: 'Feb 18' },
  { name: 'Nora Kim', role: 'Admin', status: 'Active', joined: 'Oct 09' },
]

export const assignments = [
  { id: 'a1', title: 'Design Case Study', course: 'UI/UX Masterclass', due: 'Jun 12', status: 'Pending' },
  { id: 'a2', title: 'Hooks Challenge', course: 'React Development', due: 'Jun 15', status: 'Submitted' },
  { id: 'a3', title: 'Pitch Deck Draft', course: 'Communication', due: 'Jun 18', status: 'Pending' },
]

export const certificates = [
  { id: 'cert1', title: 'Foundations of UX', issued: 'Apr 2026', idCode: 'LF-UX-2041' },
  { id: 'cert2', title: 'JavaScript Essentials', issued: 'Feb 2026', idCode: 'LF-JS-1188' },
]

export const messages = [
  { id: 'm1', from: 'Mia Chen', preview: 'Your Figma file feedback is ready.', time: '12m', unread: true },
  { id: 'm2', from: 'Support', preview: 'Certificate download is available.', time: '2h', unread: false },
  { id: 'm3', from: 'Omar Diaz', preview: 'Reminder: live class tomorrow.', time: '1d', unread: false },
]

export const calendarEvents: Record<number, string> = {
  9: 'Live: Advanced Figma Systems',
  11: 'Assignment due: Hooks Challenge',
  15: 'Portfolio Critique Lab',
  22: 'Weekly progress review',
}

export function navForRole(role: Role): { key: NavKey; label: string }[] {
  if (role === 'student') {
    return [
      { key: 'dashboard', label: 'Dashboard' },
      { key: 'courses', label: 'My Courses' },
      { key: 'live', label: 'Live Classes' },
      { key: 'assignments', label: 'Assignments' },
      { key: 'certificates', label: 'Certificates' },
      { key: 'progress', label: 'Progress' },
      { key: 'messages', label: 'Messages' },
      { key: 'calendar', label: 'Calendar' },
    ]
  }

  if (role === 'trainer') {
    return [
      { key: 'dashboard', label: 'Dashboard' },
      { key: 'courses', label: 'My Classes' },
      { key: 'live', label: 'Live Sessions' },
      { key: 'students', label: 'Students' },
      { key: 'assignments', label: 'Assignments' },
      { key: 'messages', label: 'Messages' },
      { key: 'calendar', label: 'Calendar' },
      { key: 'reports', label: 'Reports' },
    ]
  }

  return [
    { key: 'dashboard', label: 'Dashboard' },
    { key: 'courses', label: 'Courses' },
    { key: 'students', label: 'Students' },
    { key: 'trainers', label: 'Trainers' },
    { key: 'live', label: 'Live Classes' },
    { key: 'reports', label: 'Reports' },
    { key: 'messages', label: 'Messages' },
    { key: 'settings', label: 'Settings' },
  ]
}
