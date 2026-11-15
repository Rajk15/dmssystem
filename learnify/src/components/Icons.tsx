import {
  Award,
  BarChart3,
  Bell,
  BookOpen,
  CalendarDays,
  CheckCircle2,
  ChevronLeft,
  ChevronRight,
  CircleDollarSign,
  Clock3,
  FileText,
  Flame,
  Globe2,
  GraduationCap,
  HelpCircle,
  LayoutDashboard,
  LogOut,
  Menu,
  MessageSquare,
  PanelLeftClose,
  Radio,
  Search,
  Settings,
  Users,
  type LucideIcon,
} from 'lucide-react'
import type { NavKey } from '../data'

const navIcons: Record<NavKey, LucideIcon> = {
  dashboard: LayoutDashboard,
  courses: BookOpen,
  live: Radio,
  assignments: FileText,
  certificates: Award,
  progress: BarChart3,
  messages: MessageSquare,
  calendar: CalendarDays,
  students: Users,
  trainers: GraduationCap,
  reports: BarChart3,
  settings: Settings,
  help: HelpCircle,
}

export function NavIcon({ name, size = 18 }: { name: NavKey; size?: number }) {
  const Icon = navIcons[name] ?? LayoutDashboard
  return <Icon size={size} strokeWidth={2} />
}

export const Icons = {
  Search,
  Bell,
  Globe2,
  Menu,
  PanelLeftClose,
  LogOut,
  Settings,
  HelpCircle,
  ChevronLeft,
  ChevronRight,
  BookOpen,
  CheckCircle2,
  Clock3,
  Flame,
  Radio,
  Users,
  FileText,
  BarChart3,
  CircleDollarSign,
  GraduationCap,
  Award,
}
