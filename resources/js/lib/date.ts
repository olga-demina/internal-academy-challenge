import { format, parseISO } from 'date-fns';

export function formatDateTime(date: string) {
    return format(parseISO(date), 'dd/MM/yyyy HH:mm');
}

export function formatRange(start: string, end: string) {
    return `${format(parseISO(start), 'dd/MM/yyyy HH:mm')} - ${format(parseISO(end), 'HH:mm')}`;
}
